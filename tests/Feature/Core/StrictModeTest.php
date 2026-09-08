<?php

namespace Tests\Feature\Core;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\LoanRenewal;
use App\Modules\Circulation\Services\LoanRenewalService;
use App\Modules\Identity\Models\User;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\LazyLoadingViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Mode ketat Eloquent adalah jaring untuk satu kelas cacat, bukan sekadar
 * setelan: setiap cacat yang ditemukan saat menyusun test suite ini gagal
 * tanpa bersuara. Berkas ini menjaga agar jaringnya tetap terpasang — mudah
 * sekali hilang saat seseorang merapikan AppServiceProvider.
 */
class StrictModeTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function strict_mode_is_active_outside_production(): void
    {
        $this->assertTrue(Model::preventsSilentlyDiscardingAttributes());
        $this->assertTrue(Model::preventsLazyLoading());
        $this->assertTrue(Model::preventsAccessingMissingAttributes());
    }

    /**
     * Persis bentuk cacat `last_login_at`: kode menyetel atribut, mass
     * assignment membuangnya, kolom tetap NULL, dan tidak ada yang tahu.
     */
    #[Test]
    public function an_attribute_that_is_not_fillable_now_raises_instead_of_vanishing(): void
    {
        $this->expectException(MassAssignmentException::class);

        User::factory()->create()->update(['kolom_yang_tidak_ada_di_fillable' => 'nilai']);
    }

    /**
     * Eloquent hanya menegakkan larangan ini saat kueri menghidrasi LEBIH DARI
     * SATU baris — N+1 memang baru bermakna pada koleksi, dan halaman daftarlah
     * yang menderita karenanya. Test ini karena itu memuat dua record, bukan satu.
     */
    #[Test]
    public function lazy_loading_a_relation_across_a_collection_now_raises(): void
    {
        BibliographicRecord::factory()->count(2)->withAuthor()->create();

        $records = BibliographicRecord::query()->get();

        $this->expectException(LazyLoadingViolationException::class);

        $records->first()->authors->count();
    }

    #[Test]
    public function eager_loading_the_same_relation_stays_fine(): void
    {
        BibliographicRecord::factory()->count(2)->withAuthor()->create();

        $records = BibliographicRecord::query()->with('authors')->get();

        $this->assertCount(1, $records->first()->authors);
    }

    /**
     * Kueri satu baris sengaja dikecualikan Eloquent; dikunci di sini supaya
     * batasan itu dipahami sebagai keputusan, bukan celah yang terlewat.
     */
    #[Test]
    public function a_single_row_query_is_deliberately_exempt(): void
    {
        BibliographicRecord::factory()->withAuthor()->create();

        $record = BibliographicRecord::query()->firstOrFail();

        $this->assertCount(1, $record->authors);
    }

    /**
     * Regresi yang ditemukan mode ketat: LoanRenewal mematikan $timestamps,
     * sehingga `created_at` harus datang dari aplikasi. Sebelum kolom itu
     * masuk $fillable, nilainya dibuang dan yang tersimpan adalah jam server
     * basis data — bukan jam aplikasi.
     */
    #[Test]
    public function a_renewal_records_the_application_clock_not_the_database_clock(): void
    {
        $this->actingAsUserWith(['circulation.process_renewal']);
        Carbon::setTestNow('2026-07-01 09:00:00');
        $loan = $this->activeLoan(null, null, ['due_date' => '2026-07-05 09:00:00']);

        app(LoanRenewalService::class)->renew($loan);

        $this->assertSame(
            '2026-07-01 09:00:00',
            LoanRenewal::query()->firstOrFail()->created_at->toDateTimeString()
        );
    }
}

<?php

namespace Tests\Feature\Circulation;

use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\ReturnTransaction;
use App\Modules\Circulation\Services\ReturnProcessingService;
use App\Modules\Core\Services\OperationalRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Uang disimpan dan dihitung sebagai rupiah bulat.
 *
 * Rupiah tidak punya satuan pecahan yang dipakai; sen sudah lama tidak beredar
 * dan tarif denda perpustakaan selalu bilangan bulat. Menyimpannya sebagai
 * DECIMAL berarti setiap nilai melewati konversi float, dan `sum()` atas kolom
 * desimal mengembalikan string yang mudah tercampur saat dijumlahkan lagi di
 * laporan — tempat kesalahan pembulatan menumpuk tanpa terlihat.
 */
class MoneyIsWholeRupiahTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_fine_amount_comes_back_as_an_integer(): void
    {
        $fine = Fine::factory()->create(['amount' => 7500]);

        // assertSame bersifat ketat terhadap tipe: nilai '7500' maupun 7500.0
        // akan menggagalkannya, sehingga pemeriksaan tipe terpisah tidak perlu.
        $this->assertSame(7500, $fine->fresh()->amount);
    }

    #[Test]
    public function a_return_transaction_fine_amount_comes_back_as_an_integer(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan(3);

        app(ReturnProcessingService::class)->processReturn($loan->physicalItem->barcode);

        $this->assertSame(3000, ReturnTransaction::query()->firstOrFail()->fine_amount);
    }

    #[Test]
    public function the_daily_rate_from_the_settings_is_an_integer(): void
    {
        $this->assertSame(1000, app(OperationalRules::class)->fineDailyAmount());
    }

    /**
     * Perkalian bilangan bulat tidak pernah menghasilkan sisa pecahan, betapa
     * pun besar angkanya — inilah yang hilang saat nilai uang melewati float.
     */
    #[Test]
    public function a_long_overdue_fine_stays_exact(): void
    {
        $this->actingAsUserWith(['circulation.process_return']);
        $loan = $this->overdueLoan(365);

        $result = app(ReturnProcessingService::class)->processReturn($loan->physicalItem->barcode);

        $this->assertSame(365_000, $result['fine_amount']);
    }

    /**
     * Penjumlahan denda dipakai gerbang kelayakan pinjam dan laporan keuangan;
     * hasilnya harus tepat, bukan mendekati.
     */
    #[Test]
    public function summing_fines_stays_exact(): void
    {
        $member = $this->eligibleMember();
        foreach ([1_500, 2_750, 3_125, 999_999] as $amount) {
            Fine::factory()->forMember($member)->outstanding()->create(['amount' => $amount]);
        }

        $total = (int) $member->fines()->where('status', 'outstanding')->sum('amount');

        $this->assertSame(1_007_374, $total);
    }

    /**
     * Kolomnya sendiri harus bilangan bulat di basis data, bukan hanya di PHP —
     * kalau tidak, nilai pecahan masih bisa masuk lewat jalur lain.
     */
    #[Test]
    public function the_columns_are_integers_in_the_database(): void
    {
        foreach ([['fines', 'amount'], ['return_transactions', 'fine_amount']] as [$table, $column]) {
            $type = Schema::getColumnType($table, $column);

            $this->assertStringContainsString(
                'int',
                strtolower($type),
                "Kolom {$table}.{$column} bertipe {$type}, bukan bilangan bulat."
            );
        }
    }

    /**
     * Halaman Aturan Operasional menolak tarif pecahan, sehingga nilai bukan
     * bulat tidak pernah masuk lewat pintu depan.
     */
    #[Test]
    public function the_settings_page_rejects_a_fractional_daily_rate(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'fine_daily_amount' => 1500.75,
        ]))->assertSessionHasErrors('fine_daily_amount');
    }

    #[Test]
    public function a_whole_daily_rate_is_still_accepted(): void
    {
        $this->actingAsUserWith(['core.view_operational_rules', 'core.update_operational_rules']);

        $this->put(route('admin.settings.operational_rules.update'), $this->operationalRulePayload([
            'fine_daily_amount' => 2500,
        ]))->assertSessionHasNoErrors();

        $this->assertSame(2500, app(OperationalRules::class)->fineDailyAmount());
    }
}

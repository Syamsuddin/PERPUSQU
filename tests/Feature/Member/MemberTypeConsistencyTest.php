<?php

namespace Tests\Feature\Member;

use App\Modules\Core\Services\OperationalRules;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Support\MemberEligibilityResolver;
use Database\Seeders\MemberUserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Jenis anggota menyentuh empat hal sekaligus: validasi formulir, label di
 * layar, kunci lama pinjam di Aturan Operasional, dan data yang ditulis seeder.
 * Selama daftarnya tersalin di banyak tempat, keempatnya bisa menyimpang
 * diam-diam — dan memang sudah terjadi: MemberUserSeeder menulis `dosen`
 * alih-alih `lecturer`, sehingga dosen mendapat 14 hari, bukan 30, sementara
 * layar tetap menampilkan "Dosen".
 */
class MemberTypeConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setiap jenis anggota harus punya kunci lama pinjamnya sendiri. Tanpa itu,
     * jenis tersebut diam-diam jatuh ke `loan_default_days`.
     */
    #[Test]
    public function every_member_type_has_its_own_loan_period_setting(): void
    {
        $missing = [];

        foreach (array_keys(MemberEligibilityResolver::TYPES) as $type) {
            if (! array_key_exists('loan_days_'.$type, SystemSettings::DEFAULTS)) {
                $missing[] = $type;
            }
        }

        $this->assertSame(
            [],
            $missing,
            'Jenis anggota tanpa kunci lama pinjam: '.implode(', ', $missing)
        );
    }

    /**
     * Kebalikannya: kunci `loan_days_*` yang tidak punya jenis anggota adalah
     * kolom pengaturan yang tidak pernah dipakai siapa pun.
     */
    #[Test]
    public function every_loan_period_setting_belongs_to_a_known_member_type(): void
    {
        $orphaned = [];

        foreach (array_keys(SystemSettings::DEFAULTS) as $key) {
            if (! str_starts_with($key, 'loan_days_')) {
                continue;
            }

            $type = substr($key, strlen('loan_days_'));
            if (! array_key_exists($type, MemberEligibilityResolver::TYPES)) {
                $orphaned[] = $key;
            }
        }

        $this->assertSame([], $orphaned, 'Kunci lama pinjam tanpa jenis anggota: '.implode(', ', $orphaned));
    }

    /**
     * Data yang ditulis seeder harus lolos validasi aplikasi itu sendiri.
     */
    #[Test]
    public function the_seeder_writes_member_types_the_application_recognises(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, MemberUserSeeder::class]);

        $known = array_keys(MemberEligibilityResolver::TYPES);
        $invalid = Member::query()
            ->whereNotIn('member_type', $known)
            ->pluck('member_type')
            ->unique()
            ->all();

        $this->assertSame([], $invalid, 'Seeder menulis jenis anggota tak dikenal: '.implode(', ', $invalid));
    }

    /**
     * Regresi langsung: dosen hasil seeder harus mendapat 30 hari.
     */
    #[Test]
    public function a_seeded_lecturer_gets_the_lecturer_loan_period(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class, MemberUserSeeder::class]);
        $rules = app(OperationalRules::class);

        $lecturer = Member::query()->where('member_type', 'lecturer')->firstOrFail();

        $this->assertSame(30, $rules->loanPeriodDays($lecturer->member_type));
    }

    #[Test]
    #[DataProvider('memberTypes')]
    public function each_type_has_a_distinct_indonesian_label(string $type, string $expected): void
    {
        $this->assertSame($expected, MemberEligibilityResolver::typeLabel($type));
    }

    public static function memberTypes(): array
    {
        return [
            'mahasiswa' => ['student', 'Mahasiswa'],
            'dosen' => ['lecturer', 'Dosen'],
            'staf' => ['staff', 'Staf'],
            'alumni' => ['alumni', 'Alumni'],
            'tamu' => ['guest', 'Tamu'],
        ];
    }

    /**
     * Formulir menolak jenis anggota di luar daftar — termasuk ejaan Indonesia
     * yang sempat ditulis seeder.
     */
    #[Test]
    public function the_member_form_rejects_a_type_outside_the_list(): void
    {
        $this->actingAsUserWith(['members.create']);

        foreach (['mahasiswa', 'dosen', 'umum'] as $wrongType) {
            $this->post(route('admin.members.store'), [
                'member_number' => 'AGT-UJI-1',
                'member_type' => $wrongType,
                'name' => 'Nama Uji',
            ])->assertSessionHasErrors('member_type');
        }
    }
}

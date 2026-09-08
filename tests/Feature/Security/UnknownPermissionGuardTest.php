<?php

namespace Tests\Feature\Security;

use App\Modules\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Penjaga saat berjalan untuk kelas cacat yang paling sering muncul di aplikasi
 * ini: kode memeriksa izin yang tidak pernah didaftarkan, Gate menjawab false
 * tanpa suara, dan tombol atau cabang policy mati diam-diam.
 *
 * Pembagian tugasnya disengaja: PermissionNamesAreConsistentTest memindai kode
 * secara statis di CI, sedangkan penjaga ini melempar exception di lingkungan
 * `local` — di situlah pengembang mengklik tombol baru dan langsung tahu.
 */
class UnknownPermissionGuardTest extends TestCase
{
    use RefreshDatabase;

    private function asLocalEnvironment(): void
    {
        $this->app['env'] = 'local';
    }

    #[Test]
    public function checking_an_unregistered_permission_raises_in_local(): void
    {
        $this->asLocalEnvironment();
        $user = User::factory()->create();

        try {
            $user->can('katalog.izin_yang_salah_tulis');
            $this->fail('Izin tak terdaftar seharusnya melempar exception di lingkungan local.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('katalog.izin_yang_salah_tulis', $e->getMessage());
            $this->assertStringContainsString('tidak pernah didaftarkan', $e->getMessage());
        }
    }

    #[Test]
    public function checking_a_registered_permission_stays_silent(): void
    {
        $this->asLocalEnvironment();
        Permission::findOrCreate('catalog.view', 'web');
        $this->flushPermissionCache();
        $user = User::factory()->create();

        $this->assertFalse($user->can('catalog.view'));
    }

    #[Test]
    public function a_granted_permission_still_works_normally(): void
    {
        $this->asLocalEnvironment();
        $user = $this->userWith(['catalog.view']);

        $this->assertTrue($user->can('catalog.view'));
    }

    /**
     * Nama metode policy seperti `viewAny` atau `runOcr` juga melewati Gate.
     * Penjaga hanya menyoroti nama bergaya izin (`modul.aksi`), supaya tidak
     * menghalangi otorisasi berbasis policy.
     */
    #[Test]
    public function policy_ability_names_are_not_mistaken_for_permissions(): void
    {
        $this->asLocalEnvironment();
        $user = User::factory()->create();

        foreach (['viewAny', 'runOcr', 'update', 'reactivate'] as $ability) {
            $this->assertFalse($user->can($ability), "Ability {$ability} seharusnya lewat tanpa dijaga.");
        }
    }

    /**
     * Di luar `local` penjaga ini diam sepenuhnya — termasuk di produksi, di
     * mana kegagalan keras lebih merugikan daripada tombol yang tidak muncul.
     */
    #[Test]
    public function the_guard_stays_out_of_the_way_outside_local(): void
    {
        $user = User::factory()->create();

        foreach (['production', 'testing', 'staging'] as $environment) {
            $this->app['env'] = $environment;

            $this->assertFalse(
                $user->can('katalog.izin_yang_salah_tulis'),
                "Penjaga seharusnya tidak aktif di lingkungan {$environment}."
            );
        }
    }

    /**
     * Super Admin lolos lewat Gate::before, dan justru itulah sebabnya cacat
     * ini bertahan lama: orang yang paling mungkin menguji aplikasi tidak
     * pernah melihat akibatnya. Penjaga harus tetap berteriak untuknya.
     */
    #[Test]
    public function even_a_super_admin_triggers_the_guard(): void
    {
        $this->asLocalEnvironment();
        $superAdmin = $this->superAdmin();

        $this->expectException(RuntimeException::class);

        $superAdmin->can('katalog.izin_yang_salah_tulis');
    }
}

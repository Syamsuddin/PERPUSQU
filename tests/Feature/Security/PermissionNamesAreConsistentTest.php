<?php

namespace Tests\Feature\Security;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Penjaga untuk satu kelas cacat yang sudah muncul enam kali di aplikasi ini:
 * kode memeriksa izin dengan nama yang TIDAK PERNAH didaftarkan seeder.
 *
 * Gate menjawab false untuk izin yang tidak ada, tanpa error dan tanpa catatan.
 * Akibatnya cabang policy atau tombol yang bersangkutan mati diam-diam, dan
 * hanya Super Admin — yang lolos lewat Gate::before — yang tidak menyadarinya.
 * Yang sudah terjadi karena ini: pustakawan tidak dapat menyunting katalog,
 * OCR mustahil dijalankan, dan seluruh tombol meja sirkulasi tak terlihat oleh
 * Petugas Sirkulasi.
 *
 * Berkas ini membaca kode yang sesungguhnya, bukan daftar yang ditulis tangan,
 * sehingga salah tulis berikutnya ketahuan tanpa ada yang perlu mengingatnya.
 */
class PermissionNamesAreConsistentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Rujukan yang masih belum sejalan dan menunggu keputusan.
     *
     * Semuanya berada di metode policy yang TIDAK dipanggil controller mana pun
     * (LoanPolicy dan MemberPolicy tidak pernah lewat `authorize()`), sehingga
     * belum berdampak pada pengguna. Memperbaikinya berarti memutuskan siapa
     * yang berhak — misalnya siapa yang boleh menghapus denda atau memaksa
     * pengembalian — dan itu keputusan kebijakan, bukan salah tulis.
     *
     * Daftar ini hanya boleh menyusut. Nama baru yang tidak terdaftar akan
     * menggagalkan test, bukan diam-diam ikut diterima.
     */
    private const KNOWN_UNRESOLVED = [
        'circulation.checkout',
        'circulation.force_return',
        'circulation.manage_fines',
        'circulation.renew',
        'circulation.return',
        'circulation.view',
        'circulation.waive_fines',
        'digital_assets.access_embargoed',
        'digital_assets.update_any',
        'digital_assets.view_all',
        'members.export',
    ];

    /**
     * @return list<string>
     */
    private function registeredPermissions(): array
    {
        $this->seed(PermissionSeeder::class);

        return Permission::query()->pluck('name')->all();
    }

    /**
     * @return array<string, list<string>> nama izin => berkas yang merujuknya
     */
    private function referencesIn(string $directory, string $glob, string $pattern): array
    {
        $references = [];

        $files = new \RegexIterator(
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path($directory))),
            $glob
        );

        foreach ($files as $file) {
            preg_match_all($pattern, file_get_contents($file->getPathname()), $matches);

            foreach ($matches[1] as $permission) {
                $references[$permission][] = str_replace(base_path().'/', '', $file->getPathname());
            }
        }

        ksort($references);

        return $references;
    }

    #[Test]
    public function every_permission_a_policy_checks_is_actually_registered(): void
    {
        $registered = $this->registeredPermissions();
        $references = $this->referencesIn('app/Modules', '/Policies\/.*\.php$/', "/can\('([a-z_]+\.[a-z_]+)'\)/");

        $this->assertNotEmpty($references, 'Tidak ada izin terbaca dari policy — pemindaiannya rusak.');

        $unresolved = [];
        foreach ($references as $permission => $files) {
            if (! in_array($permission, $registered, true) && ! in_array($permission, self::KNOWN_UNRESOLVED, true)) {
                $unresolved[] = "{$permission} (".implode(', ', array_unique($files)).')';
            }
        }

        $this->assertSame(
            [],
            $unresolved,
            "Policy memeriksa izin yang tidak pernah didaftarkan PermissionSeeder:\n- ".implode("\n- ", $unresolved)
                ."\nGate akan menjawab false diam-diam. Daftarkan izinnya, atau perbaiki namanya."
        );
    }

    /**
     * Tombol yang dijaga izin salah nama tidak pernah muncul, dan tidak ada
     * pesan error apa pun yang menjelaskan mengapa.
     */
    #[Test]
    public function every_permission_a_blade_view_checks_is_actually_registered(): void
    {
        $registered = $this->registeredPermissions();
        $references = $this->referencesIn(
            'resources/views',
            '/\.blade\.php$/',
            "/@can(?:any)?\(\s*\[?\s*'([a-z_]+\.[a-z_]+)'/"
        );

        $this->assertNotEmpty($references, 'Tidak ada izin terbaca dari view — pemindaiannya rusak.');

        $unresolved = [];
        foreach ($references as $permission => $files) {
            if (! in_array($permission, $registered, true)) {
                $unresolved[] = "{$permission} (".implode(', ', array_unique($files)).')';
            }
        }

        $this->assertSame(
            [],
            $unresolved,
            "View menjaga elemen dengan izin yang tidak pernah didaftarkan:\n- ".implode("\n- ", $unresolved)
        );
    }

    /**
     * Middleware `permission:` melempar exception untuk izin tak dikenal, jadi
     * salah tulis di sini berakhir sebagai error 500, bukan kegagalan senyap.
     * Tetap diperiksa agar ketahuan sebelum sampai ke pengguna.
     */
    #[Test]
    public function every_permission_guarding_a_route_is_actually_registered(): void
    {
        $registered = $this->registeredPermissions();
        $unresolved = [];

        foreach (Route::getRoutes() as $route) {
            foreach ($route->gatherMiddleware() as $middleware) {
                if (! is_string($middleware) || ! str_starts_with($middleware, 'permission:')) {
                    continue;
                }

                foreach (explode('|', substr($middleware, strlen('permission:'))) as $permission) {
                    if (! in_array($permission, $registered, true)) {
                        $unresolved[] = "{$permission} (route {$route->getName()})";
                    }
                }
            }
        }

        $this->assertSame([], $unresolved, "Route dijaga izin yang tidak terdaftar:\n- ".implode("\n- ", $unresolved));
    }

    /**
     * Nama peran juga pernah salah tulis (`super-admin` alih-alih
     * `Super Admin`), dengan akibat yang sama: cabang yang tak pernah tercapai.
     */
    #[Test]
    public function every_role_name_a_policy_checks_actually_exists(): void
    {
        $this->seed([PermissionSeeder::class, RoleSeeder::class]);
        $roles = Role::query()->pluck('name')->all();

        $references = $this->referencesIn('app/Modules', '/Policies\/.*\.php$/', "/hasRole\('([^']+)'\)/");

        $unresolved = [];
        foreach ($references as $role => $files) {
            if (! in_array($role, $roles, true)) {
                $unresolved[] = "{$role} (".implode(', ', array_unique($files)).')';
            }
        }

        $this->assertSame([], $unresolved, "Policy memeriksa peran yang tidak ada:\n- ".implode("\n- ", $unresolved));
    }
}

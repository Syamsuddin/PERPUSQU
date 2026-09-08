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

    /*
     * Tidak ada daftar pengecualian di sini, dan itu disengaja.
     *
     * Sebelumnya ada sebelas nama yang ditoleransi: tujuh milik LoanPolicy dan
     * MemberPolicy — dua policy yang tidak pernah dipanggil siapa pun dan kini
     * dihapus — serta tiga milik DigitalAssetPolicy yang akhirnya didaftarkan
     * PermissionSeeder. Setelah keduanya beres, tidak tersisa satu pun.
     *
     * Nama izin yang tidak terdaftar harus diperbaiki atau didaftarkan, bukan
     * ditoleransi: yang ditoleransi berarti cabang policy atau tombol yang mati
     * diam-diam tanpa ada yang menyadarinya.
     */

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
            if (! in_array($permission, $registered, true)) {
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

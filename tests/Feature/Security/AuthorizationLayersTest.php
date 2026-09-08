<?php

namespace Tests\Feature\Security;

use App\Providers\AppServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\TestCase;

/**
 * Menjaga pembagian wewenang tetap dua lapis dan tidak saling menduplikasi.
 *
 *  1. Middleware `permission:` pada route menjawab "boleh tidak peran ini
 *     memakai fitur tersebut" — dijaga RouteAccessControlTest.
 *  2. Policy menjawab "boleh tidak pengguna ini bertindak atas RECORD
 *     TERTENTU" — hanya aturan yang bergantung pada isi record.
 *
 * Policy yang isinya sekadar mengulang izin yang sudah diperiksa route adalah
 * duplikasi. Duplikasi itulah yang membuat LoanPolicy, MemberPolicy, dan
 * PhysicalItemPolicy hidup bertahun-tahun tanpa pernah dipanggil siapa pun,
 * sambil menyimpan tujuh nama izin yang tidak pernah didaftarkan.
 */
class AuthorizationLayersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<class-string, class-string>
     */
    private function registeredPolicies(): array
    {
        $property = (new ReflectionClass(AppServiceProvider::class))->getProperty('policies');

        return $property->getValue(new AppServiceProvider($this->app));
    }

    /**
     * @return list<string>
     */
    private function controllerSources(): array
    {
        $sources = [];

        $files = new \RegexIterator(
            new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Modules'))),
            '/Controllers\/.*\.php$/'
        );

        foreach ($files as $file) {
            $sources[] = file_get_contents($file->getPathname());
        }

        return $sources;
    }

    /**
     * Policy yang terdaftar tetapi tidak pernah dipanggil adalah dokumentasi
     * yang berbohong: ia tampak menjaga sesuatu padahal tidak.
     */
    #[Test]
    public function every_registered_policy_is_actually_invoked(): void
    {
        $sources = $this->controllerSources();
        $this->assertNotEmpty($sources, 'Tidak ada controller terbaca — pemindaiannya rusak.');

        $unused = [];

        foreach ($this->registeredPolicies() as $model => $policy) {
            $modelName = class_basename($model);

            $invoked = collect($sources)->contains(
                fn (string $source) => str_contains($source, $modelName)
                    && str_contains($source, '$this->authorize(')
            );

            if (! $invoked) {
                $unused[] = class_basename($policy).' (untuk '.$modelName.')';
            }
        }

        $this->assertSame(
            [],
            $unused,
            "Policy terdaftar tetapi tidak pernah dipanggil controller mana pun:\n- ".implode("\n- ", $unused)
                ."\nHapus policy-nya, atau panggil lewat authorize() di controller yang bersangkutan."
        );
    }

    /**
     * Policy hanya pantas ada bila membawa aturan yang bergantung pada isi
     * record. Yang seluruh metodenya sekadar `return $user->can(...)` tidak
     * menambah apa pun di atas middleware route.
     */
    #[Test]
    public function every_registered_policy_carries_at_least_one_record_level_rule(): void
    {
        $redundant = [];

        foreach ($this->registeredPolicies() as $model => $policy) {
            $source = file_get_contents((new ReflectionClass($policy))->getFileName());

            // Aturan per-record dikenali dari penyebutan variabel record-nya
            // di luar deklarasi parameter — kepemilikan, status, dan sejenisnya.
            $body = preg_replace('/public function \w+\([^)]*\)/', '', $source) ?? '';
            $mentionsRecord = (bool) preg_match('/\$\w+->(?!can\()\w+/', $body);

            if (! $mentionsRecord) {
                $redundant[] = class_basename($policy);
            }
        }

        $this->assertSame(
            [],
            $redundant,
            "Policy yang seluruh aturannya hanya mengulang izin route:\n- ".implode("\n- ", $redundant)
                ."\nWewenang tingkat fitur sudah dijaga middleware `permission:`; policy semacam ini duplikasi."
        );
    }

    /**
     * Aturan per-record milik ketiga policy yang dihapus tidak ikut hilang —
     * semuanya memang sudah ditegakkan layanan masing-masing, dan di sana
     * pesannya sampai ke petugas alih-alih berubah menjadi 403 tanpa penjelasan.
     */
    #[Test]
    public function the_record_rules_of_the_removed_policies_are_still_enforced(): void
    {
        $this->actingAsUserWith([
            'collections.delete', 'collections.view',
            'members.delete', 'members.view',
            'circulation.process_renewal', 'circulation.view_active_loans',
        ]);

        // Item yang sedang dipinjam tidak dapat dihapus.
        $loan = $this->activeLoan();
        $item = $loan->physicalItem;
        $this->from(route('admin.collections.items.show', $item))
            ->delete(route('admin.collections.items.destroy', $item))
            ->assertSessionHas('error');
        $this->assertNotNull($item->fresh());

        // Anggota dengan pinjaman aktif tidak dapat dihapus.
        $member = $loan->member;
        $this->from(route('admin.members.show', $member))
            ->delete(route('admin.members.destroy', $member))
            ->assertSessionHas('error');
        $this->assertNotNull($member->fresh());

        // Pinjaman yang sudah dikembalikan tidak dapat diperpanjang.
        $loan->update(['loan_status' => 'returned', 'returned_at' => now()]);
        $this->from(route('admin.circulation.loans.show', $loan))
            ->post(route('admin.circulation.loans.renew', $loan))
            ->assertSessionHas('error');
        $this->assertSame(0, $loan->renewals()->count());
    }
}

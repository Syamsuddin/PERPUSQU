<?php

namespace Tests\Feature\Security;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Circulation\Models\Fine;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\MasterData\Models\Classification;
use App\Modules\MasterData\Models\ItemCondition;
use App\Modules\MasterData\Models\Language;
use App\Modules\MasterData\Models\Publisher;
use App\Modules\MasterData\Models\RackLocation;
use App\Modules\MasterData\Models\StudyProgram;
use App\Modules\MasterData\Models\Subject;
use App\Modules\Member\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Jaring pengaman lintas modul.
 *
 * Test per modul hanya menjangkau route yang diingat penulisnya. Berkas ini
 * membaca TABEL ROUTE yang sesungguhnya, sehingga route baru yang lupa
 * dipasangi penjaga langsung ketahuan tanpa ada yang perlu menambah test.
 * Setiap pemeriksaan mengumpulkan SELURUH pelanggaran lebih dulu, lalu
 * melaporkannya sekaligus — memperbaiki satu route tidak akan menyingkap
 * pelanggaran berikutnya satu per satu.
 */
class RouteAccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Route admin yang memang sengaja terbuka untuk setiap pengguna yang sudah
     * masuk, tanpa izin khusus.
     */
    private const PERMISSIONLESS_BY_DESIGN = [
        'auth.logout',
        'admin.guides.superadmin',
        'admin.guides.pustakawan',
    ];

    /**
     * @return array<string, RoutingRoute>
     */
    private function adminRoutes(): array
    {
        $routes = [];

        foreach (Route::getRoutes() as $route) {
            /** @var RoutingRoute $route */
            $name = $route->getName();

            if ($name && str_starts_with($route->uri(), 'admin')) {
                $routes[$name] = $route;
            }
        }

        ksort($routes);

        return $routes;
    }

    private function writableMethod(RoutingRoute $route): string
    {
        return collect($route->methods())->first(fn ($m) => $m !== 'HEAD') ?? 'GET';
    }

    /**
     * @param  array<string, int|string>  $ids  peta nama parameter route → id nyata
     */
    private function probeUrl(RoutingRoute $route, array $ids = []): string
    {
        $uri = preg_replace_callback(
            '/\{([^}?]+)\??\}/',
            fn (array $m) => (string) ($ids[$m[1]] ?? 1),
            $route->uri()
        );

        return '/'.$uri;
    }

    #[Test]
    public function the_admin_surface_is_not_empty(): void
    {
        $this->assertGreaterThan(
            50,
            count($this->adminRoutes()),
            'Daftar route admin tampak kosong — pemeriksaan di bawah jadi tidak berarti.'
        );
    }

    /**
     * Halaman admin tanpa middleware `auth` adalah kebocoran langsung.
     */
    #[Test]
    public function every_admin_route_sits_behind_the_auth_middleware(): void
    {
        $unguarded = [];

        foreach ($this->adminRoutes() as $name => $route) {
            if (! in_array('auth', $route->gatherMiddleware(), true)) {
                $unguarded[] = "{$name} ({$route->uri()})";
            }
        }

        $this->assertSame([], $unguarded, "Route admin tanpa middleware `auth`:\n- ".implode("\n- ", $unguarded));
    }

    /**
     * Selain halaman yang memang terbuka untuk semua pengguna terautentikasi,
     * tiap route admin harus menyebut izin yang dibutuhkannya.
     */
    #[Test]
    public function every_admin_route_names_the_permission_it_needs(): void
    {
        $missing = [];

        foreach ($this->adminRoutes() as $name => $route) {
            if (in_array($name, self::PERMISSIONLESS_BY_DESIGN, true)) {
                continue;
            }

            $guarded = collect($route->gatherMiddleware())->contains(
                fn (string $m) => str_starts_with($m, 'permission:')
                    || str_starts_with($m, 'role:')
                    || str_starts_with($m, 'role_or_permission:')
            );

            if (! $guarded) {
                $missing[] = "{$name} ({$route->uri()})";
            }
        }

        $this->assertSame(
            [],
            $missing,
            "Route admin yang hanya dijaga `auth`, tanpa middleware izin:\n- ".implode("\n- ", $missing)
                ."\nTambahkan `permission:...` atau daftarkan di PERMISSIONLESS_BY_DESIGN bila memang disengaja."
        );
    }

    /**
     * Bukan sekadar membaca daftar middleware: benar-benar memanggil setiap
     * route sebagai tamu dan memastikan tak satu pun dilayani.
     */
    #[Test]
    public function a_guest_is_never_served_an_admin_route(): void
    {
        $leaked = [];
        $loginUrl = route('auth.login');

        foreach ($this->adminRoutes() as $name => $route) {
            $method = $this->writableMethod($route);
            $response = $this->call($method, $this->probeUrl($route));
            $status = $response->getStatusCode();

            if ($status !== 302) {
                $leaked[] = "{$name} ({$method} {$route->uri()}) → HTTP {$status}";

                continue;
            }

            if ($response->headers->get('Location') !== $loginUrl) {
                $leaked[] = "{$name} dialihkan ke {$response->headers->get('Location')}, bukan halaman login";
            }
        }

        $this->assertSame([], $leaked, "Route admin yang tidak mengarahkan tamu ke login:\n- ".implode("\n- ", $leaked));
    }

    /**
     * Isi satu baris pada setiap tabel yang menjadi sasaran route-model-binding
     * dan kembalikan peta nama-parameter → id yang benar-benar terbentuk.
     * Id tidak boleh diasumsikan bernilai 1: MySQL tidak mengembalikan
     * AUTO_INCREMENT saat transaksi test dibatalkan. Tanpa sasaran yang nyata,
     * binding menjawab 404 lebih dulu dan gerbang izin tidak pernah teruji.
     */
    private function seedOneRowPerBoundTable(): array
    {
        $record = BibliographicRecord::factory()->withAuthor()->create();
        $item = PhysicalItem::factory()->loaned()->create([
            'bibliographic_record_id' => $record->id,
        ]);
        $member = Member::factory()->create();
        $loan = Loan::factory()->create([
            'member_id' => $member->id,
            'physical_item_id' => $item->id,
        ]);
        $fine = Fine::factory()->create([
            'member_id' => $member->id,
            'loan_id' => $loan->id,
        ]);
        $asset = DigitalAsset::factory()->create([
            'bibliographic_record_id' => $record->id,
        ]);

        $studyProgram = StudyProgram::factory()->create();

        return [
            'record' => $record->id,
            'item' => $item->id,
            'loan' => $loan->id,
            'fine' => $fine->id,
            'member' => $member->id,
            'digital_asset' => $asset->id,
            'user' => auth()->id() ?? 1,
            'role' => Role::findOrCreate('Pustakawan', 'web')->id,
            'permission' => Permission::findOrCreate('catalog.view', 'web')->id,
            'author' => $record->authors->first()->id,
            'collectionType' => $record->collection_type_id,
            'studyProgram' => $studyProgram->id,
            'faculty' => $studyProgram->faculty_id,
            'publisher' => Publisher::factory()->create()->id,
            'language' => Language::factory()->create()->id,
            'classification' => Classification::factory()->create()->id,
            'subject' => Subject::factory()->create()->id,
            'rackLocation' => RackLocation::factory()->create()->id,
            'itemCondition' => ItemCondition::factory()->create()->id,
        ];
    }

    /**
     * Pengguna yang sudah masuk tetapi belum diberi izin apa pun — kasus nyata
     * saat akun baru dibuat sebelum role-nya ditetapkan. Setiap sasaran benar
     * ada, sehingga 403 di sini murni keputusan gerbang izin.
     */
    #[Test]
    public function a_signed_in_user_without_any_permission_is_refused_everywhere(): void
    {
        $this->actingAs($this->userWithoutPermissions());
        $ids = $this->seedOneRowPerBoundTable();

        $served = [];

        foreach ($this->adminRoutes() as $name => $route) {
            if (in_array($name, self::PERMISSIONLESS_BY_DESIGN, true)) {
                continue;
            }

            $method = $this->writableMethod($route);
            $status = $this->call($method, $this->probeUrl($route, $ids))->getStatusCode();

            if ($status !== 403) {
                $served[] = "{$name} ({$method} {$route->uri()}) → HTTP {$status}";
            }
        }

        $this->assertSame([], $served, "Route admin yang dilayani untuk pengguna tanpa izin:\n- ".implode("\n- ", $served));
    }

    /**
     * Halaman publik OPAC harus tetap terbuka — regresi ke arah sebaliknya
     * (OPAC tiba-tiba menuntut login) sama merugikannya.
     */
    #[Test]
    public function the_public_catalogue_stays_open_to_guests(): void
    {
        foreach (['opac.home', 'opac.search', 'opac.about', 'opac.help'] as $name) {
            $this->get(route($name))->assertOk();
        }

        $this->get('/')->assertOk();
    }
}

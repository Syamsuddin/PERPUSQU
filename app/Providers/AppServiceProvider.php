<?php

namespace App\Providers;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Policies\BibliographicRecordPolicy;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Policies\LoanPolicy;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Policies\PhysicalItemPolicy;
use App\Modules\Core\Services\OperationalRules;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Policies\DigitalAssetPolicy;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Policies\MemberPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BibliographicRecord::class => BibliographicRecordPolicy::class,
        PhysicalItem::class => PhysicalItemPolicy::class,
        DigitalAsset::class => DigitalAssetPolicy::class,
        Loan::class => LoanPolicy::class,
        Member::class => MemberPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Model tersebar di app/Modules/*/Models, sehingga resolver factory
        // bawaan Laravel (yang mengasumsikan App\Models) tidak menemukannya.
        // Petakan semua model ke Database\Factories\{Model}Factory yang datar.
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        // Pengaturan dibaca berkali-kali dalam satu permintaan (kelayakan
        // pinjam, jatuh tempo, denda, batas unggah, nama aplikasi di layout).
        // Singleton menjaga tabel `system_settings` cukup dibaca sekali.
        $this->app->singleton(SystemSettings::class);
        $this->app->singleton(OperationalRules::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Setiap cacat yang ditemukan saat menyusun test suite punya bentuk
        // yang sama: sistem gagal tanpa bersuara. `last_login_at` dibuang
        // mass assignment, baris pengaturan yang belum ada gagal tersimpan,
        // izin salah nama menyembunyikan tombol. Mode ketat mengubah kelas
        // cacat itu menjadi exception di detik pertama, bukan keluhan
        // pengguna berbulan-bulan kemudian.
        //
        // Dimatikan di produksi: di sana kegagalan keras lebih merugikan
        // daripada perilaku lama yang, walau salah, sudah berjalan.
        Model::shouldBeStrict(! $this->app->isProduction());

        // Nama dan versi aplikasi berasal dari Aturan Operasional. Disuplai
        // lewat composer, bukan View::share, supaya tabel pengaturan hanya
        // tersentuh ketika salah satu halaman ini benar-benar dirender.
        View::composer(
            ['layouts.admin', 'layouts.opac', 'layouts.auth', 'landing', 'maintenance'],
            function ($view) {
                $settings = app(SystemSettings::class);

                $view->with([
                    'appName' => $settings->appName(),
                    'appVersion' => $settings->appVersion(),
                ]);
            }
        );

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            $this->guardAgainstUnknownPermission($ability);

            return $user->hasRole('Super Admin') ? true : null;
        });

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    /**
     * Berteriak saat kode memeriksa izin yang tidak pernah didaftarkan.
     *
     * Gate menjawab `false` untuk nama izin yang tidak ada — tanpa error,
     * tanpa catatan di log. Cabang policy atau tombol yang bergantung padanya
     * mati diam-diam, dan orang yang paling mungkin menguji aplikasi (Super
     * Admin) tidak akan pernah menyadarinya karena Gate::before meloloskannya
     * lebih dulu. Kelas cacat ini sudah muncul enam kali di aplikasi ini.
     *
     * Hanya melempar di lingkungan `local`: di situlah pengembang mengklik
     * tombol baru dan langsung melihat kesalahannya. Di CI tugas yang sama
     * dijalankan PermissionNamesAreConsistentTest, yang memindai kode secara
     * statis tanpa perlu ada yang membuka halamannya.
     */
    protected function guardAgainstUnknownPermission(string $ability): void
    {
        if (! $this->app->environment('local')) {
            return;
        }

        // Hanya nama bergaya izin (`modul.aksi`); ability lain — nama metode
        // policy seperti `viewAny` atau `runOcr` — bukan urusan pemeriksaan ini.
        if (! preg_match('/^[a-z_]+\.[a-z_]+$/', $ability)) {
            return;
        }

        $known = $this->knownPermissionNames();

        // null berarti tabel izin belum dapat dibaca (migrasi belum jalan);
        // gagal terbuka, jangan menghalangi penyiapan aplikasi.
        if ($known === null || in_array($ability, $known, true)) {
            return;
        }

        throw new RuntimeException(
            "Izin '{$ability}' diperiksa tetapi tidak pernah didaftarkan PermissionSeeder. "
            .'Gate akan menjawab false diam-diam sehingga tombol atau cabang policy ini tidak akan pernah aktif. '
            .'Daftarkan izinnya di PermissionSeeder, atau perbaiki nama yang salah tulis.'
        );
    }

    /**
     * @return list<string>|null null bila tabel izin belum dapat dibaca
     */
    protected function knownPermissionNames(): ?array
    {
        try {
            return $this->knownPermissions ??= app(PermissionRegistrar::class)
                ->getPermissions()
                ->pluck('name')
                ->all();
        } catch (\Throwable) {
            return null;
        }
    }

    /** @var list<string>|null */
    protected ?array $knownPermissions = null;
}

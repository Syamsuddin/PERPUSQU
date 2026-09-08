<?php

namespace App\Providers;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Policies\BibliographicRecordPolicy;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Policies\LoanPolicy;
use App\Modules\Core\Services\OperationalRules;
use App\Modules\Core\Services\SystemSettings;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Collection\Policies\PhysicalItemPolicy;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Policies\DigitalAssetPolicy;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Policies\MemberPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}

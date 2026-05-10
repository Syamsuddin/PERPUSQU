<?php

namespace App\Providers;

use App\Modules\Catalog\Models\BibliographicRecord;
use App\Modules\Catalog\Policies\BibliographicRecordPolicy;
use App\Modules\Circulation\Models\Loan;
use App\Modules\Circulation\Policies\LoanPolicy;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\DigitalRepository\Policies\DigitalAssetPolicy;
use App\Modules\Member\Models\Member;
use App\Modules\Member\Policies\MemberPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Modules\Catalog\Models\BibliographicRecord::class => \App\Modules\Catalog\Policies\BibliographicRecordPolicy::class,
        \App\Modules\Collection\Models\PhysicalItem::class => \App\Modules\Collection\Policies\PhysicalItemPolicy::class,
        \App\Modules\DigitalRepository\Models\DigitalAsset::class => \App\Modules\DigitalRepository\Policies\DigitalAssetPolicy::class,
        \App\Modules\Circulation\Models\Loan::class => \App\Modules\Circulation\Policies\LoanPolicy::class,
        \App\Modules\Member\Models\Member::class => \App\Modules\Member\Policies\MemberPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

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

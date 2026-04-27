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
        BibliographicRecord::class => BibliographicRecordPolicy::class,
        DigitalAsset::class => DigitalAssetPolicy::class,
        Loan::class => LoanPolicy::class,
        Member::class => MemberPolicy::class,
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
        // Register policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}

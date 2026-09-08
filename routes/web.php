<?php

use App\Modules\Circulation\Models\Loan;
use App\Modules\Collection\Models\PhysicalItem;
use App\Modules\Core\Models\InstitutionProfile;
use App\Modules\DigitalRepository\Models\DigitalAsset;
use App\Modules\Identity\Support\HomeRedirect;
use Illuminate\Support\Facades\Route;

// Root: Landing page for guests, dashboard for authenticated users
Route::middleware('catalogue.open')->get('/', function () {
    if (auth()->check()) {
        return redirect()->to(HomeRedirect::urlFor(auth()->user()));
    }

    $profile = InstitutionProfile::first();
    $totalCollections = PhysicalItem::count();
    $totalDigital = DigitalAsset::count();
    $activeLoans = Loan::whereNull('returned_at')->count();

    return view('landing', compact('profile', 'totalCollections', 'totalDigital', 'activeLoans'));
});

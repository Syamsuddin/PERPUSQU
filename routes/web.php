<?php

use Illuminate\Support\Facades\Route;

// Root: Landing page for guests, dashboard for authenticated users
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard.index');
    }
    
    $profile = \App\Modules\Core\Models\InstitutionProfile::first();
    $totalCollections = \App\Modules\Collection\Models\PhysicalItem::count();
    $totalDigital = \App\Modules\DigitalRepository\Models\DigitalAsset::count();
    $activeLoans = \App\Modules\Circulation\Models\Loan::whereNull('returned_at')->count();
    
    return view('landing', compact('profile', 'totalCollections', 'totalDigital', 'activeLoans'));
});

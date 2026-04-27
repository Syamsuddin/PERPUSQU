<?php

use Illuminate\Support\Facades\Route;

// Root: Landing page for guests, dashboard for authenticated users
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard.index');
    }
    return view('landing');
});

<?php

use Illuminate\Support\Facades\Route;

// Root redirect to login or dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard.index');
    }
    return redirect()->route('auth.login');
});

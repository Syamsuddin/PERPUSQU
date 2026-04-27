<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleRouteServiceProvider extends ServiceProvider
{
    /**
     * Register module route files automatically.
     *
     * Each module under app/Modules/{ModuleName}/routes/web.php
     * is auto-loaded here.
     */
    public function boot(): void
    {
        $modulesPath = app_path('Modules');

        if (! is_dir($modulesPath)) {
            return;
        }

        $modules = scandir($modulesPath);

        foreach ($modules as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $routeFile = $modulesPath.'/'.$module.'/routes/web.php';

            if (file_exists($routeFile)) {
                Route::middleware('web')->group($routeFile);
            }
        }
    }
}

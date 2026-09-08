<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;
use Tests\Concerns\ActsAsLibraryUser;
use Tests\Concerns\BuildsLibraryFixtures;

abstract class TestCase extends BaseTestCase
{
    use ActsAsLibraryUser;
    use BuildsLibraryFixtures;

    protected function setUp(): void
    {
        parent::setUp();

        // Spatie meng-cache tabel permission secara global. Tanpa reset, izin
        // yang dibuat di satu test bisa bocor (atau hilang) di test berikutnya.
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

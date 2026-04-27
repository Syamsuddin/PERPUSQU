<?php

namespace App\Modules\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Services\PermissionMatrixService;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionMatrixService $permissionService
    ) {}

    public function index()
    {
        $permissionGroups = $this->permissionService->getPermissionsGroupedByModule();

        return view('modules.identity.permissions.index', compact('permissionGroups'));
    }
}

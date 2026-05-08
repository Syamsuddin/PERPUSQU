<?php

namespace App\Modules\Identity\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Identity\Http\Requests\LoginRequest;
use App\Modules\Identity\Services\AuthenticationService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthenticationService $authService
    ) {}

    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard.index');
        }

        return view('modules.identity.auth.login');
    }

    /**
     * Process login attempt.
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->attemptLogin($request->validated());

        if (! $result['success']) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => $result['message']]);
        }

        return redirect()->intended($result['redirect']);
    }

    /**
     * Process logout.
     */
    public function logout(Request $request)
    {
        $this->authService->logoutCurrentUser();

        return redirect('/')
            ->with('success', 'Anda berhasil logout.');
    }
}

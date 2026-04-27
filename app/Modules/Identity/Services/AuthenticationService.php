<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticationService
{
    /**
     * Attempt login with email/username and password.
     */
    public function attemptLogin(array $credentials): array
    {
        $login = strtolower(trim($credentials['login']));
        $password = $credentials['password'];

        // Create rate limiter key based on login and IP
        $rateLimiterKey = $this->getRateLimiterKey($login);

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            $minutes = ceil($seconds / 60);

            Log::warning('Login rate limit exceeded', [
                'login' => $login,
                'ip' => request()->ip(),
                'seconds_remaining' => $seconds,
            ]);

            return [
                'success' => false,
                'message' => "Terlalu banyak percobaan login. Coba lagi dalam {$minutes} menit.",
            ];
        }

        // Determine if login is email or username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt authentication
        if (! Auth::attempt([$field => $login, 'password' => $password])) {
            // Increment failed attempts
            RateLimiter::hit($rateLimiterKey, 300); // Lock for 5 minutes

            Log::info('Failed login attempt', [
                'login' => $login,
                'field' => $field,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return ['success' => false, 'message' => 'Username/email atau password salah.'];
        }

        $user = Auth::user();

        // Check if account is active
        if (! $user->isActive()) {
            Auth::logout();

            Log::warning('Login attempt on inactive account', [
                'user_id' => $user->id,
                'login' => $login,
                'ip' => request()->ip(),
            ]);

            return ['success' => false, 'message' => 'Akun Anda tidak aktif. Hubungi administrator.'];
        }

        // Clear rate limiter on successful login
        RateLimiter::clear($rateLimiterKey);

        // Update last_login_at
        $user->update(['last_login_at' => now()]);

        // Regenerate session to prevent session fixation
        request()->session()->regenerate();

        // Audit log
        activity('auth')
            ->causedBy($user)
            ->withProperties(['ip' => request()->ip(), 'user_agent' => request()->userAgent()])
            ->log('Login berhasil');

        return ['success' => true, 'user' => $user, 'redirect' => route('admin.dashboard.index')];
    }

    /**
     * Logout the current user.
     */
    public function logoutCurrentUser(): void
    {
        $user = Auth::user();

        if ($user) {
            activity('auth')
                ->causedBy($user)
                ->withProperties(['ip' => request()->ip()])
                ->log('Logout');
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Get rate limiter key for login attempts.
     *
     * Combines login identifier with IP address for more granular rate limiting.
     */
    protected function getRateLimiterKey(string $login): string
    {
        return 'login-attempt:'.sha1($login.'|'.request()->ip());
    }

    /**
     * Get remaining login attempts before lockout.
     */
    public function getRemainingAttempts(string $login): int
    {
        $key = $this->getRateLimiterKey($login);
        $attempts = RateLimiter::attempts($key);

        return max(0, 5 - $attempts);
    }

    /**
     * Check if login is currently locked out.
     */
    public function isLockedOut(string $login): bool
    {
        return RateLimiter::tooManyAttempts($this->getRateLimiterKey($login), 5);
    }

    /**
     * Get seconds until lockout expires.
     */
    public function lockoutSeconds(string $login): int
    {
        return RateLimiter::availableIn($this->getRateLimiterKey($login));
    }
}

<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
    public function getPaginatedUsers(array $filters): LengthAwarePaginator
    {
        return User::with('roles')
            ->keyword($filters['keyword'] ?? null)
            ->when(isset($filters['role_id']), fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('id', $filters['role_id'])))
            ->when(isset($filters['is_active']), fn ($q) => $q->where('is_active', $filters['is_active']))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => strtolower($data['username']),
                'email' => strtolower($data['email']),
                'password' => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (! empty($data['role_ids'])) {
                $user->syncRoles($data['role_ids']);
            }

            activity('user_management')
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties(['roles' => $data['role_ids'] ?? []])
                ->log('User baru dibuat: '.$user->name);

            return $user->load('roles');
        });
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data['name'],
                'username' => strtolower($data['username']),
                'email' => strtolower($data['email']),
                'is_active' => $data['is_active'] ?? $user->is_active,
            ]);

            activity('user_management')
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log('User diperbarui: '.$user->name);

            return $user->load('roles');
        });
    }

    public function activateUser(User $user): User
    {
        $user->update(['is_active' => ! $user->is_active]);

        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log($user->is_active ? 'User diaktifkan' : 'User dinonaktifkan');

        return $user;
    }

    public function resetPassword(User $user, array $data): void
    {
        $user->update(['password' => Hash::make($data['new_password'])]);

        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Password user direset');
    }

    public function deleteUser(User $user): void
    {
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User dihapus: '.$user->name);

        $user->delete();
    }

    public function assignRoles(User $user, array $roleIds): User
    {
        DB::transaction(function () use ($user, $roleIds) {
            $user->syncRoles($roleIds);

            activity('user_management')
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->withProperties(['role_ids' => $roleIds])
                ->log('Role user diperbarui');
        });

        return $user->load('roles');
    }
}

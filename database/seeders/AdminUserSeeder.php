<?php

namespace Database\Seeders;

use App\Modules\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUsers = [
            [
                'name' => 'Admin Perpustakaan',
                'username' => 'admin_perpus',
                'email' => 'admin_perpus@gibthalibrary.local',
                'role' => 'Admin Perpustakaan',
            ],
            [
                'name' => 'Pustakawan',
                'username' => 'pustakawan',
                'email' => 'pustakawan@gibthalibrary.local',
                'role' => 'Pustakawan',
            ],
            [
                'name' => 'Petugas Sirkulasi',
                'username' => 'petugas_sirkulasi',
                'email' => 'petugas_sirkulasi@gibthalibrary.local',
                'role' => 'Petugas Sirkulasi',
            ],
            [
                'name' => 'Operator Repositori Digital',
                'username' => 'operator_digital',
                'email' => 'operator_digital@gibthalibrary.local',
                'role' => 'Operator Repositori Digital',
            ],
            [
                'name' => 'Pimpinan Perpustakaan',
                'username' => 'pimpinan',
                'email' => 'pimpinan@gibthalibrary.local',
                'role' => 'Pimpinan Perpustakaan',
            ],
        ];

        foreach ($adminUsers as $userData) {
            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('Admin@123'),
                    'is_active' => true,
                ]
            );

            $user->assignRole($userData['role']);
        }
    }
}

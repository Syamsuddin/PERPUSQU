<?php

namespace Database\Seeders;

use App\Modules\Identity\Models\User;
use App\Modules\Member\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberUserSeeder extends Seeder
{
    /**
     * Seed sample library member user accounts.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'Ahmad Fauzi',
                'username' => 'ahmad.fauzi',
                'email' => 'ahmad.fauzi@gibthalibrary.local',
                'member_number' => 'AGT-2026-0001',
                'member_type' => 'student',
                'identity_number' => '2026010001',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'username' => 'siti.nurhaliza',
                'email' => 'siti.nurhaliza@gibthalibrary.local',
                'member_number' => 'AGT-2026-0002',
                'member_type' => 'student',
                'identity_number' => '2026010002',
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'username' => 'budi.santoso',
                'email' => 'budi.santoso@gibthalibrary.local',
                'member_number' => 'AGT-2026-0003',
                'member_type' => 'lecturer',
                'identity_number' => '198501152010011001',
            ],
            [
                'name' => 'Rina Wulandari',
                'username' => 'rina.wulandari',
                'email' => 'rina.wulandari@gibthalibrary.local',
                'member_number' => 'AGT-2026-0004',
                'member_type' => 'student',
                'identity_number' => '2026010003',
            ],
            [
                'name' => 'Hendra Pratama',
                'username' => 'hendra.pratama',
                'email' => 'hendra.pratama@gibthalibrary.local',
                'member_number' => 'AGT-2026-0005',
                'member_type' => 'guest',
                'identity_number' => '6301010101900001',
            ],
        ];

        foreach ($members as $data) {
            // Create user account
            $user = User::firstOrCreate(
                ['username' => $data['username']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('Admin@123'),
                    'is_active' => true,
                ]
            );

            $user->assignRole('Anggota Perpustakaan');

            // Create corresponding member record, tertaut ke akunnya. Tautan
            // inilah yang membuat portal "Pinjaman Saya" tahu anggota mana yang
            // sedang masuk; tanpa itu akun dan data anggota hanya berdampingan.
            Member::firstOrCreate(
                ['member_number' => $data['member_number']],
                [
                    'user_id' => $user->id,
                    'name' => $data['name'],
                    'member_type' => $data['member_type'],
                    'identity_number' => $data['identity_number'],
                    'email' => $data['email'],
                    'is_active' => true,
                    'is_blocked' => false,
                ]
            );
        }
    }
}

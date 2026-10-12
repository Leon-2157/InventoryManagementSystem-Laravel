<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
        UserProfile::firstOrCreate(
            ['user_id' => $admin->id],
            ['full_name' => 'Administrator Sistem']
        );

        $staff = User::firstOrCreate(
            ['username' => 'staff'],
            [
                'password_hash' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );
        UserProfile::firstOrCreate(
            ['user_id' => $staff->id],
            ['full_name' => 'Staff Gudang']
        );
    }
}

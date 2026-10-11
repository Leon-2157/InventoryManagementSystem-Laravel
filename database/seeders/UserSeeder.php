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
        $admin = User::create([
            'username' => 'admin',
            'password_hash' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        UserProfile::create(['user_id' => $admin->id, 'full_name' => 'Administrator Sistem']);

        $staff = User::create([
            'username' => 'staff',
            'password_hash' => Hash::make('staff123'),
            'role' => 'staff',
        ]);
        UserProfile::create(['user_id' => $staff->id, 'full_name' => 'Staff Gudang']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $user = User::create([
            'email' => 'admin@tourismplatform.com',
            'password' => Hash::make('Admin@123'),
            'user_type' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        Admin::create([
            'user_id' => $user->id,
            'full_name' => 'Super Administrator',
            'role' => 'super_admin',
            'phone' => '+94771234567',
        ]);

        echo "✅ Admin user created successfully!\n";
        echo "Email: admin@tourismplatform.com\n";
        echo "Password: Admin@123\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'first_name' => 'Admin User',
            'last_name' => 'Sistema',
            'email' => 'admin_user@admin.edu',
            'password' => Hash::make('password'),
            'document' => '1003336545',
            'phone' => '3014173641',
            'role' => 'admin',
            'status' => true,
        ]);

    }
}

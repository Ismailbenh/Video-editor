<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ismail@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('SlXyyzmpmhP'),
                'is_admin' => true, // adjust field name if yours differs
                'email_verified_at' => now(),
            ]
        );
    }
}
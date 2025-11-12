<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        if (function_exists('tenant') && tenant()) {
            return;
        }

        User::updateOrCreate(
            ['email' => 'user@credify.localhost'],
            [
                'name'              => 'Demo User',
                'password'          => bcrypt('password'),
                'role'              => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}

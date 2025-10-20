<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (tenant()) {
            $this->call(AdminUserSeeder::class);
        } else {
            \App\Models\User::updateOrCreate(
                ['email' => 'admin@credify.localhost'],
                [
                    'name' => 'Admin',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                ]
            );
        }
    }
}

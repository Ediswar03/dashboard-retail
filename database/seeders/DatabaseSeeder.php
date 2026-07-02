<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@datalens.pro'],
            [
                'name' => 'Admin Analyst',
                'password' => bcrypt('password123'),
            ]
        );

        $this->call([
            RetailTransactionSeeder::class,
        ]);
    }
}

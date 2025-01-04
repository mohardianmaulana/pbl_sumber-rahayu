<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call your individual seeders here
        $this->call([
            // Panggil seeder lain di sini
            RolePermissionSeeder::class,  // Misalnya, jika Anda juga memiliki seeder untuk roles
            userSeeder::class, // Ganti dengan seeder yang Anda buat
            // Seeder lain bisa ditambahkan di sini
        ]);
    }
}

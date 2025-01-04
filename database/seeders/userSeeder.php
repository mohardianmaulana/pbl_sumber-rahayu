<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Misalnya, jika Anda ingin menambahkan pengguna baru
        $user1 = User::updateOrCreate(
            [
                'email' => 'admin@gmail.com', // Pastikan email unik
            ],
            [
                'name' => 'Ardi',
                'password' => bcrypt('12345678'), // Ganti password sesuai kebutuhan
                'roles_id' => 1, // ID peran yang sesuai
            ]
        );

        $user2 = User::updateOrCreate(
            [
                'email' => 'owner@gmail.com', // Pastikan email unik
            ],
            [
                'name' => 'Deny Ardianto',
                'password' => bcrypt('12345678'), // Ganti password sesuai kebutuhan
                'roles_id' => 2, // ID peran yang sesuai
            ]
        );

        // Memberikan role sesuai dengan roles_id
        if ($user1->roles_id == 1) {
            $user1->assignRole('admin'); // Assign role admin jika roles_id == 1
        }

        if ($user2->roles_id == 2) {
            $user2->assignRole('owner'); // Assign role owner jika roles_id == 2
        }
    }
}

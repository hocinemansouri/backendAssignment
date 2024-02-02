<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        DB::table('users')->insert(
            [ // Example Admin, Moderator and User (password = Qwert123456)
                [
                    'name' => 'Admin',
                    'surname' => 'Administrator',
                    'username' => 'admin',
                    'email' => 'admin@mail.com',
                    'password' => Hash::make('Qwert123456'),
                    'role' => 'admin',
                    'phone' => '+213665132301',
                    'address' => 'ZEO',
                    'city' => 'Biskra',
                    'state' => 'Biskra',
                    'zip_code' => 'DZ-07012'
                ],
                [
                    'name' => 'Moderator',
                    'surname' => 'Moderator',
                    'username' => 'moderator',
                    'email' => 'moderator@mail.com',
                    'password' => Hash::make('Qwert123456'),
                    'role' => 'moderator',
                    'phone' => '',
                    'address' => '',
                    'city' => '',
                    'state' => '',
                    'zip_code' => ''
                ],
                [
                    'name' => 'User',
                    'surname' => 'User',
                    'username' => 'user',
                    'email' => 'user@mail.com',
                    'password' => Hash::make('Qwert123456'),
                    'role' => 'user',
                    'phone' => '',
                    'address' => '',
                    'city' => '',
                    'state' => '',
                    'zip_code' => ''
                ]
            ]
        );
    }
}

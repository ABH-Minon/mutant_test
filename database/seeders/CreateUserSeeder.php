<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'user',
            ],
            [
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'user',
            ],
            [
                'name' => 'user3',
                'email' => 'user3@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'user',
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'admin',
            ],
            [
                'name' => 'admin2',
                'email' => 'admin2@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'admin',
            ],
            [
                'name' => 'admin3',
                'email' => 'admin3@gmail.com',
                'password' => Hash::make('12345678'),
                'cp_number' => '09123456789',
                'address' => 'Address',
                'type' => 'admin',
            ],
        ];
        foreach ($users as $userData) {
            $user = new User($userData);
            $user->save();
        }
    }
}

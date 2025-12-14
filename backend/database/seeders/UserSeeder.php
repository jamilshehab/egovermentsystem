<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $users = [
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'password' => Hash::make('password123'), 'role' => 'admin'],
            ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'password' => Hash::make('password123'), 'role' => 'moderator'],
            ['name' => 'Carol Davis', 'email' => 'carol@example.com', 'password' => Hash::make('password123'), 'role' => 'staff'],
            ['name' => 'David Thompson', 'email' => 'david@example.com', 'password' => Hash::make('password123'), 'role' => 'supervisor'],
            ['name' => 'Eva Martinez', 'email' => 'eva@example.com', 'password' => Hash::make('password123'), 'role' => 'admin'],
            ['name' => 'Frank Wilson', 'email' => 'frank@example.com', 'password' => Hash::make('password123'), 'role' => 'moderator'],
            ['name' => 'Grace Lee', 'email' => 'grace@example.com', 'password' => Hash::make('password123'), 'role' => 'staff'],
            ['name' => 'Henry Clark', 'email' => 'henry@example.com', 'password' => Hash::make('password123'), 'role' => 'supervisor'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

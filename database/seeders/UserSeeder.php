<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    
    public function run() 
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com', 
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
            'role_as' => '1',
        ]); 
        User::create([
            'name' => 'usuario1',
            'email' => 'usuario1@gmail.com', 
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
            'role_as' => '0',
        ]);
        User::create([
            'name' => 'usuario2',
            'email' => 'usuario2@gmail.com', 
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
            'role_as' => '0',
        ]);
        User::create([
            'name' => 'usuario3',
            'email' => 'usuario3@gmail.com', 
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
            'role_as' => '0',
        ]);

    }
}

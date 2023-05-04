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
    }
}

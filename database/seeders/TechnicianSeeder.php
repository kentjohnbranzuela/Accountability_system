<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Technician User',
            'email' => 'tech@blackline.com',
            'password' => Hash::make('tech'),
            'role' => 'technician',
        ]);
    }
}


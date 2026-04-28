<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Officer;
use Illuminate\Support\Facades\Hash;

class OfficerAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officers = Officer::all();
        
        foreach ($officers as $officer) {
            // Generate username from name (lowercase, replace spaces with dots)
            $username = strtolower(str_replace(' ', '.', $officer->name));
            
            // Set a default password (can be changed later)
            $password = Hash::make('password123');
            
            $officer->update([
                'username' => $username,
                'password' => $password,
            ]);
        }
    }
}

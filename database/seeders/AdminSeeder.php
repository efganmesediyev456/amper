<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Admin::where('email','admin@gmail.com')->exists()){
            Admin::create([
                "name"=>"Admin",
                "email"=>"admin@gmail.com",
                "password"=>Hash::make("Az12345678")
            ]);
        }
    }
}

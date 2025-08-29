<?php

namespace Database\Seeders;

use App\Models\ReturnPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!ReturnPolicy::first()){
            ReturnPolicy::create([
            ]);
        }
    }
}

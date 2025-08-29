<?php

namespace Database\Seeders;

use App\Models\OurOnMap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OurOnMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!OurOnMap::first()){
            OurOnMap::create([]);
        }
    }
}

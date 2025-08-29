<?php

namespace Database\Seeders;

use App\Models\VacancyBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VacancyBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!VacancyBanner::first()) {
            VacancyBanner::create([]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\BlogNew;
use App\Models\Product;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vacancies')->delete();
        Vacancy::factory()->count(count: 100)->create();
    }
}

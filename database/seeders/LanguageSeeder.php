<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = ['az','en','ru'];
        foreach ($languages as $language) {
            if(!Language::whereCode($language)->exists()) {
                Language::create([
                    'title'=>$language,
                    'code'=>$language,
                ]);
            }
        }
    }
}

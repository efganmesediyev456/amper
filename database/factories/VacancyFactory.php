<?php

namespace Database\Factories;

use App\Models\BlogNew;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class VacancyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $imageUrl = "https://picsum.photos/800/600";

        $imageContents = Http::get($imageUrl)->body();
        $filename = 'vacancies_' . uniqid() . '.jpg';

        Storage::disk('public')->put('uploads/vacancies/' . $filename, $imageContents);

        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 week');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');

        return [
            'image' => 'uploads/vacancies/' . $filename,
            'vacany_start_at' => $startDate,
            'vacany_expired_at' => $endDate,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Vacancy $product) {
            // Create translations for the product
            $languages = Language::all();

            foreach ($languages as $language) {
                $vacancy_title = $this->faker->words(3, true);
                $vacancy_location = $this->faker->words(3, true);
                $slug = Str::slug($vacancy_title);

                // Create title translation
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'vacancy_title',
                    'value' => $vacancy_title
                ]);

                // Create slug translation
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'slug',
                    'value' => $slug
                ]);
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'vacancy_location',
                    'value' => $vacancy_location
                ]);

                // Create description translation
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'description',
                    'value' => $this->faker->paragraphs(3, true)
                ]);

                // Create SEO meta translations
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'seo_keywords',
                    'value' => implode(', ', $this->faker->words(5))
                ]);

                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'seo_description',
                    'value' => $this->faker->sentence(10)
                ]);
            }

        });
    }
}

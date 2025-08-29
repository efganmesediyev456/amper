<?php

namespace Database\Factories;

use App\Models\BlogNew;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class BlogNewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BlogNew::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $imageUrl = "https://picsum.photos/800/600";

        $imageContents = Http::get($imageUrl)->body();
        $filename = 'blognews_' . uniqid() . '.jpg';

        Storage::disk('public')->put('uploads/blognews/' . $filename, $imageContents);

        return [
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'image' => 'uploads/blognews/' . $filename,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (BlogNew $product) {
            // Create translations for the product
            $languages = Language::all();

            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
                $subtitle = $this->faker->words(3, true);
                $slug = Str::slug($title);

                // Create title translation
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'title',
                    'value' => $title
                ]);

                // Create slug translation
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'slug',
                    'value' => $slug
                ]);
                $product->translations()->create([
                    'locale' => $language->code,
                    'key' => 'subtitle',
                    'value' => $subtitle
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

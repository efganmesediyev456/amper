<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubProperty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Get random related models or their IDs
        $categoryId = Category::inRandomOrder()->first();
        $imageUrl = "https://picsum.photos/800/600";

        $imageContents = Http::get($imageUrl)->body();
        $filename = 'products_' . uniqid() . '.jpg';

        Storage::disk('public')->put('uploads/products/' . $filename, $imageContents);


        return [
            'category_id' => $categoryId,
            'is_new' => $this->faker->randomElement([0, 1]),
            'pick_of_status' => $this->faker->randomElement([0, 1]),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->randomFloat(2, 10, 1000),
            // 'stock_count' => $this->faker->numberBetween(0, 100),
            // 'status' => $this->faker->randomElement([0, 1]),
            // 'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'is_seasonal' => $this->faker->randomElement([0, 1]),
            'is_special_offer' => $this->faker->randomElement([0, 1]),
            'is_bundle' => $this->faker->randomElement([0, 1]),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'image' => 'uploads/products/' . $filename,
            'product_code' => Str::random(12),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            // Create translations for the product
            $languages = Language::all();

            foreach ($languages as $language) {
                $title = $this->faker->words(3, true);
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

            // Create product media (images)
            $mediaCount = $this->faker->numberBetween(0, 5);
            for ($i = 0; $i < $mediaCount; $i++) {

                $imageUrl = "https://picsum.photos/800/600";

                $imageContents = Http::get($imageUrl)->body();
                $filename = 'media_' . uniqid() . '.jpg';

                Storage::disk('public')->put('uploads/media/' . $filename, $imageContents);

                $product->media()->create([
                    'image' => 'uploads/media/' . $filename,
                ]);
            }

            //add properties
            $propertiesCount = $this->faker->numberBetween(0, 3);
            $subProperties = SubProperty::inRandomOrder()->limit($propertiesCount)->get();
            $product->subProperties()->attach($subProperties);
        });
    }

    /**
     * Indicate that the product is featured.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function featured()
    {
        return $this->state(function (array $attributes) {
            return [
                // 'is_featured' => true,
            ];
        });
    }

    /**
     * Indicate that the product is on sale.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function onSale()
    {
        return $this->state(function (array $attributes) {
            $price = $this->faker->randomFloat(2, 50, 1000);
            $discountPrice = $this->faker->randomFloat(2, 10, $price * 0.9);

            return [
                'price' => $price,
            ];
        });
    }

    /**
     * Indicate that the product is out of stock.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function outOfStock()
    {
        return $this->state(function (array $attributes) {
            return [
                'quantity' => 0,
            ];
        });
    }
}

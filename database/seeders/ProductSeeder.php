<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        // Create 50 random products
        DB::table('products')->delete();
        Product::factory()->count( 500)->create();
//
//        // Create 10 featured products
//        Product::factory()->featured()->count(10)->create();
//
//        Product::factory()->onSale()->count(10)->create();
//
//        // Create 5 products that are out of stock
//        Product::factory()->outOfStock()->count(10)->create();
//
//        // Create 5 products that are both featured and on sale
//        Product::factory()->featured()->onSale()->count(10)->create();
    }
}

<?php

namespace Database\Seeders;

use App\Models\DeliveryPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!DeliveryPayment::first()){
            DeliveryPayment::create([]);
        }
    }
}

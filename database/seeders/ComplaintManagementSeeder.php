<?php

namespace Database\Seeders;

use App\Models\ComplaintManagement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ComplaintManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!ComplaintManagement::first()){
            ComplaintManagement::create([]);
        }
    }
}

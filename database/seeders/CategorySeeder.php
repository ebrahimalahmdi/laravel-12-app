<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            'aother work',
            'aother personal',
            'projects',
            'Education',
            'finance',
        ];
        foreach ($categories as $key => $Category) {
            # code...
            Category::create(['name' => $Category]);
        }
    }
    // php artisan db:seed --class=CategorySeeder
    // php artisan db:seed 
}

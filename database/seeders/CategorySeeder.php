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
        $categories = [
            'General',
            'Entertainment',
            'Sports',
            'Movies',
            'Politics',
            'Cars'
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'title' => $categoryName
            ]);
        }
    }
}

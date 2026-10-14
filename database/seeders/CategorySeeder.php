<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Sembako', 'Minuman', 'Makanan Ringan', 'Bumbu Dapur', 'Kebersihan'] as $name) {
            Category::create(['name' => $name]);
        }
    }
}

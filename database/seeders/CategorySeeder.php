<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Peralatan Kantor',
            'Bahan Baku',
            'Suku Cadang',
            'Perlengkapan Gudang',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // [category_id, sku, name, price, stock, minimum_stock]
        $products = [
            [1, 'ELK-001', 'Keyboard Mechanical RGB',      750000.00,  25, 5],
            [1, 'ELK-002', 'Mouse Wireless Ergonomic',     350000.00,  40, 10],
            [1, 'ELK-003', 'Monitor LED 24 Inch',          2500000.00,  8, 3],
            [1, 'ELK-004', 'USB Hub 7 Port',               180000.00,  15, 5],
            [2, 'KNT-001', 'Kertas HVS A4 80gsm',          55000.00,  200, 50],
            [2, 'KNT-002', 'Tinta Printer Hitam',          125000.00,  30, 10],
            [2, 'KNT-003', 'Stapler Heavy Duty',            85000.00,  12, 5],
            [3, 'BHN-001', 'Kabel UTP Cat6 (1 Roll)',      450000.00,  10, 3],
            [3, 'BHN-002', 'Connector RJ45 (100pcs)',       75000.00,  50, 15],
            [3, 'BHN-003', 'Pipa Conduit 20mm',             35000.00,  80, 20],
            [4, 'SKC-001', 'Bearing 6205',                  45000.00, 100, 25],
            [4, 'SKC-002', 'V-Belt A68',                   120000.00,  20, 5],
            [4, 'SKC-003', 'Filter Oli Mesin',              65000.00,  35, 10],
            [5, 'GDG-001', 'Palet Kayu 120x100cm',         250000.00,  15, 5],
            [5, 'GDG-002', 'Stretch Film 50cm',             95000.00,   4, 10],
        ];

        foreach ($products as [$catId, $sku, $name, $price, $stock, $minStock]) {
            Product::create([
                'category_id' => $catId,
                'sku' => $sku,
                'name' => $name,
                'price' => $price,
                'stock' => $stock,
                'minimum_stock' => $minStock,
            ]);
        }

        // Product-supplier relationships with supplier_price
        $pivots = [
            [1, 1, 650000.00], [2, 1, 280000.00], [3, 1, 2200000.00], [4, 1, 150000.00],
            [5, 2, 48000.00],  [6, 2, 105000.00], [7, 2, 70000.00],
            [8, 3, 380000.00], [9, 3, 60000.00],  [10, 3, 28000.00],
            [11, 4, 35000.00], [12, 4, 95000.00], [13, 4, 50000.00],
            [14, 5, 200000.00], [15, 5, 78000.00],
            [1, 4, 670000.00], [8, 4, 400000.00], [11, 3, 38000.00],
        ];

        foreach ($pivots as [$productId, $supplierId, $price]) {
            $product = Product::find($productId);
            // sync without detach to allow multiple suppliers per product
            $product->suppliers()->attach($supplierId, ['supplier_price' => $price]);
        }
    }
}

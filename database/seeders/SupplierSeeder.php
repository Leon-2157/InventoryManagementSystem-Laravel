<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'PT Sinar Elektronik',  'contact' => '021-5551234', 'address' => 'Jl. Mangga Dua Raya No. 45, Jakarta Utara'],
            ['name' => 'CV Maju Bersama',       'contact' => '031-8887654', 'address' => 'Jl. Rungkut Industri III No. 12, Surabaya'],
            ['name' => 'UD Teknik Jaya',        'contact' => '022-7654321', 'address' => 'Jl. Soekarno-Hatta No. 88, Bandung'],
            ['name' => 'PT Global Supply',      'contact' => '024-3456789', 'address' => 'Jl. Kaligawe KM 5, Semarang'],
            ['name' => 'CV Perkasa Material',   'contact' => '061-4567890', 'address' => 'Jl. Gatot Subroto No. 100, Medan'],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }
    }
}

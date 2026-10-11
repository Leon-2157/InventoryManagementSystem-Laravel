<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $movements = [
            [1,  1, 'IN',  30, 'Pembelian awal keyboard',          '2026-09-01 08:00:00'],
            [2,  1, 'IN',  50, 'Pembelian awal mouse',             '2026-09-01 08:30:00'],
            [3,  1, 'IN',  10, 'Pembelian awal monitor',           '2026-09-02 09:00:00'],
            [5,  1, 'IN', 250, 'Restok kertas HVS',                '2026-09-03 10:00:00'],
            [11, 1, 'IN', 120, 'Pembelian bearing batch 1',        '2026-09-05 08:00:00'],
            [1,  2, 'OUT',  5, 'Permintaan divisi IT',             '2026-09-10 13:00:00'],
            [2,  2, 'OUT', 10, 'Permintaan divisi Marketing',      '2026-09-10 14:00:00'],
            [3,  2, 'OUT',  2, 'Setup ruang meeting baru',         '2026-09-12 10:00:00'],
            [5,  2, 'OUT', 50, 'Distribusi ke lantai 2',           '2026-09-15 09:00:00'],
            [11, 2, 'OUT', 20, 'Maintenance mesin produksi',       '2026-09-18 11:00:00'],
            [6,  1, 'IN',  40, 'Restok tinta printer',             '2026-09-20 08:00:00'],
            [6,  2, 'OUT', 10, 'Distribusi ke bagian admin',       '2026-09-22 14:00:00'],
            [14, 1, 'IN',  20, 'Pembelian palet baru',             '2026-09-25 09:00:00'],
            [14, 2, 'OUT',  5, 'Pengiriman ke gudang cabang',      '2026-09-26 10:00:00'],
            [15, 1, 'IN',  10, 'Restok stretch film',              '2026-09-28 08:00:00'],
            [15, 2, 'OUT',  6, 'Packing pengiriman batch 9',       '2026-09-29 14:00:00'],
            [9,  1, 'IN',  60, 'Pembelian connector RJ45',         '2026-10-01 08:00:00'],
            [9,  2, 'OUT', 10, 'Setup jaringan gedung B',          '2026-10-02 10:00:00'],
            [7,  1, 'IN',  15, 'Pembelian stapler',                '2026-10-03 09:00:00'],
            [7,  2, 'OUT',  3, 'Distribusi ke divisi HRD',         '2026-10-04 11:00:00'],
        ];

        foreach ($movements as [$productId, $userId, $type, $qty, $note, $createdAt]) {
            StockMovement::create([
                'product_id' => $productId,
                'user_id' => $userId,
                'type' => $type,
                'quantity' => $qty,
                'note' => $note,
                'created_at' => $createdAt,
            ]);
        }
    }
}

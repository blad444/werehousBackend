<?php

namespace Database\Seeders;

use App\Models\Operation_item;
use App\Models\OperationItem;
use Illuminate\Database\Seeder;

class OperationItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['id' => 1, 'operation_id' => 1, 'product_id' => 1, 'quantity' => 20, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 2, 'operation_id' => 1, 'product_id' => 2, 'quantity' => 15, 'price' => 270.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 3, 'operation_id' => 2, 'product_id' => 5, 'quantity' => 30, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 4, 'operation_id' => 2, 'product_id' => 6, 'quantity' => 25, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 5, 'operation_id' => 3, 'product_id' => 9, 'quantity' => 25, 'price' => 350.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 6, 'operation_id' => 3, 'product_id' => 10, 'quantity' => 30, 'price' => 220.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 7, 'operation_id' => 4, 'product_id' => 13, 'quantity' => 35, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 8, 'operation_id' => 4, 'product_id' => 14, 'quantity' => 25, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 9, 'operation_id' => 5, 'product_id' => 17, 'quantity' => 40, 'price' => 190.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 10, 'operation_id' => 5, 'product_id' => 18, 'quantity' => 30, 'price' => 210.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 11, 'operation_id' => 6, 'product_id' => 21, 'quantity' => 50, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 12, 'operation_id' => 6, 'product_id' => 22, 'quantity' => 40, 'price' => 220.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 13, 'operation_id' => 7, 'product_id' => 3, 'quantity' => 45, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 14, 'operation_id' => 7, 'product_id' => 4, 'quantity' => 35, 'price' => 1200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 15, 'operation_id' => 8, 'product_id' => 7, 'quantity' => 20, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 16, 'operation_id' => 8, 'product_id' => 8, 'quantity' => 25, 'price' => 150.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 17, 'operation_id' => 9, 'product_id' => 11, 'quantity' => 40, 'price' => 170.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 18, 'operation_id' => 9, 'product_id' => 12, 'quantity' => 35, 'price' => 890.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 19, 'operation_id' => 10, 'product_id' => 1, 'quantity' => 30, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 20, 'operation_id' => 10, 'product_id' => 5, 'quantity' => 40, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 21, 'operation_id' => 11, 'product_id' => 9, 'quantity' => 25, 'price' => 350.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 22, 'operation_id' => 11, 'product_id' => 13, 'quantity' => 30, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 23, 'operation_id' => 12, 'product_id' => 17, 'quantity' => 45, 'price' => 190.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 24, 'operation_id' => 12, 'product_id' => 21, 'quantity' => 35, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 25, 'operation_id' => 13, 'product_id' => 3, 'quantity' => 40, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 26, 'operation_id' => 13, 'product_id' => 7, 'quantity' => 30, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 27, 'operation_id' => 14, 'product_id' => 11, 'quantity' => 35, 'price' => 170.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 28, 'operation_id' => 14, 'product_id' => 2, 'quantity' => 25, 'price' => 270.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 29, 'operation_id' => 15, 'product_id' => 6, 'quantity' => 50, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 30, 'operation_id' => 15, 'product_id' => 10, 'quantity' => 40, 'price' => 220.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 31, 'operation_id' => 16, 'product_id' => 14, 'quantity' => 35, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 32, 'operation_id' => 16, 'product_id' => 18, 'quantity' => 30, 'price' => 210.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 33, 'operation_id' => 17, 'product_id' => 22, 'quantity' => 30, 'price' => 220.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 34, 'operation_id' => 17, 'product_id' => 24, 'quantity' => 25, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 35, 'operation_id' => 18, 'product_id' => 20, 'quantity' => 40, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 36, 'operation_id' => 18, 'product_id' => 16, 'quantity' => 35, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 37, 'operation_id' => 19, 'product_id' => 1, 'quantity' => 25, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 38, 'operation_id' => 19, 'product_id' => 5, 'quantity' => 20, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 39, 'operation_id' => 20, 'product_id' => 9, 'quantity' => 15, 'price' => 350.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 40, 'operation_id' => 20, 'product_id' => 13, 'quantity' => 20, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 41, 'operation_id' => 21, 'product_id' => 1, 'quantity' => 30, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 42, 'operation_id' => 21, 'product_id' => 5, 'quantity' => 25, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 43, 'operation_id' => 22, 'product_id' => 9, 'quantity' => 20, 'price' => 350.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 44, 'operation_id' => 22, 'product_id' => 13, 'quantity' => 15, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 45, 'operation_id' => 23, 'product_id' => 17, 'quantity' => 40, 'price' => 190.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 46, 'operation_id' => 23, 'product_id' => 21, 'quantity' => 30, 'price' => 250.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 47, 'operation_id' => 24, 'product_id' => 3, 'quantity' => 25, 'price' => 230.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 48, 'operation_id' => 24, 'product_id' => 7, 'quantity' => 20, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 49, 'operation_id' => 25, 'product_id' => 11, 'quantity' => 45, 'price' => 170.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 50, 'operation_id' => 25, 'product_id' => 15, 'quantity' => 35, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 51, 'operation_id' => 26, 'product_id' => 19, 'quantity' => 30, 'price' => 280.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 52, 'operation_id' => 26, 'product_id' => 23, 'quantity' => 25, 'price' => 200.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 53, 'operation_id' => 27, 'product_id' => 2, 'quantity' => 35, 'price' => 270.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
            ['id' => 54, 'operation_id' => 27, 'product_id' => 6, 'quantity' => 30, 'price' => 180.00, 'created_at' => '2026-06-04 11:44:12', 'updated_at' => '2026-06-04 11:44:12'],
        ];

        foreach ($items as $item) {
            Operation_item::create($item);
        }
    }
}
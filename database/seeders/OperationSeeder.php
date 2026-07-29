<?php

namespace Database\Seeders;

use App\Models\Operation;
use Illuminate\Database\Seeder;

class OperationSeeder extends Seeder
{
    public function run(): void
    {
        $operations = [
            ['id' => 1, 'user_id' => 8, 'manager_id' => 3, 'supplier_id' => 1, 'price' => 15000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-01-05 05:30:00', 'updated_at' => '2026-01-05 05:30:00'],
            ['id' => 2, 'user_id' => 9, 'manager_id' => 3, 'supplier_id' => null, 'price' => 8500.00, 'type' => 'Расход', 'status' => 'Выполнен', 'created_at' => '2026-01-12 09:15:00', 'updated_at' => '2026-01-12 09:15:00'],
            ['id' => 3, 'user_id' => 10, 'manager_id' => 4, 'supplier_id' => 2, 'price' => 22000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-01-20 04:00:00', 'updated_at' => '2026-01-20 04:00:00'],
            ['id' => 4, 'user_id' => 8, 'manager_id' => 4, 'supplier_id' => 3, 'price' => 18500.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-02-03 06:45:00', 'updated_at' => '2026-02-03 06:45:00'],
            ['id' => 5, 'user_id' => 11, 'manager_id' => 5, 'supplier_id' => null, 'price' => 12000.00, 'type' => 'Расход', 'status' => 'Выполнен', 'created_at' => '2026-02-10 11:20:00', 'updated_at' => '2026-02-10 11:20:00'],
            ['id' => 6, 'user_id' => 9, 'manager_id' => 3, 'supplier_id' => 1, 'price' => 25000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-02-18 05:00:00', 'updated_at' => '2026-02-18 05:00:00'],
            ['id' => 7, 'user_id' => 12, 'manager_id' => 5, 'supplier_id' => 4, 'price' => 19500.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-03-02 08:30:00', 'updated_at' => '2026-03-02 08:30:00'],
            ['id' => 8, 'user_id' => 10, 'manager_id' => 6, 'supplier_id' => null, 'price' => 9500.00, 'type' => 'Расход', 'status' => 'Выполнен', 'created_at' => '2026-03-08 10:45:00', 'updated_at' => '2026-03-08 10:45:00'],
            ['id' => 9, 'user_id' => 13, 'manager_id' => 4, 'supplier_id' => 2, 'price' => 28000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-03-15 04:15:00', 'updated_at' => '2026-03-15 04:15:00'],
            ['id' => 10, 'user_id' => 8, 'manager_id' => 7, 'supplier_id' => 3, 'price' => 21000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-04-01 05:30:00', 'updated_at' => '2026-04-01 05:30:00'],
            ['id' => 11, 'user_id' => 14, 'manager_id' => 5, 'supplier_id' => null, 'price' => 11000.00, 'type' => 'Расход', 'status' => 'Выполнен', 'created_at' => '2026-04-07 09:00:00', 'updated_at' => '2026-04-07 09:00:00'],
            ['id' => 12, 'user_id' => 11, 'manager_id' => 3, 'supplier_id' => 1, 'price' => 24500.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-04-14 06:20:00', 'updated_at' => '2026-04-14 06:20:00'],
            ['id' => 13, 'user_id' => 9, 'manager_id' => 6, 'supplier_id' => 4, 'price' => 17500.00, 'type' => 'Приход', 'status' => 'В процессе', 'created_at' => '2026-05-05 04:45:00', 'updated_at' => '2026-05-05 04:45:00'],
            ['id' => 14, 'user_id' => 15, 'manager_id' => 4, 'supplier_id' => null, 'price' => 13500.00, 'type' => 'Расход', 'status' => 'В процессе', 'created_at' => '2026-05-10 11:30:00', 'updated_at' => '2026-05-10 11:30:00'],
            ['id' => 15, 'user_id' => 12, 'manager_id' => 7, 'supplier_id' => 2, 'price' => 26000.00, 'type' => 'Приход', 'status' => 'Новый', 'created_at' => '2026-05-15 05:00:00', 'updated_at' => '2026-05-15 05:00:00'],
            ['id' => 16, 'user_id' => 10, 'manager_id' => null, 'supplier_id' => 3, 'price' => 19000.00, 'type' => 'Приход', 'status' => 'Новый', 'created_at' => '2026-05-18 08:15:00', 'updated_at' => '2026-05-18 08:15:00'],
            ['id' => 17, 'user_id' => 13, 'manager_id' => null, 'supplier_id' => null, 'price' => 10500.00, 'type' => 'Расход', 'status' => 'Новый', 'created_at' => '2026-05-20 10:45:00', 'updated_at' => '2026-05-20 10:45:00'],
            ['id' => 18, 'user_id' => 8, 'manager_id' => null, 'supplier_id' => 1, 'price' => 23000.00, 'type' => 'Приход', 'status' => 'Новый', 'created_at' => '2026-05-22 06:30:00', 'updated_at' => '2026-05-22 06:30:00'],
            ['id' => 19, 'user_id' => 14, 'manager_id' => 5, 'supplier_id' => 4, 'price' => 15000.00, 'type' => 'Приход', 'status' => 'Отменен', 'created_at' => '2026-04-25 05:00:00', 'updated_at' => '2026-04-26 04:00:00'],
            ['id' => 20, 'user_id' => 11, 'manager_id' => 4, 'supplier_id' => null, 'price' => 8000.00, 'type' => 'Расход', 'status' => 'Отменен', 'created_at' => '2026-03-20 09:30:00', 'updated_at' => '2026-03-21 05:00:00'],
            ['id' => 21, 'user_id' => 8, 'manager_id' => 3, 'supplier_id' => 1, 'price' => 18000.00, 'type' => 'Приход', 'status' => 'Выполнен', 'created_at' => '2026-06-01 04:00:00', 'updated_at' => '2026-06-01 04:00:00'],
            ['id' => 22, 'user_id' => 10, 'manager_id' => 4, 'supplier_id' => null, 'price' => 12500.00, 'type' => 'Расход', 'status' => 'Выполнен', 'created_at' => '2026-06-02 09:20:00', 'updated_at' => '2026-06-02 09:20:00'],
            ['id' => 23, 'user_id' => 12, 'manager_id' => 5, 'supplier_id' => 2, 'price' => 22000.00, 'type' => 'Приход', 'status' => 'В процессе', 'created_at' => '2026-06-03 06:15:00', 'updated_at' => '2026-06-03 06:15:00'],
            ['id' => 24, 'user_id' => 9, 'manager_id' => 6, 'supplier_id' => 3, 'price' => 9800.00, 'type' => 'Расход', 'status' => 'В процессе', 'created_at' => '2026-06-04 11:45:00', 'updated_at' => '2026-06-04 11:45:00'],
            ['id' => 25, 'user_id' => 13, 'manager_id' => null, 'supplier_id' => 1, 'price' => 25000.00, 'type' => 'Приход', 'status' => 'Новый', 'created_at' => '2026-06-05 03:30:00', 'updated_at' => '2026-06-05 03:30:00'],
            ['id' => 26, 'user_id' => 11, 'manager_id' => null, 'supplier_id' => null, 'price' => 11000.00, 'type' => 'Расход', 'status' => 'Новый', 'created_at' => '2026-06-05 05:00:00', 'updated_at' => '2026-06-05 05:00:00'],
            ['id' => 27, 'user_id' => 15, 'manager_id' => null, 'supplier_id' => 4, 'price' => 19500.00, 'type' => 'Приход', 'status' => 'Новый', 'created_at' => '2026-06-05 08:20:00', 'updated_at' => '2026-06-05 08:20:00'],
        ];

        foreach ($operations as $operation) {
            Operation::create($operation);
        }
    }
}
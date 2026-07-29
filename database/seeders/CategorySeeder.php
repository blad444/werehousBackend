<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'name' => 'Моти и Дайфуку'],
            ['id' => 2, 'name' => 'Покки и Печенье'],
            ['id' => 3, 'name' => 'Шоколад и Конфеты'],
            ['id' => 4, 'name' => 'Чипсы и Снеки'],
            ['id' => 5, 'name' => 'Желе и Мармелад'],
            ['id' => 6, 'name' => 'Лапша быстрого приготовления'],
            ['id' => 7, 'name' => 'Напитки'],
            ['id' => 8, 'name' => 'Соусы и Приправы'],
            ['id' => 9, 'name' => 'Корейские снеки'],
            ['id' => 10, 'name' => 'Китайские сладости'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
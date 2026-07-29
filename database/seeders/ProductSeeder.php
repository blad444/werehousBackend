<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['id' => 1, 'category_id' => 1, 'title' => 'Моти Клубника', 'description' => 'Японские рисовые пирожные с начинкой из клубники', 'price' => 250.00, 'photo' => 'img/moti.jpg', 'quantity' => 50, 'availability' => 'В наличии'],
            ['id' => 2, 'category_id' => 1, 'title' => 'Моти Манго', 'description' => 'Нежные моти с тропической начинкой из манго', 'price' => 270.00, 'photo' => 'img/motiMango.jpg', 'quantity' => 45, 'availability' => 'В наличии'],
            ['id' => 3, 'category_id' => 1, 'title' => 'Дайфуку Красная фасоль', 'description' => 'Традиционные дайфуку с пастой из красной фасоли', 'price' => 230.00, 'photo' => 'img/fasol.jpg', 'quantity' => 40, 'availability' => 'В наличии'],
            ['id' => 4, 'category_id' => 1, 'title' => 'Моти Ассорти', 'description' => 'Набор моти с разными вкусами (6 шт)', 'price' => 1200.00, 'photo' => 'img/moti.jpg', 'quantity' => 25, 'availability' => 'В наличии'],
            ['id' => 5, 'category_id' => 2, 'title' => 'Pocky Шоколад', 'description' => 'Классические шоколадные палочки Pocky', 'price' => 180.00, 'photo' => 'img/pockySH.jpg', 'quantity' => 100, 'availability' => 'В наличии'],
            ['id' => 6, 'category_id' => 2, 'title' => 'Pocky Клубника', 'description' => 'Палочки Pocky с клубничным покрытием', 'price' => 180.00, 'photo' => 'img/pockyST.jpg', 'quantity' => 80, 'availability' => 'В наличии'],
            ['id' => 7, 'category_id' => 2, 'title' => 'Pocky Матча', 'description' => 'Японский зелёный чай маття в палочках Pocky', 'price' => 200.00, 'photo' => 'img/pockyMA.jpg', 'quantity' => 60, 'availability' => 'В наличии'],
            ['id' => 8, 'category_id' => 2, 'title' => 'Hello Panda Шоколад', 'description' => 'Печенье с пандой и шоколадной начинкой', 'price' => 150.00, 'photo' => 'img/panda.jpg', 'quantity' => 90, 'availability' => 'В наличии'],
            ['id' => 9, 'category_id' => 3, 'title' => 'KitKat Матча', 'description' => 'Японский KitKat со вкусом зелёного чая', 'price' => 350.00, 'photo' => 'img/kitkat.jpg', 'quantity' => 70, 'availability' => 'В наличии'],
            ['id' => 10, 'category_id' => 3, 'title' => 'Meiji Chocolate', 'description' => 'Японский молочный шоколад Meiji', 'price' => 220.00, 'photo' => 'img/meiji.jpg', 'quantity' => 85, 'availability' => 'В наличии'],
            ['id' => 11, 'category_id' => 3, 'title' => 'Glico Pretz', 'description' => 'Солёные палочки с разными вкусами', 'price' => 170.00, 'photo' => 'img/glico.jpg', 'quantity' => 75, 'availability' => 'В наличии'],
            ['id' => 12, 'category_id' => 3, 'title' => 'Royce Nama Chocolate', 'description' => 'Премиальный шоколад Royce из Хоккайдо', 'price' => 890.00, 'photo' => 'img/royce.jpg', 'quantity' => 30, 'availability' => 'В наличии'],
            ['id' => 13, 'category_id' => 4, 'title' => 'Calbee Potato Chips', 'description' => 'Японские картофельные чипсы Calbee', 'price' => 250.00, 'photo' => 'img/calbee.jpg', 'quantity' => 65, 'availability' => 'В наличии'],
            ['id' => 14, 'category_id' => 4, 'title' => 'Jagabee Картофельные стики', 'description' => 'Хрустящие картофельные стики с солью', 'price' => 230.00, 'photo' => 'img/jagabee.jpg', 'quantity' => 55, 'availability' => 'В наличии'],
            ['id' => 15, 'category_id' => 4, 'title' => 'Karamucho Hot Chili', 'description' => 'Острые чипсы Karamucho', 'price' => 200.00, 'photo' => 'img/karamucho.jpg', 'quantity' => 70, 'availability' => 'В наличии'],
            ['id' => 16, 'category_id' => 4, 'title' => 'Wasabi Peas', 'description' => 'Горошек в васаби хрустящий', 'price' => 180.00, 'photo' => 'img/wasabi.jpg', 'quantity' => 80, 'availability' => 'В наличии'],
            ['id' => 17, 'category_id' => 5, 'title' => 'Kasugai Gummy Candy', 'description' => 'Японские жевательные конфеты с фруктовыми вкусами', 'price' => 190.00, 'photo' => 'img/kasugai.jpg', 'quantity' => 90, 'availability' => 'В наличии'],
            ['id' => 18, 'category_id' => 5, 'title' => 'Nobel Kororo Grape', 'description' => 'Желе с виноградом и виноградным соком', 'price' => 210.00, 'photo' => 'img/Nobel.jpg', 'quantity' => 60, 'availability' => 'В наличии'],
            ['id' => 19, 'category_id' => 5, 'title' => 'Orihiro Konnyaku Jelly', 'description' => 'Низкокалорийное желе конняку (ассорти)', 'price' => 280.00, 'photo' => 'img/Orihiro.jpg', 'quantity' => 50, 'availability' => 'В наличии'],
            ['id' => 20, 'category_id' => 5, 'title' => 'UHA Mikakuto Kororo', 'description' => 'Жевательный мармелад с натуральным соком', 'price' => 200.00, 'photo' => 'img/UHA.jpg', 'quantity' => 75, 'availability' => 'В наличии'],
            ['id' => 21, 'category_id' => 7, 'title' => 'Ramune Original', 'description' => 'Японский газированный напиток с шариком', 'price' => 250.00, 'photo' => 'img/soda.jpg', 'quantity' => 80, 'availability' => 'В наличии'],
            ['id' => 22, 'category_id' => 7, 'title' => 'Calpis Water', 'description' => 'Напиток на основе кальписа', 'price' => 220.00, 'photo' => 'img/soda.jpg', 'quantity' => 75, 'availability' => 'В наличии'],
            ['id' => 23, 'category_id' => 7, 'title' => 'Sangaria Matcha Tea', 'description' => 'Зелёный чай маття в бутылке', 'price' => 200.00, 'photo' => 'img/soda.jpg', 'quantity' => 85, 'availability' => 'В наличии'],
            ['id' => 24, 'category_id' => 7, 'title' => 'Pocari Sweat', 'description' => 'Изотонический напиток', 'price' => 230.00, 'photo' => 'img/soda.jpg', 'quantity' => 95, 'availability' => 'В наличии'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
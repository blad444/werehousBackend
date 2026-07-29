<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['id' => 1, 'name' => 'АзияТрейд', 'contact_person' => 'Виктор Ли', 'phone' => '+7 (999) 111-11-01', 'email' => 'info@asiatrade.ru', 'address' => 'г. Москва, ул. Азиатская, 15, офис 301', 'is_active' => 1],
            ['id' => 2, 'name' => 'ВостокИмпорт', 'contact_person' => 'Анна Ким', 'phone' => '+7 (999) 222-22-02', 'email' => 'sales@vostokimport.ru', 'address' => 'г. Санкт-Петербург, Невский пр., 88, стр. 2', 'is_active' => 1],
            ['id' => 3, 'name' => 'Korea Food Distribution', 'contact_person' => 'Пак Чон Хо', 'phone' => '+7 (999) 333-33-03', 'email' => 'contact@koreafood.ru', 'address' => 'г. Владивосток, ул. Портовая, 42', 'is_active' => 1],
            ['id' => 4, 'name' => 'ChinaSweet Wholesale', 'contact_person' => 'Ван Мин', 'phone' => '+7 (999) 444-44-04', 'email' => 'wholesale@chinasweet.ru', 'address' => 'г. Новосибирск, ул. Торговая, 7, склад 5', 'is_active' => 1],
            ['id' => 5, 'name' => 'JapanSnack Official', 'contact_person' => 'Танака Юки', 'phone' => '+7 (999) 555-55-05', 'email' => 'official@japansnack.ru', 'address' => 'г. Москва, ул. Сакура, 3, этаж 4', 'is_active' => 1],
            ['id' => 6, 'name' => 'Тихий Океан ЛТД', 'contact_person' => 'Игорь Белов', 'phone' => '+7 (999) 666-66-06', 'email' => 'info@pacific-ltd.ru', 'address' => 'г. Хабаровск, ул. Амурская, 19', 'is_active' => 0],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
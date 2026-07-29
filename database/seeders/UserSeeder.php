<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['id' => 1, 'full_name' => 'Александр Иванов', 'phone' => '+7 (999) 000-00-01', 'role' => 'admin', 'email' => 'admin@admin.admin', 'password' => '$2y$12$ZTbOL1s5d0VNXLNJJZyIT.trNoKotudUSFugUU4bBmcV0OKLARGGy'],
            ['id' => 2, 'full_name' => 'Дмитрий Петров', 'phone' => '+7 (999) 000-00-02', 'role' => 'admin', 'email' => 'admin2@admin.admin', 'password' => '$2y$12$Tlpw1suzlbbSD3SOdJ4ngu4S8BssW6YHEYT5jLxgzHSz4i3kCNSUi'],
            ['id' => 3, 'full_name' => 'Мария Сидорова', 'phone' => '+7 (999) 000-00-03', 'role' => 'manager', 'email' => 'manager@manager.manager', 'password' => '$2y$12$wZnoiCAh7DP0.NqxaVGm5ej.okKuzeaOW9VCiwTUplm.Bvr.UGRg6'],
            ['id' => 4, 'full_name' => 'Елена Козлова', 'phone' => '+7 (999) 000-00-04', 'role' => 'manager', 'email' => 'manager2@manager.manager', 'password' => '$2y$12$F8eY0fMSYZM9rc7FuOHboeAEVw17ObvE0zduY.3S5AwrJnXOvfQYO'],
            ['id' => 5, 'full_name' => 'Анна Новикова', 'phone' => '+7 (999) 000-00-05', 'role' => 'manager', 'email' => 'manager3@manager.manager', 'password' => '$2y$12$kkwdwzTdgZZaNHqcvKVLVu3Nhrcsp7jpZtciOFep3rV33K4ZBpaUK'],
            ['id' => 6, 'full_name' => 'Ольга Морозова', 'phone' => '+7 (999) 000-00-06', 'role' => 'manager', 'email' => 'manager4@manager.manager', 'password' => '$2y$12$m4V812edG0JsAA6H4llwn.AIqxqCGeq0EH6b/drx1w2gDmc96Y7W.'],
            ['id' => 7, 'full_name' => 'Татьяна Волкова', 'phone' => '+7 (999) 000-00-07', 'role' => 'manager', 'email' => 'manager5@manager.manager', 'password' => '$2y$12$mup9FztXxwV/.OueCHahAensx4TzcM3reFmOQIB9qgHp3z6PGacOm'],
            ['id' => 8, 'full_name' => 'Иван Смирнов', 'phone' => '+7 (999) 000-00-08', 'role' => 'kladovsik', 'email' => 'kladovsik@kladovsik.kladovsik', 'password' => '$2y$12$KAo/D1kjAIGCuyCIH2QaF.891j/Wv5GQd0HrzlRxM9zoVkRsfo1Bi'],
            ['id' => 9, 'full_name' => 'Павел Кузнецов', 'phone' => '+7 (999) 000-00-09', 'role' => 'kladovsik', 'email' => 'kladovsik2@warehouse.ru', 'password' => '$2y$12$gtUtq1BRbOhJVs3B3cNHiOynigSviIG2Hz0gubbd5X.I.SLRQIQUi'],
            ['id' => 10, 'full_name' => 'Сергей Попов', 'phone' => '+7 (999) 000-00-10', 'role' => 'kladovsik', 'email' => 'kladovsik3@warehouse.ru', 'password' => '$2y$12$.YFYURku3u1hjpT6mUj9r.yMWuj4CdH2yc0ptG2b5gt/8LjS3HYnK'],
            ['id' => 11, 'full_name' => 'Андрей Соколов', 'phone' => '+7 (999) 000-00-11', 'role' => 'kladovsik', 'email' => 'kladovsik4@warehouse.ru', 'password' => '$2y$12$xDhIQb6PGKnFHJ8Bi9NVbeFp1cEU7oCnU7VxNNL3K4bURoD7KL862'],
            ['id' => 12, 'full_name' => 'Михаил Лебедев', 'phone' => '+7 (999) 000-00-12', 'role' => 'kladovsik', 'email' => 'kladovsik5@warehouse.ru', 'password' => '$2y$12$o1fxZZj41rL02qViJ39IBu8hi7NXXsL0jqD.ngLIzo9In2LS1XLRm'],
            ['id' => 13, 'full_name' => 'Максим Козлов', 'phone' => '+7 (999) 000-00-13', 'role' => 'kladovsik', 'email' => 'kladovsik6@warehouse.ru', 'password' => '$2y$12$kqyVeXStMRjsBU/LjumveOxB3tTIkYH76SsAG1nctqLGZb5jJC40G'],
            ['id' => 14, 'full_name' => 'Артём Новиков', 'phone' => '+7 (999) 000-00-14', 'role' => 'kladovsik', 'email' => 'kladovsik7@warehouse.ru', 'password' => '$2y$12$VsOHKTgSikkXFR8h0OLuIuc9czSolMF4uyMERK3r2OYc8UkfMDXq.'],
            ['id' => 15, 'full_name' => 'Денис Морозов', 'phone' => '+7 (999) 000-00-15', 'role' => 'kladovsik', 'email' => 'kladovsik8@warehouse.ru', 'password' => '$2y$12$Brkmq5OHIK90YQ4TytwchOIpsXuB6M4Xr3WoQSbWgkwAB67MhOUZK'],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

/**
 * Сидер для дерева видов деятельности (ограничение — 3 уровня).
 */
class ActivitySeeder extends Seeder
{
    /**
     * Заполнение таблицы activities.
     */
    public function run(): void
    {
        $food = Activity::create([
            'name' => 'Еда',
            'description' => 'Продукты питания',
            'parent_id' => null,
            'level' => 1,
        ]);

        $cars = Activity::create([
            'name' => 'Автомобили',
            'description' => 'Автомобильная тематика',
            'parent_id' => null,
            'level' => 1,
        ]);

        $meat = Activity::create([
            'name' => 'Мясная продукция',
            'description' => 'Мясо и полуфабрикаты',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        $dairy = Activity::create([
            'name' => 'Молочная продукция',
            'description' => 'Молоко, сыры, кисломолочная продукция',
            'parent_id' => $food->id,
            'level' => 2,
        ]);

        $trucks = Activity::create([
            'name' => 'Грузовые',
            'description' => 'Грузовые автомобили',
            'parent_id' => $cars->id,
            'level' => 2,
        ]);

        $passenger = Activity::create([
            'name' => 'Легковые',
            'description' => 'Легковые автомобили',
            'parent_id' => $cars->id,
            'level' => 2,
        ]);

        Activity::create([
            'name' => 'Запчасти',
            'description' => 'Запчасти для легковых авто',
            'parent_id' => $passenger->id,
            'level' => 3,
        ]);

        Activity::create([
            'name' => 'Аксессуары',
            'description' => 'Аксессуары для легковых авто',
            'parent_id' => $passenger->id,
            'level' => 3,
        ]);
    }
}

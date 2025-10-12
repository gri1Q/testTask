<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'БЦ «Сокол»',
                'address' => 'Москва, ул. Ленина 1',
                'latitude' => 55.7558,
                'longitude' => 37.6176
            ],
            [
                'name' => 'ТЦ «Орбита»',
                'address' => 'Москва, пр-т Мира 12',
                'latitude' => 55.7810,
                'longitude' => 37.6310
            ],
            [
                'name' => 'Офис-парк «Южный»',
                'address' => 'Москва, ул. Тверская 5',
                'latitude' => 55.7640,
                'longitude' => 37.6050
            ],
            [
                'name' => 'БЦ «Север»',
                'address' => 'Москва, ул. Пушкина 10',
                'latitude' => 55.7660,
                'longitude' => 37.6200
            ],
            [
                'name' => 'БЦ «Восток»',
                'address' => 'Москва, ул. Арбат 20',
                'latitude' => 55.7470,
                'longitude' => 37.5860
            ],
            [
                'name' => 'Админкорпус «Блюхера»',
                'address' => 'Екатеринбург, ул. Блюхера 32/1',
                'latitude' => 56.8570,
                'longitude' => 60.5970
            ],
            [
                'name' => 'Деловой центр «Престиж»',
                'address' => 'Санкт-Петербург, Невский пр. 120',
                'latitude' => 59.9311,
                'longitude' => 30.3609
            ],
            [
                'name' => 'Офисный комплекс «Город»',
                'address' => 'Казань, ул. Баумана 50',
                'latitude' => 55.7887,
                'longitude' => 49.1221
            ],
            [
                'name' => 'Технопарк «Инновация»',
                'address' => 'Новосибирск, Красный пр. 200',
                'latitude' => 55.0302,
                'longitude' => 82.9204
            ],
            [
                'name' => 'БЦ «Дальний Восток»',
                'address' => 'Владивосток, ул. Светланская 15',
                'latitude' => 43.1155,
                'longitude' => 131.8855
            ],
        ];

        foreach ($items as $row) {
            Building::create($row);
        }
    }
}

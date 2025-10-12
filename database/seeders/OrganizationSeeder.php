<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\OrganizationPhone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Сидер организаций: создаёт 18 компаний с разными наборами данных.
 */
class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $b = fn(string $name) => Building::where('name', $name)->value('id');

            $food = Activity::where('name', 'Еда')->value('id');
            $meat = Activity::where('name', 'Мясная продукция')->value('id');
            $dairy = Activity::where('name', 'Молочная продукция')->value('id');
            $trucks = Activity::where('name', 'Грузовые')->value('id');
            $passenger = Activity::where('name', 'Легковые')->value('id');
            $parts = Activity::where('name', 'Запчасти')->value('id');
            $access = Activity::where('name', 'Аксессуары')->value('id');

            $orgs = [
                [
                    'name' => 'ООО «Рога и Копыта»',
                    'description' => 'Поставщик мясной и молочной продукции.',
                    'email' => 'info@rogakopyta.example',
                    'building' => 'Админкорпус «Блюхера»',
                    'phones' => ['2-222-222', '3-333-333', '8-923-666-13-13'],
                    'activities' => [$meat, $dairy],
                ],
                [
                    'name' => 'Мясная лавка',
                    'description' => 'Свежая фермерская мясная продукция.',
                    'email' => 'shop@meat.example',
                    'building' => 'ТЦ «Орбита»',
                    'phones' => ['+7 (495) 100-20-30'],
                    'activities' => [$meat],
                ],
                [
                    'name' => 'Молочная ферма',
                    'description' => 'Молоко и сыры собственного производства.',
                    'email' => 'hello@milkfarm.example',
                    'building' => 'БЦ «Север»',
                    'phones' => ['+7 (495) 200-30-40', '+7 (495) 200-30-50'],
                    'activities' => [$dairy],
                ],
                [
                    'name' => 'ЕдаМаркет',
                    'description' => 'Супермаркет продуктов питания.',
                    'email' => 'contact@edamarket.example',
                    'building' => 'БЦ «Сокол»',
                    'phones' => ['+7 (495) 300-40-50'],
                    'activities' => [$food, $meat, $dairy],
                ],
                [
                    'name' => 'АвтоГигант',
                    'description' => 'Продажа грузовых автомобилей и запчастей.',
                    'email' => 'sale@autogiant.example',
                    'building' => 'Офис-парк «Южный»',
                    'phones' => ['+7 (495) 400-50-60'],
                    'activities' => [$trucks, $parts],
                ],
                [
                    'name' => 'Легковичок',
                    'description' => 'Салон легковых автомобилей.',
                    'email' => 'info@legkovichok.example',
                    'building' => 'БЦ «Восток»',
                    'phones' => ['+7 (495) 500-60-70'],
                    'activities' => [$passenger],
                ],
                [
                    'name' => 'Запчасти24',
                    'description' => 'Онлайн-магазин автозапчастей.',
                    'email' => 'support@zapchasti24.example',
                    'building' => 'БЦ «Сокол»',
                    'phones' => ['+7 (495) 600-70-80'],
                    'activities' => [$parts],
                ],
                [
                    'name' => 'Автоакс',
                    'description' => 'Аксессуары и доп. оборудование для авто.',
                    'email' => 'sales@avtoaks.example',
                    'building' => 'БЦ «Восток»',
                    'phones' => ['+7 (495) 700-80-90'],
                    'activities' => [$access],
                ],
                [
                    'name' => 'TruckMaster',
                    'description' => 'Грузовые перевозки и продажа техники.',
                    'email' => 'contact@truckmaster.example',
                    'building' => 'Деловой центр «Престиж»',
                    'phones' => ['+7 (812) 300-10-20'],
                    'activities' => [$trucks],
                ],
                [
                    'name' => 'Сыроварня №1',
                    'description' => 'Производство и продажа сыров.',
                    'email' => 'cheese@syrovarnya.example',
                    'building' => 'Технопарк «Инновация»',
                    'phones' => ['+7 (383) 100-20-30'],
                    'activities' => [$dairy],
                ],
                [
                    'name' => 'Фермерские продукты',
                    'description' => 'Мясо и молочные продукты от фермеров.',
                    'email' => 'farm@organic.example',
                    'building' => 'Офисный комплекс «Город»',
                    'phones' => ['+7 (843) 300-40-50'],
                    'activities' => [$meat, $dairy],
                ],
                [
                    'name' => 'AutoLuxury',
                    'description' => 'Салон премиальных легковых автомобилей.',
                    'email' => 'info@autoluxury.example',
                    'building' => 'БЦ «Дальний Восток»',
                    'phones' => ['+7 (423) 123-45-67'],
                    'activities' => [$passenger],
                ],
                [
                    'name' => 'DriveParts',
                    'description' => 'Оригинальные запчасти и аксессуары.',
                    'email' => 'parts@driveparts.example',
                    'building' => 'Технопарк «Инновация»',
                    'phones' => ['+7 (383) 321-12-12', '+7 (383) 321-13-13'],
                    'activities' => [$parts, $access],
                ],
                [
                    'name' => 'EcoMilk',
                    'description' => 'Экологичные молочные продукты.',
                    'email' => 'eco@milk.example',
                    'building' => 'БЦ «Сокол»',
                    'phones' => ['+7 (495) 800-11-22'],
                    'activities' => [$dairy],
                ],
                [
                    'name' => 'Мясной Дом',
                    'description' => 'Оптовая продажа мясной продукции.',
                    'email' => 'meat@myasdom.example',
                    'building' => 'ТЦ «Орбита»',
                    'phones' => ['+7 (495) 123-12-12'],
                    'activities' => [$meat],
                ],
                [
                    'name' => 'AutoFix',
                    'description' => 'Сервисный центр и продажа запчастей.',
                    'email' => 'service@autofix.example',
                    'building' => 'Офис-парк «Южный»',
                    'phones' => ['+7 (495) 900-10-10'],
                    'activities' => [$parts],
                ],
                [
                    'name' => 'Milk&Meat',
                    'description' => 'Гастрономический бутик.',
                    'email' => 'info@milkmeat.example',
                    'building' => 'Деловой центр «Престиж»',
                    'phones' => ['+7 (812) 321-54-76'],
                    'activities' => [$meat, $dairy],
                ],
            ];

            foreach ($orgs as $row) {
                $org = Organization::create([
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'email' => $row['email'],
                    'building_id' => $b($row['building']),
                ]);

                foreach ($row['phones'] as $phone) {
                    OrganizationPhone::create([
                        'organization_id' => $org->id,
                        'phone' => $phone,
                    ]);
                }

                $org->activities()->attach($row['activities']);
            }
        });
    }
}

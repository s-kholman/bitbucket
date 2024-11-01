<?php

namespace Database\Seeders;

use App\Models\Event;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');

        for ($i = 1; $i <= 30; $i++){
            Event::query()
                ->create([
                        'name' => $faker->company(),
                        'description' => $faker->realText(),
                        'schedule' => Carbon::parse($faker->dateTimeInInterval('+1 mount', '+30 days'))->format('d-m-Y'),
                        'adult_price' => rand(1000, 9999),
                        'kid_price' => rand(700, 999),
                    ]
                );
        }
    }
}

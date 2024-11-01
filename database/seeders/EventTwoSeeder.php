<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventTwo;
use App\Models\TicketTypeEvent;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTwoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ru_RU');

        for ($i = 1; $i <= 30; $i++){
            $model = EventTwo::query()
                ->create([
                        'name' => $faker->company(),
                        'description' => $faker->realText(),
                        'schedule' => Carbon::parse($faker->dateTimeInInterval('+1 mount', '+30 days'))->format('d-m-Y'),
                    ]
                );

            for ($y = 1; $y <= rand(1,4); $y++){
                TicketTypeEvent::query()
                    ->create([
                        'event_two_id' => $model->id,
                        'ticket_type_id' => $y,
                    ]);
            }
        }
    }
}

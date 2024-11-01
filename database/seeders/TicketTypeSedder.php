<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketTypeSedder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticket_type = ['ticket_adult' => 'Взрослый', 'ticket_kid' => 'Детский','ticked_group' => 'Групповой','ticked_preferential' => 'Льготный'];

        foreach ($ticket_type as $type => $name){
            TicketType::query()
                ->create([
                    'name' => $name,
                    'type' => $type
                ]);
        }
    }
}

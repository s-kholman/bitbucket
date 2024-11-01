<?php

namespace App\Actions;

class ApiBooking
{
    public function __invoke($event_id, $event_date, $ticket_adult_price, $ticket_adult_quantity, $ticket_kid_price, $ticket_kid_quantity, $barcode)
    {
        if (rand(0, 1)){
            return ['message'=> 'order successfully booked'];
        } else {
            return ['error' => 'barcode already exists'];
        }
    }
}

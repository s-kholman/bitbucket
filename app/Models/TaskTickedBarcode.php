<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTickedBarcode extends Model
{
    use HasFactory;
    protected $fillable = ['event_two_id','event_date','equal_price','created' ];

    public function ticketBarcode()
    {
        return $this->hasMany(TicketBarcode::class);
    }
}

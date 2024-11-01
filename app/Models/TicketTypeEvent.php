<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTypeEvent extends Model
{
    use HasFactory;
    protected $fillable = ['event_twos_id','ticket_types_id'];
}

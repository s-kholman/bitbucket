<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFirst extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id',
        'event_date',
        'ticket_adult_price',
        'ticket_adult_quantity',
        'ticket_kid_price',
        'ticket_kid_quantity',
        'barcode',
        'equal_price',
        'created',
    ];

}

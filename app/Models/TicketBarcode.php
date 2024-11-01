<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBarcode extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_type_id','task_ticked_barcode_id','ticket_price','barcode'];
}

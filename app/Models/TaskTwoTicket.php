<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTwoTicket extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_type_id','task_two_id','ticket_price','ticket_quantity'];
}

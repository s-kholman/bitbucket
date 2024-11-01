<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTwo extends Model
{
    use HasFactory;

    protected $fillable = ['event_two_id','event_date','barcode','equal_price','created'];

    public function taskTwoTicket()
    {
        return $this->hasMany(TaskTwoTicket::class);
    }
}

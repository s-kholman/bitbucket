<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_two_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id');
            $table->foreignId('task_two_id');
            $table->integer('ticket_price');
            $table->integer('ticket_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_two_tickets');
    }
};

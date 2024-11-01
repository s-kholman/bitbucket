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
        Schema::create('task_twos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_two_id');
            $table->date('event_date');
            $table->string('barcode', 120)->unique();
            $table->integer('equal_price');
            $table->date('created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_twos');
    }
};

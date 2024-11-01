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
        Schema::create('task_ticked_barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_two_id');
            $table->date('event_date');
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
        Schema::dropIfExists('task_ticked_barcodes');
    }
};

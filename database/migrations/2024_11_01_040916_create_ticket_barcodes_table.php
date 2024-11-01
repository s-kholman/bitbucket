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
        Schema::create('ticket_barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_type_id');
            $table->foreignId('task_ticked_barcode_id');
            $table->integer('ticket_price');
            $table->string('barcode', 120)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_barcodes');
    }
};

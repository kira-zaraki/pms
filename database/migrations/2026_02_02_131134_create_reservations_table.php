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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed' ])->default('confirmed');
            $table->unsignedSmallInteger('total_nights')->nullable();
            $table->decimal('price_per_night', 8, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('external_id')->nullable()->unique();
            $table->string('source')->default('internal');

            $table->timestamps();

            $table->index(['room_id', 'check_in_date', 'check_out_date']);
            

            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

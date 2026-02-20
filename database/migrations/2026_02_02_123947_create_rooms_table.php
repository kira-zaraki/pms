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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->unsignedTinyInteger('floor')->nullable();
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->enum('type', ['normal', 'suite'])->default('normal');
            $table->boolean('is_cleaned')->default(true);
            $table->decimal('price_per_night', 8, 2)->nullable();
            $table->string('image')->nullable();
            $table->string('ota_ical_import_url')->nullable(); 
            $table->string('ical_export_token')->unique()->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('star_rating')->default(3); // 1-5
            $table->json('room_types')->nullable();
            $table->string('location_address');
            $table->decimal('nightly_rate_pkr', 12, 2)->default(0);
            $table->unsignedInteger('total_rooms')->default(1);
            $table->unsignedInteger('available_rooms')->default(1);
            $table->boolean('is_partner')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};

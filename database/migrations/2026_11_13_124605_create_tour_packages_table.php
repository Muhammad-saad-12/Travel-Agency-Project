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
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedInteger('duration_days')->default(1);
            $table->decimal('price_pkr', 12, 2)->default(0);
            $table->string('difficulty_rating')->default('Easy'); // Easy, Moderate, Challenging
            $table->boolean('is_umrah')->default(false);
            $table->json('itinerary')->nullable();
            $table->json('inclusions')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};

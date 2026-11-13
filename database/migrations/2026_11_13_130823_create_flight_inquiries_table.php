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
        Schema::create('flight_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('trip_type')->default('round_trip'); // one_way, round_trip, multi_city
            $table->string('origin');
            $table->string('destination');
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->json('multi_city_segments')->nullable();
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);
            $table->unsignedInteger('infants')->default(0);
            $table->string('cabin_class')->default('Economy'); // Economy, Premium Economy, Business, First
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->text('notes')->nullable();
            $table->decimal('quoted_price_pkr', 12, 2)->nullable();
            $table->string('status')->default('Pending'); // Pending, Contacted, Quoted, Booked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_inquiries');
    }
};

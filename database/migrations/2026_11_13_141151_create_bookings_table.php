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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->string('pnr')->nullable()->index();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('service')->default('Flight Ticket');
            $table->string('type')->default('flight'); // flight, hotel, umrah, package, group
            $table->string('destination')->nullable();
            $table->string('route')->nullable();
            $table->string('airline')->nullable();
            $table->string('flight_number')->nullable();
            $table->date('travel_date')->nullable();
            $table->date('return_date')->nullable();
            $table->unsignedInteger('passengers_count')->default(1);
            $table->decimal('fare_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('ancillaries_amount', 12, 2)->default(0);
            $table->decimal('markup_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('PKR');
            $table->string('status')->default('Confirmed'); // Confirmed, Pending, Ticketed, Cancelled, Refunded
            $table->string('payment_status')->default('Paid'); // Paid, Unpaid, Partially Paid, Refunded
            $table->string('supplier')->default('Amadeus GDS (1A)');
            $table->string('ticket_ref')->nullable();
            $table->dateTime('time_limit')->nullable();
            $table->json('passengers_data')->nullable();
            $table->json('segments_data')->nullable();
            $table->json('ancillaries_data')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

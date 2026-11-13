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
        Schema::create('offline_requests', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref');
            $table->string('pnr')->index();
            $table->string('airline');
            $table->string('route');
            $table->string('passenger_name');
            $table->string('ticket_number')->nullable();
            $table->date('travel_date');
            $table->string('request_type')->default('ticketing'); // ticketing, refund, void, schedule_change
            $table->string('status')->default('Applied'); // Applied, In Progress, Processed, Rejected
            $table->boolean('is_urgent')->default(false);
            $table->string('assigned_user')->nullable();
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->dateTime('requested_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_requests');
    }
};

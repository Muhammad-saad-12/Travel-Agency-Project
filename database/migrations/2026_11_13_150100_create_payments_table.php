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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_ref')->unique()->index();
            $table->string('booking_id')->nullable()->index(); // Booking reference e.g. PK-BK-8901
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('channel'); // Raast P2P/P2M, 1Link Bank Transfer, JazzCash, EasyPaisa, Credit/Debit Card, Bank Deposit
            $table->string('channel_category')->default('bank'); // raast, 1link, wallet, card, bank
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('PKR');
            $table->string('status')->default('Pending')->index(); // Verified, Pending, Failed, Refunded
            $table->string('gateway_ref')->nullable(); // External bank / gateway reference ID
            $table->string('proof_file')->nullable(); // Deposit slip or receipt screenshot reference
            $table->dateTime('payment_date');
            $table->dateTime('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

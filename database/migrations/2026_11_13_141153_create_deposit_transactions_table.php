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
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('agency_node_id')->nullable()->constrained('agency_nodes')->nullOnDelete();
            $table->string('agency_code')->default('CLI-20000');
            $table->string('agency_name');
            $table->string('bank');
            $table->string('type')->default('Bank Deposit Slip'); // Bank Deposit Slip, Online Transfer, Credit Adjustment, Ticket Debit, Refund Credit
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->string('status')->default('Verified'); // Verified, Pending Approval, Rejected
            $table->string('proof_file')->nullable();
            $table->string('verified_by')->nullable();
            $table->dateTime('transaction_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_transactions');
    }
};

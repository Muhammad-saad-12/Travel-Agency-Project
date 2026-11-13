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
        Schema::create('agency_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type')->default('Branch'); // HQ, Branch, Sub-Agent
            $table->string('city');
            $table->string('country')->default('PK');
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency', 3)->default('PKR');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('Active'); // Active, Suspended, Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_nodes');
    }
};

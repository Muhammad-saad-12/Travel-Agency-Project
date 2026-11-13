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
        Schema::create('agency_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('dts_license')->nullable();
            $table->string('ntn')->nullable();
            $table->string('strn')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('default_currency', 10)->default('PKR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency_settings');
    }
};

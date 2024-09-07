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
        Schema::table('vehicle_usages', function (Blueprint $table) {
            $table->dateTime('end_time')->nullable()->change();
            $table->integer('kilometers_driven')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_usages', function (Blueprint $table) {
            $table->dateTime('end_date')->nullable(false)->change();
            $table->integer('kilometers_driven')->nullable(false)->change();
        });
    }
};

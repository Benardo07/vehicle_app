<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is used to make changes to the database.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('start_time');
        });
    }

    /**
     * Reverse the migrations.
     * This method is used to revert the changes made in the up method.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable();
        });
    }
};

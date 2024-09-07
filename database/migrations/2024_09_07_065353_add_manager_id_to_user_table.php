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
        Schema::table('users', function (Blueprint $table) {
            // Add the 'role' enum column
            $table->enum('role', ['admin', 'manager', 'driver'])->default('driver');

            // Drop the foreign key and column for 'manager_id'
            $table->dropForeign(['manager_id']); // First drop the foreign key
            $table->dropColumn('manager_id'); // Then drop the column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the 'manager_id' column and foreign key again
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('employees');

            // Remove the 'role' enum column
            $table->dropColumn('role');
        });
    }
};

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
        Schema::table('bookings', function (Blueprint $table) {
            // Add the 'approver_id' column as a nullable unsigned BigInteger
            $table->unsignedBigInteger('approver_id')->nullable()->after('id'); // Placing it after 'id' for clarity

            // Add a foreign key constraint referencing the 'id' on the 'users' table
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null'); 
            // onDelete('set null') will set the 'approver_id' to null if the referenced user is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['approver_id']);
            
            // Then drop the column
            $table->dropColumn('approver_id');
        });
    }
};

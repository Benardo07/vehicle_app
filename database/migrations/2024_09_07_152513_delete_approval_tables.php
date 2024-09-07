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
        // Update the bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('approved'); // Drop the approved column
            $table->enum('status', ['pending', 'allowed', 'rejected'])->default('pending'); // Add status column
        });

        // Optionally, drop the approvals table if it exists
        Schema::dropIfExists('approvals');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally re-create the approvals table if it was previously deleted
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Other necessary columns
        });

        // Revert changes made to the bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->tinyInteger('approved')->default(0);
            $table->dropColumn('status');
        });
    }
};

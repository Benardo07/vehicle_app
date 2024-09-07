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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Misal 'personnel carrier', 'cargo transporter'
            $table->string('license_plate')->unique();
            $table->string('ownership'); // Misal 'owned', 'leased'
            $table->double('fuel_consumption'); // Konsumsi BBM per km
            $table->string('status')->default('available'); // Possible values: available, in_use, maintenance, etc.
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('employee_id');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('purpose'); // Tujuan penggunaan kendaraan
            $table->boolean('approved')->default(false); // Status persetujuan
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('approver_id'); // ID atasan atau manajer yang menyetujui
            $table->boolean('is_approved')->default(false);
            $table->text('remarks')->nullable(); // Catatan persetujuan atau penolakan
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->date('service_date');
            $table->string('service_type'); // e.g., 'regular', 'emergency'
            $table->text('description');
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
        });

        Schema::create('vehicle_usage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('employee_id');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('kilometers_driven');
            $table->string('purpose')->nullable();
            $table->text('notes')->nullable(); // Additional notes about the trip
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('services');
        Schema::dropIfExists('vehicle_usage');
    }
};

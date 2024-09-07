<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('model_name')->after('type'); // Adding the model_name column
            $table->string('image_path')->nullable()->after('model_name'); // Adding the image_path column, nullable if not all vehicles have images initially
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['model_name', 'image_path']); // Rollback by removing these columns
        });
    }
};

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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('horse_power_id');
            $table->unsignedBigInteger('mounting_type_id');
            $table->unsignedBigInteger('type_id');

            $table->string('item_number')->unique();
            $table->string('indoor_model');
            $table->json('indoor_sn')->nullable();

            $table->string('outdoor_model');
            $table->json('outdoor_sn')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

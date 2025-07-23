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
        Schema::create('particulars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('indoor_model')->nullable();
            $table->string('outdoor_model')->nullable();
            $table->json('indoor_sn')->nullable();
            $table->json('outdoor_sn')->nullable();
            $table->string('inv_1')->nullable();
            $table->string('inv_2')->nullable();
            $table->date('date_issued_1')->nullable();
            $table->date('date_issued_2')->nullable();
            $table->float('total', 10, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps(); // includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('particulars');
    }
};

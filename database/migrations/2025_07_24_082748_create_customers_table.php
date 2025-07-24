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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('item_num')->nullable(); // JSON field
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('delivery_no')->nullable();
            $table->date('delivery_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable(); // Adjust precision if needed
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

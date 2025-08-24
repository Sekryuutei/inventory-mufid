<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete(); // lebih aman, idiomatis Laravel

            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->dateTime('transaction_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};

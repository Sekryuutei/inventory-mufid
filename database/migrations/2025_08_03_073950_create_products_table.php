<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // sudah auto increment
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('stock')->default(0); // bisa pakai unsignedBigInteger kalau stok sangat besar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

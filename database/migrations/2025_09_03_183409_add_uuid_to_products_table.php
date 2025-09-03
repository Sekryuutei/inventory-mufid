<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom uuid setelah kolom id
            $table->uuid('uuid')->after('id')->unique()->nullable();
        });

        // Isi UUID untuk data produk yang sudah ada
        Product::all()->each(function ($product) {
            $product->uuid = Str::uuid();
            $product->save();
        });

        // Jadikan kolom uuid tidak boleh null
        Schema::table('products', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};

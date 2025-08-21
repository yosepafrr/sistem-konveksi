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
            
            $table->bigInteger('item_id')->unique();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('item_sku')->nullable();
            $table->string('item_status')->nullable();
            $table->integer('stock')->default(0);
            $table->bigInteger('price')->default(0); // atau pakai bigint
            $table->string('category')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

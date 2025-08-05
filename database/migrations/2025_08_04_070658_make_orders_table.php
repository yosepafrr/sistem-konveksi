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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('order_sn')->unique(); // Nomor pesanan dari Shopee
            $table->timestamp('order_time');
            $table->string('order_status');
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->json('raw_data')->nullable(); // bisa simpan JSON full untuk cadangan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

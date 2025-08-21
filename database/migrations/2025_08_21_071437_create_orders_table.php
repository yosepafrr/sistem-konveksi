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
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->bigInteger('item_id')->nullable();
            $table->string('order_sn')->unique(); // Nomor pesanan dari Shopee
            $table->integer('quantity_purchased')->nullable();
            $table->timestamp('order_time')->nullable();
            $table->string('order_status')->nullable();
            $table->json('raw_data')->nullable(); // bisa simpan JSON full untuk cadangan
            $table->string('booking_sn')->nullable();
            $table->boolean('cod')->default(false);
            $table->dateTime('ship_by_date')->nullable();
            $table->text('message_to_seller')->nullable();
            $table->unsignedBigInteger('order_selling_price')->nullable();
            $table->unsignedBigInteger('escrow_amount')->nullable();
            $table->unsignedBigInteger('escrow_amount_after_adjustment')->nullable();

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

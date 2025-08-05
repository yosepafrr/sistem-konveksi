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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('booking_sn')->nullable();
            $table->boolean('cod')->default(false);
            $table->timestamp('create_time')->nullable();
            $table->dateTime('ship_by_date')->nullable();
            $table->text('message_to_seller')->nullable();
            $table->unsignedBigInteger('order_selling_price')->nullable();
            $table->unsignedBigInteger('escrow_amount_after_adjustment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('booking_sn')->nullable();
            $table->boolean('cod')->default(false);
            $table->timestamp('create_time')->nullable();
            $table->dateTime('ship_by_date')->nullable();
            $table->text('message_to_seller')->nullable();
            $table->unsignedBigInteger('order_selling_price')->nullable();
            $table->unsignedBigInteger('escrow_amount_after_adjustment')->nullable();
        });
    }
};

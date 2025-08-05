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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('shopee_shop_id')->unique(); // Tambah kolom baru
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->time('token_expired_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['shopee_shop_id', 'access_token', 'refresh_token', 'token_expired_at']);
            $table->dropColumn('access_token')->nullable();
            $table->dropColumn('refresh_token')->nullable();
            $table->dropColumn('token_expired_at')->nullable();
        });
    }
};

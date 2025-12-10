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
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('hpp', 15, 2)->nullable()->after('price');
        });

        
        Schema::create('variant_items', function (Blueprint $table) 
        {
            $table->id();
            
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('model_id')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_sku')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('hpp', 15, 2)->nullable();
            $table->string('status')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('hpp');
        });

        Schema::dropIfExists('variant_items');
    }
};

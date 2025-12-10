<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('variant_items', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });
    }

    public function down()
    {
        Schema::table('variant_items', function (Blueprint $table) {
            $table->integer('model_id')->change();
        });
    }
};

<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE stores
            ALTER COLUMN token_expired_at TYPE timestamp
            USING now()::date + token_expired_at
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE stores
            ALTER COLUMN token_expired_at TYPE time
            USING token_expired_at::time
        ");
    }
};

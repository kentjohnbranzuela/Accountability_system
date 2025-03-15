<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyQuantityNullableInTechnicians extends Migration
{
    public function up()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->change(); // ✅ Ginawang nullable
        });
    }

    public function down()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->integer('quantity')->nullable(false)->change(); // ⬅️ Ibalik sa dating setting kung kakailanganin
        });
    }
}


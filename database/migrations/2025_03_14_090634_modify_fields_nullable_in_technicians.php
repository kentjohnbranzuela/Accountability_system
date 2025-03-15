<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFieldsNullableInTechnicians extends Migration
{
    public function up()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('status')->nullable()->change(); // ✅ Ginawang nullable
            $table->string('ser_no')->nullable()->change(); // ✅ Ginawang nullable
        });
    }

    public function down()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('status')->nullable(false)->change();
            $table->string('ser_no')->nullable(false)->change();
        });
    }
}


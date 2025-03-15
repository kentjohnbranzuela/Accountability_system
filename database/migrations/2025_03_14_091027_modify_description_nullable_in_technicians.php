<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDescriptionNullableInTechnicians extends Migration
{
    public function up()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('description')->nullable()->change(); // ✅ Ginawang nullable
        });
    }

    public function down()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change(); // ⬅️ Ibalik sa dating setting kung kakailanganin
        });
    }
}

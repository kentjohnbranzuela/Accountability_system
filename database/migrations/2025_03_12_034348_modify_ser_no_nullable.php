<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('ser_no')->nullable()->change(); // Change this to match your intended table
        });
    }
    
    public function down()
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('ser_no')->nullable(false)->change(); // Rollback if needed
        });
    }
};
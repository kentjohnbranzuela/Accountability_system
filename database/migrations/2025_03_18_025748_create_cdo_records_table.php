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
    Schema::create('cdo_records', function (Blueprint $table) {
        $table->id();
        $table->string('position')->nullable();
        $table->string('name');
        $table->date('date')->nullable();
        $table->integer('quantity')->default(0);
        $table->string('description')->nullable();
        $table->string('ser_no')->nullable();
        $table->string('status');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdo_records');
    }
};

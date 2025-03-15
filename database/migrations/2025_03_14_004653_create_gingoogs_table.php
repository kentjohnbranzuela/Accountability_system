<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('gingoogs', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable();
            $table->string('name');
            $table->date('date'); // ✅ Renamed to 'date_received'
            $table->integer('quantity');
            $table->text('description');
            $table->string('ser_no')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gingoogs');
    }
};

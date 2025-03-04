<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('accountability_records', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('id_number');
            $table->string('name');
            $table->date('date');
            $table->integer('quantity');
            $table->text('description');
            $table->string('ser_no')->unique();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accountability_records');
    }
};


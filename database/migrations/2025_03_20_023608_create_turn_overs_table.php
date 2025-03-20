<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('turn_overs', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable();
            $table->string('name'); // Person Responsible for Turn Over
            $table->date('date'); // Date of Turn Over
            $table->integer('quantity')->default(0);
            $table->text('description'); // Description of the Turn Over Item
            $table->string('ser_no')->nullable(); // Serial Number
            $table->string('status'); // Status (e.g., Pending, Completed, etc.)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('turn_overs');
    }
};

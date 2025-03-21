
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('resign_records', function (Blueprint $table) {
            $table->id();
            $table->string('position')->nullable();
            $table->string('name'); // Person Responsible for Turn Over
            $table->date('date'); // Date of Turn Over
            $table->integer('quantity')->default(0);
            $table->text('description'); // Description of the Turn Over Item
            $table->string('ser_no')->nullable(); // Serial Number
            $table->string('status')->nullable(); // Status (e.g., Pending, Completed, etc.)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('resign_records');
    }
};

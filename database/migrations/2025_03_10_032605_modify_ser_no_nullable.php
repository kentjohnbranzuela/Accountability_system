<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accountability_records', function (Blueprint $table) {
            $table->string('ser_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountability_records', function (Blueprint $table) {
            $table->string('ser_no')->nullable(false)->change(); // Revert if needed
        });
    }
};

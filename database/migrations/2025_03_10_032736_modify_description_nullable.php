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
            $table->string('description')->default('N/A')->nullable()->change(); // Set default "N/A"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountability_records', function (Blueprint $table) {
            $table->string('description')->nullable(false)->default(null)->change(); // Revert default
        });
    }
};

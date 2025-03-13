<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('accountability_records', function (Blueprint $table) {
        $table->string('ser_no')->nullable()->change(); // Keep nullable to prevent errors
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accountability_records', function (Blueprint $table) {
            $table->string('ser_no', 255)->nullable(false)->default(null)->change(); // Revert change
        });
    }
};

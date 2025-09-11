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
        Schema::table('academic_plans', function (Blueprint $table) {
            $table->integer('periods_count')->default(4)->after('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_plans', function (Blueprint $table) {
            $table->dropColumn('periods_count');
        });
    }
};

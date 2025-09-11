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
    public function up(): void
    {
        // Add period_number as integer and remove name
        Schema::table('periods', function (Blueprint $table) {
            $table->integer('period_number')->after('academic_plan_id');
        });

        // Populate period_number with period_order values
        DB::statement('UPDATE periods SET period_number = period_order');

        // Remove name and period_order columns
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn(['name', 'period_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back name and period_order
        Schema::table('periods', function (Blueprint $table) {
            $table->string('name', 50)->after('academic_plan_id');
            $table->integer('period_order')->after('name');
        });

        // Populate the columns (PostgreSQL uses || for concatenation)
        DB::statement('UPDATE periods SET period_order = period_number, name = \'Período \' || period_number');

        // Remove period_number
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('period_number');
        });
    }
};

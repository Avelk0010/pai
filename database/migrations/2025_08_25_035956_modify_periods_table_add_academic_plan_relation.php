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
        // Add academic_plan_id column
        Schema::table('periods', function (Blueprint $table) {
            $table->foreignId('academic_plan_id')->nullable()->after('id')->constrained('academic_plans')->onDelete('cascade');
        });

        // Migrate existing periods to academic plans based on academic_year
        // Each unique academic_year becomes an academic_plan
        DB::statement('
            UPDATE periods 
            SET academic_plan_id = ap.id
            FROM academic_plans ap 
            WHERE periods.academic_year = ap.academic_year
        ');

        // Make academic_plan_id not nullable
        Schema::table('periods', function (Blueprint $table) {
            $table->foreignId('academic_plan_id')->nullable(false)->change();
        });

        // Remove the old academic_year column
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back academic_year column
        Schema::table('periods', function (Blueprint $table) {
            $table->year('academic_year')->after('end_date');
        });

        // Restore academic_year from academic_plan
        DB::statement('
            UPDATE periods 
            SET academic_year = ap.academic_year
            FROM academic_plans ap 
            WHERE periods.academic_plan_id = ap.id
        ');

        // Drop the foreign key and column
        Schema::table('periods', function (Blueprint $table) {
            $table->dropForeign(['academic_plan_id']);
            $table->dropColumn('academic_plan_id');
        });
    }
};

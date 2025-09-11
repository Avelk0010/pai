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
        // Add grade_level_id column
        Schema::table('subject_assignments', function (Blueprint $table) {
            $table->foreignId('grade_level_id')->after('group_id')->nullable()->constrained('grade_levels')->onDelete('cascade');
        });

        // Populate grade_level_id from the subject's academic plan
        DB::statement('
            UPDATE subject_assignments 
            SET grade_level_id = ap.grade_level_id
            FROM subjects s 
            JOIN academic_plans ap ON s.academic_plan_id = ap.id 
            WHERE subject_assignments.subject_id = s.id
        ');

        // Make grade_level_id not nullable now that it's populated
        Schema::table('subject_assignments', function (Blueprint $table) {
            $table->foreignId('grade_level_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_assignments', function (Blueprint $table) {
            $table->dropForeign(['grade_level_id']);
            $table->dropColumn('grade_level_id');
        });
    }
};

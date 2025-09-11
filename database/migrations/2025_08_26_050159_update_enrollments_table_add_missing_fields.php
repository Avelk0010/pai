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
        Schema::table('enrollments', function (Blueprint $table) {
            // Add missing fields that are being used in UserController
            $table->date('enrollment_date')->default(now()->toDateString())->after('academic_year');
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active')->after('enrollment_date');
            $table->boolean('is_active')->default(true)->after('status'); // Para compatibilidad con EnrollmentController
            $table->foreignId('grade_level_id')->nullable()->constrained('grade_levels')->onDelete('cascade')->after('group_id');
            
            // Set default value for academic_year to current year
            $table->year('academic_year')->default(date('Y'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['enrollment_date', 'status', 'is_active', 'grade_level_id']);
            $table->year('academic_year')->change(); // Remove default
        });
    }
};

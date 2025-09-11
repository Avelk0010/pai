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
        Schema::create('period_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->decimal('final_grade', 3, 2);
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'subject_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_grades');
    }
};

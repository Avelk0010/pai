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
        Schema::create('student_period_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->decimal('final_grade', 3, 2); // Nota final de la materia en el período
            $table->decimal('weighted_grade', 5, 2); // Nota ponderada por créditos
            $table->enum('status', ['passed', 'failed', 'pending'])->default('pending');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
            
            // Índices únicos
            $table->unique(['student_id', 'period_id', 'subject_id']);
            
            // Índices para consultas
            $table->index(['period_id', 'student_id']);
            $table->index(['student_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_period_grades');
    }
};

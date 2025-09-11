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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('periods')->onDelete('cascade');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('activity_type', ['exam', 'quiz', 'assignment', 'project', 'participation']);
            $table->dateTime('due_date')->nullable();
            $table->decimal('max_score', 3, 2)->default(5.0);
            $table->decimal('percentage', 5, 2); // percentage of the period grade
            $table->enum('status', ['draft', 'published', 'finished'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

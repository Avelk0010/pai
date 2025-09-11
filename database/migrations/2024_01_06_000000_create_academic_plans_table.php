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
        Schema::create('academic_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->string('name', 100);
            $table->year('academic_year');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_plans');
    }
};

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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_plan_id')->constrained('academic_plans')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            $table->integer('credits')->default(1);
            $table->string('area', 50); // "Mathematics", "Sciences", "Languages", "Social Studies"
            $table->boolean('is_mandatory')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

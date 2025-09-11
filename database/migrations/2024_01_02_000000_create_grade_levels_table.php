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
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_number'); // 6, 7, 8, 9, 10, 11
            $table->string('grade_name', 50); // "Sixth Grade", "Seventh Grade", etc.
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_levels');
    }
};

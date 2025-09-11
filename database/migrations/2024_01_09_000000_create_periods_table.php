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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50); // "First Period", "Second Period", "Third Period"
            $table->integer('period_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->year('academic_year');
            $table->enum('status', ['upcoming', 'active', 'finished'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};

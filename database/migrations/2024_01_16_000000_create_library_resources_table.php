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
        Schema::create('library_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('author', 150)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->enum('resource_type', ['book', 'magazine', 'digital', 'multimedia']);
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->string('location', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_resources');
    }
};

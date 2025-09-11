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
        // For PostgreSQL, we need to drop and recreate the column to change enum values
        // First, add a temporary column
        Schema::table('periods', function (Blueprint $table) {
            $table->enum('status_new', ['planned', 'upcoming', 'active', 'finished'])->default('planned')->after('status');
        });
        
        // Copy data to new column, mapping old values
        DB::statement("UPDATE periods SET status_new = CASE 
            WHEN status = 'upcoming' THEN 'upcoming'
            WHEN status = 'active' THEN 'active'
            WHEN status = 'finished' THEN 'finished'
            ELSE 'planned'
        END");
        
        // Drop old column and rename new one
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('periods', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the process: add temp column with old enum values
        Schema::table('periods', function (Blueprint $table) {
            $table->enum('status_old', ['upcoming', 'active', 'finished'])->default('upcoming')->after('status');
        });
        
        // Copy data back, excluding 'planned' values
        DB::statement("UPDATE periods SET status_old = CASE 
            WHEN status = 'upcoming' THEN 'upcoming'
            WHEN status = 'active' THEN 'active'
            WHEN status = 'finished' THEN 'finished'
            ELSE 'upcoming'
        END");
        
        // Drop new column and rename old one
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('periods', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};

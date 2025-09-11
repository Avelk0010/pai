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
        Schema::table('library_loans', function (Blueprint $table) {
            // Add temporary column with new enum values
            $table->enum('status_new', ['requested', 'approved', 'active', 'returned', 'overdue', 'rejected'])->default('requested')->after('status');
            $table->timestamp('requested_at')->nullable()->after('status_new');
            $table->timestamp('approved_at')->nullable()->after('requested_at');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->text('rejection_reason')->nullable()->after('approved_by');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Copy data to new column
        DB::statement("UPDATE library_loans SET status_new = CASE 
            WHEN status = 'active' THEN 'active'
            WHEN status = 'returned' THEN 'returned'
            WHEN status = 'overdue' THEN 'overdue'
            ELSE 'requested'
        END");
        
        // Drop old column and rename new one
        Schema::table('library_loans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('library_loans', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('library_loans', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['requested_at', 'approved_at', 'approved_by', 'rejection_reason', 'rejected_at', 'rejected_by']);
            
            // Add temporary column with old enum values
            $table->enum('status_old', ['active', 'returned', 'overdue'])->default('active')->after('status');
        });
        
        // Copy data back
        DB::statement("UPDATE library_loans SET status_old = CASE 
            WHEN status = 'active' THEN 'active'
            WHEN status = 'returned' THEN 'returned'
            WHEN status = 'overdue' THEN 'overdue'
            ELSE 'active'
        END");
        
        // Drop new column and rename old one
        Schema::table('library_loans', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('library_loans', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};

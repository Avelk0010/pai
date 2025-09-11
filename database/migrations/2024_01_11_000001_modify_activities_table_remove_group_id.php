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
        // Primero, migrar datos existentes a la tabla pivot
        $activities = DB::table('activities')->get();
        
        foreach ($activities as $activity) {
            if ($activity->group_id) {
                DB::table('activity_group')->insert([
                    'activity_id' => $activity->id,
                    'group_id' => $activity->group_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Luego eliminar la columna group_id
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
        });
        
        // Restaurar datos desde la tabla pivot
        $pivotData = DB::table('activity_group')->get();
        
        foreach ($pivotData as $pivot) {
            DB::table('activities')
                ->where('id', $pivot->activity_id)
                ->update(['group_id' => $pivot->group_id]);
        }
    }
};

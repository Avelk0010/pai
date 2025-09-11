<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "Checking groups table structure...\n";
    echo "================================\n";
    
    $columns = DB::select("SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_name = 'groups' ORDER BY ordinal_position");
    
    foreach ($columns as $column) {
        echo "Column: " . $column->column_name . " - Type: " . $column->data_type . " - Null: " . $column->is_nullable . "\n";
    }
    
    $hasHomeroomTeacher = false;
    foreach ($columns as $column) {
        if ($column->column_name === 'homeroom_teacher_id') {
            $hasHomeroomTeacher = true;
            break;
        }
    }
    
    echo "\n";
    if ($hasHomeroomTeacher) {
        echo "✅ homeroom_teacher_id column EXISTS\n";
    } else {
        echo "❌ homeroom_teacher_id column MISSING\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

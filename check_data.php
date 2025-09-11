<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Verificar si grade_level_id está poblado
$assignments = DB::select('SELECT id, subject_id, grade_level_id FROM subject_assignments LIMIT 5');

echo "Primeras 5 asignaciones:\n";
foreach ($assignments as $assignment) {
    echo "ID: {$assignment->id}, Subject: {$assignment->subject_id}, Grade Level: {$assignment->grade_level_id}\n";
}

// Contar asignaciones sin grade_level_id
$nullCount = DB::selectOne('SELECT COUNT(*) as count FROM subject_assignments WHERE grade_level_id IS NULL');
echo "\nAsignaciones sin grade_level_id: {$nullCount->count}\n";

echo "\n";

<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CREANDO INSCRIPCIONES AUTOMÁTICAS ===" . PHP_EOL;

// Obtener estudiantes sin inscripción para el año actual
$currentYear = date('Y');
$studentsWithoutEnrollment = \App\Models\User::where('role', 'student')
    ->whereNotExists(function($query) use ($currentYear) {
        $query->select(\Illuminate\Support\Facades\DB::raw(1))
              ->from('enrollments')
              ->whereColumn('enrollments.student_id', 'users.id')
              ->where('enrollments.academic_year', $currentYear);
    })
    ->get();

echo "Estudiantes sin inscripción: " . $studentsWithoutEnrollment->count() . PHP_EOL;

// Obtener grupos disponibles
$availableGroups = \App\Models\Group::where('status', true)->get();
echo "Grupos disponibles: " . $availableGroups->count() . PHP_EOL;

if ($availableGroups->count() > 0 && $studentsWithoutEnrollment->count() > 0) {
    foreach ($studentsWithoutEnrollment as $index => $student) {
        // Asignar estudiantes a grupos de forma rotativa
        $group = $availableGroups[$index % $availableGroups->count()];
        
        $enrollment = \App\Models\Enrollment::create([
            'student_id' => $student->id,
            'group_id' => $group->id,
            'grade_level_id' => $group->grade_level_id,
            'academic_year' => $currentYear,
            'enrollment_date' => now(),
            'status' => 'active',
            'is_active' => true
        ]);
        
        echo "✅ Inscrito: {$student->first_name} {$student->last_name} en grupo {$group->name}" . PHP_EOL;
    }
    
    echo PHP_EOL . "=== RESUMEN FINAL ===" . PHP_EOL;
    echo "Total inscripciones: " . \App\Models\Enrollment::count() . PHP_EOL;
    
} else {
    echo "❌ No hay grupos disponibles o no hay estudiantes sin inscripción." . PHP_EOL;
}

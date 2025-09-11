<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICACIÓN DE DATOS ===" . PHP_EOL;
echo "Estudiantes: " . \App\Models\User::where('role', 'student')->count() . PHP_EOL;
echo "Grupos: " . \App\Models\Group::count() . PHP_EOL;
echo "Inscripciones: " . \App\Models\Enrollment::count() . PHP_EOL;
echo "Planes académicos: " . \App\Models\AcademicPlan::count() . PHP_EOL;

echo PHP_EOL . "=== ESTUDIANTES ===" . PHP_EOL;
$students = \App\Models\User::where('role', 'student')->select('id', 'email', 'first_name', 'last_name')->get();
foreach($students as $student) {
    echo "ID: {$student->id}, Email: {$student->email}, Nombre: {$student->first_name} {$student->last_name}" . PHP_EOL;
}

echo PHP_EOL . "=== GRUPOS ===" . PHP_EOL;
$groups = \App\Models\Group::with('gradeLevel')->select('id', 'name', 'grade_level_id', 'academic_year')->get();
foreach($groups as $group) {
    echo "ID: {$group->id}, Nombre: {$group->name}, Nivel: " . ($group->gradeLevel->name ?? 'N/A') . ", Año: {$group->academic_year}" . PHP_EOL;
}

echo PHP_EOL . "=== INSCRIPCIONES ===" . PHP_EOL;
$enrollments = \App\Models\Enrollment::with(['student', 'group'])->get();
foreach($enrollments as $enrollment) {
    $studentName = $enrollment->student->first_name . ' ' . $enrollment->student->last_name;
    $groupName = $enrollment->group->name ?? 'N/A';
    echo "Estudiante: {$studentName}, Grupo: {$groupName}, Año: {$enrollment->academic_year}, Status: {$enrollment->status}" . PHP_EOL;
}

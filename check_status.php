<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Group;
use App\Models\Enrollment;
use App\Models\GradeLevel;

try {
    echo "Database Status:\n";
    echo "===============\n";
    echo "Users: " . User::count() . "\n";
    echo "Groups: " . Group::count() . "\n"; 
    echo "Grade Levels: " . GradeLevel::count() . "\n";
    echo "Enrollments: " . Enrollment::count() . "\n\n";
    
    echo "User Roles:\n";
    echo "===========\n";
    $roles = User::selectRaw('role, count(*) as count')->groupBy('role')->get();
    foreach ($roles as $role) {
        echo ucfirst($role->role) . "s: " . $role->count . "\n";
    }
    
    echo "\nRecent Enrollment:\n";
    echo "=================\n";
    $enrollment = Enrollment::with(['student', 'group', 'gradeLevel'])->latest()->first();
    if ($enrollment) {
        echo "ID: " . $enrollment->id . "\n";
        echo "Student: " . ($enrollment->student->name ?? 'N/A') . "\n";
        echo "Group: " . ($enrollment->group->name ?? 'N/A') . "\n";
        echo "Grade Level: " . ($enrollment->gradeLevel->name ?? 'N/A') . "\n";
        echo "Academic Year: " . $enrollment->academic_year . "\n";
        echo "Status: " . ($enrollment->status ?? 'pending') . "\n";
        echo "Active: " . ($enrollment->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "No enrollments found\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

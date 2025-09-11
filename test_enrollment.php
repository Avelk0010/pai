<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Group;
use App\Models\Enrollment;

try {
    echo "Testing enrollment creation...\n";
    
    // Get first student
    $student = User::where('role', 'student')->first();
    if (!$student) {
        echo "No student found. Creating test student...\n";
        $student = User::create([
            'name' => 'Test Student',
            'email' => 'test.student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);
    }
    
    // Get first group
    $group = Group::first();
    if (!$group) {
        echo "No group found. Please create a group first.\n";
        exit(1);
    }
    
    // Test enrollment creation
    $enrollment = Enrollment::create([
        'student_id' => $student->id,
        'group_id' => $group->id,
        'academic_year' => (int)date('Y')
    ]);
    
    echo "SUCCESS: Enrollment created with ID: " . $enrollment->id . "\n";
    echo "Student: " . $student->name . "\n";
    echo "Group: " . $group->name . "\n";
    echo "Academic Year: " . $enrollment->academic_year . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

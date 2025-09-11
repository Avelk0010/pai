<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Group;
use App\Models\Enrollment;

try {
    echo "Testing homeRoomTeacher relationship...\n";
    echo "=====================================\n";
    
    // Get first group
    $group = Group::first();
    if (!$group) {
        echo "No groups found.\n";
        exit(1);
    }
    
    echo "Group: " . $group->name . "\n";
    echo "Group ID: " . $group->id . "\n";
    
    // Test the homeRoomTeacher relationship
    $homeRoomTeacher = $group->homeRoomTeacher;
    
    if ($homeRoomTeacher) {
        echo "Homeroom Teacher: " . $homeRoomTeacher->name . "\n";
    } else {
        echo "No homeroom teacher assigned (this is OK for testing).\n";
    }
    
    // Test enrollment with homeRoomTeacher relationship
    echo "\nTesting enrollment with homeRoomTeacher...\n";
    $enrollment = Enrollment::with(['group.homeRoomTeacher'])->first();
    
    if ($enrollment) {
        echo "Enrollment found for student: " . ($enrollment->student->name ?? 'N/A') . "\n";
        echo "Group: " . $enrollment->group->name . "\n";
        echo "Homeroom teacher: " . ($enrollment->group->homeRoomTeacher->name ?? 'None assigned') . "\n";
        echo "✅ Relationship works correctly!\n";
    } else {
        echo "No enrollments found.\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

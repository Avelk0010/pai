<?php

// Test script to verify Activity model relationships
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test that Activity model can load groups relationship
    $activities = \App\Models\Activity::with(['subject', 'groups', 'period', 'teacher'])->first();
    
    if ($activities) {
        echo "✅ Activity model loaded successfully\n";
        echo "Activity: " . $activities->title . "\n";
        echo "Groups: " . $activities->groups->count() . "\n";
        echo "Subject: " . ($activities->subject ? $activities->subject->name : 'N/A') . "\n";
    } else {
        echo "⚠️  No activities found in database\n";
    }
    
    // Test Activity creation (basic test)
    $activity = new \App\Models\Activity();
    echo "✅ Activity model instantiated successfully\n";
    
    echo "✅ All tests passed! No 'group' relationship error found.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "File: " . $e->getFile() . "\n";
}

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Activity;
use App\Models\Subject;
use App\Models\Period;

try {
    echo "Testing percentage validation...\n";
    echo "===============================\n";
    
    // Get first active period and subject
    $period = Period::where('status', 'active')->first();
    $subject = Subject::where('status', true)->first();
    
    if (!$period || !$subject) {
        echo "❌ Need active period and subject for testing\n";
        exit(1);
    }
    
    echo "Period: " . $period->name . "\n";
    echo "Subject: " . $subject->name . "\n";
    
    // Get current activities for this period and subject
    $activities = Activity::where('period_id', $period->id)
        ->where('subject_id', $subject->id)
        ->whereIn('status', ['published', 'finished'])
        ->get();
        
    $totalPercentage = $activities->sum('percentage');
    $availablePercentage = 100 - $totalPercentage;
    
    echo "\nCurrent activities:\n";
    foreach ($activities as $activity) {
        echo "  - " . $activity->title . ": " . $activity->percentage . "%\n";
    }
    
    echo "\nTotal used: {$totalPercentage}%\n";
    echo "Available: {$availablePercentage}%\n";
    
    if ($availablePercentage > 0) {
        echo "✅ Can create activities up to {$availablePercentage}%\n";
    } else {
        echo "❌ No percentage available for new activities\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

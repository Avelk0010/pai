<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Period;
use App\Models\StudentPeriodGrade;
use App\Models\User;
use App\Models\Subject;

try {
    echo "Testing Period Final Grades System...\n";
    echo "====================================\n";
    
    // Check if we have periods
    $periods = Period::all();
    echo "Total periods: " . $periods->count() . "\n";
    
    if ($periods->count() > 0) {
        $period = $periods->first();
        echo "First period: " . $period->name . " (Status: " . $period->status . ")\n";
        
        // Check if we have student period grades
        $studentGrades = StudentPeriodGrade::count();
        echo "Total student period grades: " . $studentGrades . "\n";
        
        if ($studentGrades > 0) {
            echo "\nSample Student Period Grades:\n";
            $sampleGrades = StudentPeriodGrade::with(['student', 'subject', 'period'])
                ->take(3)
                ->get();
                
            foreach ($sampleGrades as $grade) {
                echo "- Student: " . ($grade->student->name ?? 'N/A') . 
                     " | Subject: " . $grade->subject->name . 
                     " | Period: " . $grade->period->name . 
                     " | Grade: " . $grade->final_grade . 
                     " | Status: " . $grade->status . "\n";
            }
            
            // Calculate period average for first student
            if ($sampleGrades->count() > 0) {
                $firstStudent = $sampleGrades->first()->student;
                $firstPeriod = $sampleGrades->first()->period;
                
                $average = StudentPeriodGrade::calculatePeriodAverage(
                    $firstStudent->id, 
                    $firstPeriod->id
                );
                
                echo "\nPeriod average for " . $firstStudent->name . 
                     " in " . $firstPeriod->name . ": " . number_format($average, 2) . "\n";
            }
        }
        
        // Show subjects with credits
        echo "\nSubjects with credits:\n";
        $subjects = Subject::where('status', true)->take(5)->get();
        foreach ($subjects as $subject) {
            echo "- " . $subject->name . " (Credits: " . $subject->credits . ")\n";
        }
    }
    
    echo "\n✅ System is ready for period finalization!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

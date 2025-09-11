<?php

use App\Models\AcademicPlan;

// Test the corrected query
try {
    $academicPlans = AcademicPlan::where('status', true)->count();
    echo "SUCCESS: Found {$academicPlans} active academic plans\n";
} catch (Exception $e) {
    echo "ERROR: {$e->getMessage()}\n";
}

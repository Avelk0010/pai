<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AcademicPlan;
use App\Models\Period;
use App\Models\Activity;
use App\Models\User;
use App\Models\StudentGrade;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Http\Controllers\AcademicPlanController;

echo "=== Test de Validación de Finalización de Período ===\n\n";

try {
    // Get a test period and academic plan
    $academicPlan = AcademicPlan::with('subjects')->first();
    if (!$academicPlan) {
        echo "❌ No se encontró ningún plan académico\n";
        exit;
    }

    $period = Period::where('status', 'active')->first();
    if (!$period) {
        echo "❌ No se encontró ningún período activo\n";
        exit;
    }

    echo "🔍 Probando validación para:\n";
    echo "   Plan Académico: {$academicPlan->name}\n";
    echo "   Período: {$period->name}\n\n";

    // Create controller instance and call validation method
    $controller = new AcademicPlanController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('validatePeriodCanBeFinished');
    $method->setAccessible(true);

    $result = $method->invoke($controller, $period, $academicPlan);

    echo "📊 Resultado de la validación:\n";
    echo "   Puede finalizar: " . ($result['canFinish'] ? '✅ SÍ' : '❌ NO') . "\n";
    echo "   Mensaje:\n";
    echo "   " . str_replace("\n", "\n   ", $result['message']) . "\n\n";

    if (!$result['canFinish']) {
        echo "🔍 Información adicional sobre el período:\n";
        
        // Show activities in this period
        $activities = Activity::where('period_id', $period->id)
            ->with(['subject', 'groups'])
            ->get();
        
        echo "   Actividades en el período ({$activities->count()}):\n";
        foreach ($activities as $activity) {
            echo "   • {$activity->name} - {$activity->subject->name} - {$activity->percentage}% - {$activity->status}\n";
            
            // Check grades for this activity
            $grades = StudentGrade::where('activity_id', $activity->id)->get();
            $gradedCount = StudentGrade::where('activity_id', $activity->id)
                ->whereNotNull('graded_at')->count();
            $totalStudents = User::where('role', 'student')
                ->whereHas('enrollments', function($q) use ($activity) {
                    $groupIds = $activity->groups->pluck('id');
                    $q->whereIn('group_id', $groupIds)->where('status', 'active');
                })->count();
            
            echo "     Calificados: {$gradedCount}/{$totalStudents} estudiantes\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Fin del Test ===\n";

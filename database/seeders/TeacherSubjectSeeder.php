<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\Group;

class TeacherSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some basic data if it doesn't exist
        
        // Create a teacher user if none exists
        if (!User::where('role', 'teacher')->exists()) {
            User::create([
                'first_name' => 'María',
                'last_name' => 'García',
                'email' => 'teacher@test.com',
                'password' => bcrypt('password'),
                'document' => '12345678',
                'phone' => '1234567890',
                'role' => 'teacher',
                'status' => true,
            ]);
        }

        // Create another teacher
        if (User::where('email', 'teacher2@test.com')->doesntExist()) {
            User::create([
                'first_name' => 'Carlos',
                'last_name' => 'Rodríguez',
                'email' => 'teacher2@test.com',
                'password' => bcrypt('password'),
                'document' => '87654321',
                'phone' => '0987654321',
                'role' => 'teacher',
                'status' => true,
            ]);
        }

        // Create an academic plan if none exists
        $academicPlan = \App\Models\AcademicPlan::first();
        if (!$academicPlan) {
            $gradeLevel = \App\Models\GradeLevel::first();
            if (!$gradeLevel) {
                $gradeLevel = \App\Models\GradeLevel::create([
                    'grade_name' => '6°',
                    'grade_number' => 6,
                    'education_level' => 'secondary',
                    'status' => true,
                ]);
            }

            $academicPlan = \App\Models\AcademicPlan::create([
                'name' => 'Plan Académico Básico',
                'description' => 'Plan académico para educación básica secundaria',
                'grade_level_id' => $gradeLevel->id,
                'academic_year' => date('Y'),
                'status' => true,
            ]);
        }

        // Create some subjects if none exist
        $subjectsData = [
            ['name' => 'Matemáticas', 'code' => 'MAT', 'area' => 'Mathematics'],
            ['name' => 'Español', 'code' => 'ESP', 'area' => 'Languages'],
            ['name' => 'Ciencias Naturales', 'code' => 'CN', 'area' => 'Sciences'],
            ['name' => 'Ciencias Sociales', 'code' => 'CS', 'area' => 'Social Studies'],
            ['name' => 'Inglés', 'code' => 'ENG', 'area' => 'Languages'],
        ];

        foreach ($subjectsData as $subjectData) {
            if (!Subject::where('code', $subjectData['code'])->exists()) {
                Subject::create([
                    'academic_plan_id' => $academicPlan->id,
                    'name' => $subjectData['name'],
                    'code' => $subjectData['code'],
                    'description' => 'Descripción de ' . $subjectData['name'],
                    'credits' => 3,
                    'area' => $subjectData['area'],
                    'is_mandatory' => true,
                    'status' => true,
                    'hours_per_week' => 4,
                ]);
            }
        }

        // Create a group if none exists
        if (!Group::exists()) {
            Group::create([
                'group_letter' => 'A',
                'capacity' => 30,
                'current_students' => 0,
                'classroom' => 'Aula 101',
                'status' => true,
            ]);

            Group::create([
                'group_letter' => 'B',
                'capacity' => 30,
                'current_students' => 0,
                'classroom' => 'Aula 102',
                'status' => true,
            ]);
        }

        // Create some subject assignments as examples
        $teacher = User::where('role', 'teacher')->first();
        $subjects = Subject::take(3)->get();
        $groups = Group::all();

        if ($teacher && $subjects->count() > 0 && $groups->count() > 0) {
            foreach ($subjects as $subject) {
                foreach ($groups as $group) {
                    SubjectAssignment::firstOrCreate([
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'group_id' => $group->id,
                        'academic_year' => date('Y')
                    ]);
                }
            }
        }

        $this->command->info('Teacher-Subject assignments seeder completed successfully!');
    }
}

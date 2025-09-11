<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GradeLevel;
use App\Models\Group;
use App\Models\AcademicPlan;
use App\Models\Subject;
use App\Models\Period;
use App\Models\ForumCategory;
use App\Models\LibraryResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Sistema',
            'email' => 'admin@escuela.edu',
            'password' => Hash::make('password'),
            'document' => '12345678',
            'phone' => '3001234567',
            'role' => 'admin',
            'status' => true,
        ]);

        // Create Grade Levels
        $grade11 = GradeLevel::create([
            'grade_number' => 11,
            'grade_name' => 'Grado 11',
            'status' => true,
        ]);

        // Create Groups
        $group11A = Group::create([
            'group_letter' => 'A',
            'grade_level_id' => $grade11->id,
            'academic_year' => 2024,
            'max_students' => 35,
            'status' => true,
        ]);

        $group11B = Group::create([
            'group_letter' => 'B',
            'grade_level_id' => $grade11->id,
            'academic_year' => 2024,
            'max_students' => 35,
            'status' => true,
        ]);

        // Create Academic Plan
        $academicPlan = AcademicPlan::create([
            'name' => 'Plan Académico 2024',
            'grade_level_id' => $grade11->id,
            'academic_year' => 2024,
            'status' => true,
        ]);

        // Create Subjects
        $subjects = [
            ['name' => 'Matemáticas', 'code' => 'MAT11', 'credits' => 4, 'area' => 'Mathematics'],
            ['name' => 'Español', 'code' => 'ESP11', 'credits' => 4, 'area' => 'Languages'],
            ['name' => 'Inglés', 'code' => 'ING11', 'credits' => 3, 'area' => 'Languages'],
            ['name' => 'Ciencias Naturales', 'code' => 'CN11', 'credits' => 4, 'area' => 'Sciences'],
            ['name' => 'Ciencias Sociales', 'code' => 'CS11', 'credits' => 3, 'area' => 'Social Studies'],
            ['name' => 'Educación Física', 'code' => 'EF11', 'credits' => 2, 'area' => 'Physical Education'],
            ['name' => 'Filosofía', 'code' => 'FIL11', 'credits' => 2, 'area' => 'Social Studies'],
            ['name' => 'Química', 'code' => 'QUI11', 'credits' => 3, 'area' => 'Sciences'],
            ['name' => 'Física', 'code' => 'FIS11', 'credits' => 3, 'area' => 'Sciences'],
            ['name' => 'Informática', 'code' => 'INF11', 'credits' => 2, 'area' => 'Technology'],
        ];

        foreach ($subjects as $subjectData) {
            Subject::create([
                'name' => $subjectData['name'],
                'code' => $subjectData['code'],
                'description' => 'Materia de ' . $subjectData['name'] . ' para grado 11',
                'credits' => $subjectData['credits'],
                'area' => $subjectData['area'],
                'is_mandatory' => true,
                'academic_plan_id' => $academicPlan->id,
                'status' => true,
            ]);
        }

        // Create Teachers
        $teachers = [
            ['first_name' => 'María', 'last_name' => 'González', 'email' => 'maria.gonzalez@escuela.edu', 'document' => '23456789'],
            ['first_name' => 'Carlos', 'last_name' => 'Rodríguez', 'email' => 'carlos.rodriguez@escuela.edu', 'document' => '34567890'],
            ['first_name' => 'Ana', 'last_name' => 'Martínez', 'email' => 'ana.martinez@escuela.edu', 'document' => '45678901'],
            ['first_name' => 'Luis', 'last_name' => 'López', 'email' => 'luis.lopez@escuela.edu', 'document' => '56789012'],
            ['first_name' => 'Carmen', 'last_name' => 'Hernández', 'email' => 'carmen.hernandez@escuela.edu', 'document' => '67890123'],
        ];

        foreach ($teachers as $teacherData) {
            User::create([
                'first_name' => $teacherData['first_name'],
                'last_name' => $teacherData['last_name'],
                'email' => $teacherData['email'],
                'password' => Hash::make('password'),
                'document' => $teacherData['document'],
                'phone' => '300' . rand(1000000, 9999999),
                'role' => 'teacher',
                'status' => true,
            ]);
        }

        // Create Students
        $students = [
            ['first_name' => 'Juan', 'last_name' => 'Pérez', 'email' => 'juan.perez@estudiante.edu', 'document' => '78901234'],
            ['first_name' => 'Laura', 'last_name' => 'García', 'email' => 'laura.garcia@estudiante.edu', 'document' => '89012345'],
            ['first_name' => 'Diego', 'last_name' => 'Morales', 'email' => 'diego.morales@estudiante.edu', 'document' => '90123456'],
            ['first_name' => 'Sofia', 'last_name' => 'Vargas', 'email' => 'sofia.vargas@estudiante.edu', 'document' => '01234567'],
            ['first_name' => 'Andrés', 'last_name' => 'Castro', 'email' => 'andres.castro@estudiante.edu', 'document' => '12345679'],
            ['first_name' => 'Valentina', 'last_name' => 'Ruiz', 'email' => 'valentina.ruiz@estudiante.edu', 'document' => '23456780'],
            ['first_name' => 'Santiago', 'last_name' => 'Jiménez', 'email' => 'santiago.jimenez@estudiante.edu', 'document' => '34567891'],
            ['first_name' => 'Isabella', 'last_name' => 'Torres', 'email' => 'isabella.torres@estudiante.edu', 'document' => '45678902'],
        ];

        foreach ($students as $studentData) {
            User::create([
                'first_name' => $studentData['first_name'],
                'last_name' => $studentData['last_name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'document' => $studentData['document'],
                'phone' => '300' . rand(1000000, 9999999),
                'role' => 'student',
                'status' => true,
            ]);
        }

        // Create Parents
        $parents = [
            ['first_name' => 'Roberto', 'last_name' => 'Pérez', 'email' => 'roberto.perez@padre.edu', 'document' => '55555555'],
            ['first_name' => 'Elena', 'last_name' => 'García', 'email' => 'elena.garcia@padre.edu', 'document' => '66666666'],
            ['first_name' => 'Fernando', 'last_name' => 'Morales', 'email' => 'fernando.morales@padre.edu', 'document' => '77777777'],
            ['first_name' => 'Patricia', 'last_name' => 'Vargas', 'email' => 'patricia.vargas@padre.edu', 'document' => '88888888'],
        ];

        foreach ($parents as $parentData) {
            User::create([
                'first_name' => $parentData['first_name'],
                'last_name' => $parentData['last_name'],
                'email' => $parentData['email'],
                'password' => Hash::make('password'),
                'document' => $parentData['document'],
                'phone' => '300' . rand(1000000, 9999999),
                'role' => 'parent',
                'status' => true,
            ]);
        }

        // Create Periods
        $periods = [
            ['period_number' => 1, 'start_date' => '2024-02-01', 'end_date' => '2024-04-30'],
            ['period_number' => 2, 'start_date' => '2024-05-01', 'end_date' => '2024-07-31'],
            ['period_number' => 3, 'start_date' => '2024-08-01', 'end_date' => '2024-10-31'],
            ['period_number' => 4, 'start_date' => '2024-11-01', 'end_date' => '2024-12-15'],
        ];

        foreach ($periods as $periodData) {
            Period::create([
                'academic_plan_id' => $academicPlan->id,
                'period_number' => $periodData['period_number'],
                'start_date' => $periodData['start_date'],
                'end_date' => $periodData['end_date'],
                'status' => 'active',
            ]);
        }

        // Create Forum Categories
        $forumCategories = [
            ['name' => 'Anuncios Generales', 'description' => 'Anuncios importantes de la institución', 'color' => '#DC2626', 'order' => 1],
            ['name' => 'Académico', 'description' => 'Discusiones sobre temas académicos', 'color' => '#3B82F6', 'order' => 2],
            ['name' => 'Actividades Extracurriculares', 'description' => 'Información sobre actividades fuera del aula', 'color' => '#10B981', 'order' => 3],
            ['name' => 'Soporte Técnico', 'description' => 'Ayuda con problemas técnicos de la plataforma', 'color' => '#F59E0B', 'order' => 4],
            ['name' => 'Sugerencias', 'description' => 'Sugerencias para mejorar la plataforma', 'color' => '#8B5CF6', 'order' => 5],
        ];

        foreach ($forumCategories as $categoryData) {
            ForumCategory::create([
                'name' => $categoryData['name'],
                'description' => $categoryData['description'],
                'color' => $categoryData['color'],
                'order_number' => $categoryData['order'],
            ]);
        }

        // Call LibraryResourceSeeder
        $this->call([
            LibraryResourceSeeder::class,
        ]);
    }
}

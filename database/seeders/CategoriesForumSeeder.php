<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategoriesForumSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
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

    }
}

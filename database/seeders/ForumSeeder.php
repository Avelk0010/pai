<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\User;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create forum categories
        $categories = [
            [
                'name' => 'Matemáticas',
                'description' => 'Discusiones sobre matemáticas, álgebra, geometría y cálculo',
                'color' => '#3B82F6',
                'order_number' => 1
            ],
            [
                'name' => 'Ciencias Naturales',
                'description' => 'Física, química, biología y ciencias de la tierra',
                'color' => '#10B981',
                'order_number' => 2
            ],
            [
                'name' => 'Lenguaje y Literatura',
                'description' => 'Español, literatura, redacción y comprensión lectora',
                'color' => '#F59E0B',
                'order_number' => 3
            ],
            [
                'name' => 'Ciencias Sociales',
                'description' => 'Historia, geografía, educación cívica y ciencias políticas',
                'color' => '#8B5CF6',
                'order_number' => 4
            ],
            [
                'name' => 'Idiomas Extranjeros',
                'description' => 'Inglés, francés y otros idiomas extranjeros',
                'color' => '#EF4444',
                'order_number' => 5
            ],
            [
                'name' => 'Arte y Cultura',
                'description' => 'Educación artística, música, danza y expresión cultural',
                'color' => '#EC4899',
                'order_number' => 6
            ],
            [
                'name' => 'Educación Física',
                'description' => 'Deportes, salud física y bienestar',
                'color' => '#06B6D4',
                'order_number' => 7
            ],
            [
                'name' => 'Tecnología e Informática',
                'description' => 'Informática, programación y tecnología educativa',
                'color' => '#6B7280',
                'order_number' => 8
            ],
            [
                'name' => 'Dudas Académicas',
                'description' => 'Preguntas generales y consultas sobre cualquier materia',
                'color' => '#F97316',
                'order_number' => 9
            ],
            [
                'name' => 'Anuncios',
                'description' => 'Comunicados oficiales de la institución',
                'color' => '#DC2626',
                'order_number' => 10
            ]
        ];

        foreach ($categories as $categoryData) {
            ForumCategory::create($categoryData);
        }

        // Create sample posts (only if we have users)
        $admin = User::where('role', 'admin')->first();
        $teacher = User::where('role', 'teacher')->first();
        $student = User::where('role', 'student')->first();

        if ($admin || $teacher || $student) {
            $mathCategory = ForumCategory::where('name', 'Matemáticas')->first();
            $scienceCategory = ForumCategory::where('name', 'Ciencias Naturales')->first();
            $announcementsCategory = ForumCategory::where('name', 'Anuncios')->first();

            // Sample posts
            $samplePosts = [
                [
                    'author_id' => $admin?->id ?? ($teacher?->id ?? $student->id),
                    'category_id' => $announcementsCategory->id,
                    'title' => 'Bienvenidos al Foro Académico',
                    'content' => "¡Bienvenidos al nuevo foro académico de nuestra institución!\n\nEste espacio ha sido creado para fomentar el intercambio de conocimientos, resolver dudas académicas y fortalecer nuestra comunidad educativa.\n\n**¿Cómo funciona?**\n- Todas las publicaciones deben ser aprobadas por un moderador antes de ser visibles\n- Mantén un lenguaje respetuoso y apropiado\n- Selecciona la categoría correcta para tu publicación\n- Participa activamente ayudando a tus compañeros\n\n¡Esperamos ver contenido de calidad y discusiones enriquecedoras!",
                    'is_approved' => true,
                    'approved_by' => $admin?->id,
                    'approved_at' => now(),
                    'views' => 45
                ]
            ];

            if ($teacher) {
                $samplePosts[] = [
                    'author_id' => $teacher->id,
                    'category_id' => $mathCategory->id,
                    'title' => 'Estrategias para resolver ecuaciones cuadráticas',
                    'content' => "Hola estudiantes,\n\nQuiero compartir algunas estrategias efectivas para resolver ecuaciones cuadráticas:\n\n**1. Factorización**\nCuando la ecuación se puede factorizar fácilmente.\n\n**2. Completar el cuadrado**\nÚtil para visualizar la forma vertex de la parábola.\n\n**3. Fórmula general**\nSiempre funciona: x = (-b ± √(b²-4ac)) / 2a\n\n¿Qué método prefieren y por qué? ¡Compartan sus experiencias!",
                    'is_approved' => true,
                    'approved_by' => $admin?->id,
                    'approved_at' => now()->subDays(2),
                    'views' => 23
                ];
            }

            if ($student) {
                $samplePosts[] = [
                    'author_id' => $student->id,
                    'category_id' => $scienceCategory->id,
                    'title' => '¿Alguien puede explicar la fotosíntesis?',
                    'content' => "Hola compañeros,\n\nEstoy estudiando para el examen de biología y tengo algunas dudas sobre la fotosíntesis.\n\n¿Podrían ayudarme a entender mejor:\n- Las fases lumínica y oscura\n- La función de los cloroplastos\n- La importancia del ATP en el proceso\n\n¡Cualquier ayuda será muy apreciada!",
                    'is_approved' => false, // Pending approval
                    'views' => 0
                ];
            }

            foreach ($samplePosts as $postData) {
                $post = ForumPost::create($postData);
                
                // Add sample comments to approved posts
                if ($post->is_approved && $student && $teacher) {
                    if ($post->title === 'Estrategias para resolver ecuaciones cuadráticas') {
                        ForumComment::create([
                            'post_id' => $post->id,
                            'author_id' => $student->id,
                            'content' => '¡Excelente explicación profesor! Yo prefiero la factorización cuando es posible, me parece más intuitiva.',
                            'is_approved' => true,
                            'approved_by' => $admin?->id,
                            'approved_at' => now()->subDay()
                        ]);
                    }
                }
            }
        }

        $this->command->info('Forum categories and sample data created successfully!');
    }
}

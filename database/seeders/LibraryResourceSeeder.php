<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibraryResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            // Libros de Matemáticas
            [
                'title' => 'Álgebra Lineal Básica',
                'author' => 'María José García',
                'isbn' => '978-84-376-0123-4',
                'resource_type' => 'book',
                'total_copies' => 5,
                'available_copies' => 5,
                'location' => 'Estante A1-Matemáticas',
                'description' => 'Manual completo de álgebra lineal para estudiantes de educación media. Incluye ejercicios prácticos y ejemplos paso a paso.',
                'status' => true,
            ],
            [
                'title' => 'Geometría Analítica',
                'author' => 'Carlos Rodríguez López',
                'isbn' => '978-84-376-0145-8',
                'resource_type' => 'book',
                'total_copies' => 4,
                'available_copies' => 4,
                'location' => 'Estante A1-Matemáticas',
                'description' => 'Estudio completo de la geometría analítica con aplicaciones en el plano y el espacio.',
                'status' => true,
            ],
            [
                'title' => 'Cálculo Diferencial e Integral',
                'author' => 'Ana Martínez Silva',
                'isbn' => '978-84-376-0167-2',
                'resource_type' => 'book',
                'total_copies' => 6,
                'available_copies' => 6,
                'location' => 'Estante A2-Matemáticas',
                'description' => 'Introducción al cálculo diferencial e integral con ejercicios resueltos y propuestos.',
                'status' => true,
            ],
            
            // Libros de Ciencias
            [
                'title' => 'Química Orgánica Fundamental',
                'author' => 'Dr. Pedro Fernández',
                'isbn' => '978-84-376-0189-4',
                'resource_type' => 'book',
                'total_copies' => 5,
                'available_copies' => 5,
                'location' => 'Estante B1-Ciencias',
                'description' => 'Manual de química orgánica que cubre los conceptos fundamentales y reacciones principales.',
                'status' => true,
            ],
            [
                'title' => 'Biología Molecular',
                'author' => 'Dra. Laura González',
                'isbn' => '978-84-376-0201-3',
                'resource_type' => 'book',
                'total_copies' => 4,
                'available_copies' => 4,
                'location' => 'Estante B2-Ciencias',
                'description' => 'Estudio detallado de la biología molecular moderna, incluyendo ADN, ARN y proteínas.',
                'status' => true,
            ],
            [
                'title' => 'Física Cuántica para Principiantes',
                'author' => 'Prof. Miguel Santos',
                'isbn' => '978-84-376-0223-5',
                'resource_type' => 'book',
                'total_copies' => 3,
                'available_copies' => 3,
                'location' => 'Estante B3-Ciencias',
                'description' => 'Introducción accesible a los conceptos básicos de la física cuántica.',
                'status' => true,
            ],
            
            // Libros de Historia y Sociales
            [
                'title' => 'Historia de Colombia Siglo XX',
                'author' => 'Historiador Juan Pérez',
                'isbn' => '978-84-376-0245-7',
                'resource_type' => 'book',
                'total_copies' => 6,
                'available_copies' => 6,
                'location' => 'Estante C1-Historia',
                'description' => 'Análisis completo de los acontecimientos más importantes de Colombia en el siglo XX.',
                'status' => true,
            ],
            [
                'title' => 'Geografía de América Latina',
                'author' => 'Dra. Carmen López',
                'isbn' => '978-84-376-0267-9',
                'resource_type' => 'book',
                'total_copies' => 5,
                'available_copies' => 5,
                'location' => 'Estante C2-Geografía',
                'description' => 'Estudio detallado de la geografía física y humana de América Latina.',
                'status' => true,
            ],
            
            // Literatura
            [
                'title' => 'Cien Años de Soledad',
                'author' => 'Gabriel García Márquez',
                'isbn' => '978-84-376-0289-1',
                'resource_type' => 'book',
                'total_copies' => 8,
                'available_copies' => 8,
                'location' => 'Estante D1-Literatura',
                'description' => 'Obra maestra del realismo mágico latinoamericano, premio Nobel de Literatura.',
                'status' => true,
            ],
            [
                'title' => 'Don Quijote de la Mancha',
                'author' => 'Miguel de Cervantes',
                'isbn' => '978-84-376-0311-9',
                'resource_type' => 'book',
                'total_copies' => 6,
                'available_copies' => 6,
                'location' => 'Estante D1-Literatura',
                'description' => 'Clásico de la literatura española y universal. Edición anotada para estudiantes.',
                'status' => true,
            ],
            
            // Revistas
            [
                'title' => 'National Geographic en Español',
                'author' => 'National Geographic Society',
                'isbn' => null,
                'resource_type' => 'magazine',
                'total_copies' => 3,
                'available_copies' => 3,
                'location' => 'Mesa de Revistas',
                'description' => 'Revista mensual de geografía, historia natural, ciencia y cultura mundial.',
                'status' => true,
            ],
            [
                'title' => 'Scientific American',
                'author' => 'Scientific American Inc.',
                'isbn' => null,
                'resource_type' => 'magazine',
                'total_copies' => 2,
                'available_copies' => 2,
                'location' => 'Mesa de Revistas',
                'description' => 'Revista de divulgación científica con artículos sobre los últimos avances.',
                'status' => true,
            ],
            
            // Recursos Digitales
            [
                'title' => 'Curso Interactivo de Programación Python',
                'author' => 'Instituto Tecnológico',
                'isbn' => null,
                'resource_type' => 'digital',
                'total_copies' => 20,
                'available_copies' => 20,
                'location' => 'Plataforma Digital',
                'description' => 'Curso completo de programación en Python con ejercicios interactivos y proyectos.',
                'status' => true,
            ],
            [
                'title' => 'Base de Datos de Química',
                'author' => 'ChemSpider',
                'isbn' => null,
                'resource_type' => 'digital',
                'total_copies' => 15,
                'available_copies' => 15,
                'location' => 'Acceso Online',
                'description' => 'Base de datos química con más de 100 millones de estructuras químicas.',
                'status' => true,
            ],
            
            // Multimedia
            [
                'title' => 'Documentales de Historia Universal - Set 1',
                'author' => 'BBC Documentary',
                'isbn' => null,
                'resource_type' => 'multimedia',
                'total_copies' => 4,
                'available_copies' => 4,
                'location' => 'Estante Multimedia',
                'description' => 'Colección de documentales sobre civilizaciones antiguas y eventos históricos importantes.',
                'status' => true,
            ],
            [
                'title' => 'Atlas Interactivo de Biología',
                'author' => 'Educational Software Co.',
                'isbn' => null,
                'resource_type' => 'multimedia',
                'total_copies' => 3,
                'available_copies' => 3,
                'location' => 'Estante Multimedia',
                'description' => 'Software educativo con modelos 3D interactivos del cuerpo humano y sistemas biológicos.',
                'status' => true,
            ],
        ];

        foreach ($resources as $resource) {
            \App\Models\LibraryResource::create($resource);
        }
    }
}

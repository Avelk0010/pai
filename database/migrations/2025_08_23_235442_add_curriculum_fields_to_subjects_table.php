<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Contenido curricular y cronograma
            $table->longText('curriculum_content')->nullable()->comment('Cronograma completo y contenidos temáticos');
            
            // Lista estructurada de temas por períodos
            $table->json('topics')->nullable()->comment('Estructura JSON con temas por períodos');
            
            // Objetivos de aprendizaje
            $table->text('objectives')->nullable()->comment('Objetivos generales y específicos');
            
            // Metodología de enseñanza
            $table->text('methodology')->nullable()->comment('Metodología y estrategias didácticas');
            
            // Criterios de evaluación
            $table->text('evaluation_criteria')->nullable()->comment('Criterios y métodos de evaluación');
            
            // Recursos necesarios
            $table->json('resources')->nullable()->comment('Libros, materiales y recursos necesarios');
            
            // Prerrequisitos
            $table->text('prerequisites')->nullable()->comment('Conocimientos previos requeridos');
            
            // Intensidad horaria semanal
            $table->integer('hours_per_week')->default(1)->comment('Horas académicas por semana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn([
                'curriculum_content',
                'topics',
                'objectives',
                'methodology',
                'evaluation_criteria',
                'resources',
                'prerequisites',
                'hours_per_week'
            ]);
        });
    }
};

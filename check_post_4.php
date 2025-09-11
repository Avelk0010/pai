<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ForumPost;

echo "=== Verificación de Publicación ID 4 ===\n\n";

try {
    // Check if post with ID 4 exists
    $post = ForumPost::find(4);
    
    if ($post) {
        echo "✅ Publicación ID 4 encontrada:\n";
        echo "   Título: {$post->title}\n";
        echo "   Autor: {$post->author->name}\n";
        echo "   Categoría: {$post->category->name}\n";
        echo "   Estado: " . ($post->is_approved ? '✅ Aprobado' : '⏳ Pendiente') . "\n";
        echo "   Vistas: {$post->views}\n";
        echo "   Creado: {$post->created_at}\n";
    } else {
        echo "❌ No existe publicación con ID 4\n";
        
        // Show available posts
        $posts = ForumPost::all();
        echo "\n📝 Publicaciones disponibles:\n";
        foreach ($posts as $p) {
            $status = $p->is_approved ? '✅' : '⏳';
            echo "   ID {$p->id}: {$p->title} - {$status}\n";
        }
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la verificación ===\n";

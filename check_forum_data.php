<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\User;

echo "=== Verificación de Datos del Foro ===\n\n";

try {
    // Check categories
    $categories = ForumCategory::all();
    echo "📁 Categorías del foro (" . $categories->count() . "):\n";
    if ($categories->count() > 0) {
        foreach ($categories as $category) {
            echo "  - {$category->name}: {$category->description}\n";
        }
    } else {
        echo "  No hay categorías creadas\n";
    }
    
    echo "\n";
    
    // Check posts
    $posts = ForumPost::with(['author', 'category'])->get();
    echo "📝 Publicaciones del foro (" . $posts->count() . "):\n";
    if ($posts->count() > 0) {
        foreach ($posts as $post) {
            $approved = $post->is_approved ? '✅ Aprobado' : '⏳ Pendiente';
            echo "  - {$post->title}\n";
            echo "    Autor: {$post->author->name}\n";
            echo "    Categoría: {$post->category->name}\n";
            echo "    Estado: {$approved}\n";
            echo "    Vistas: {$post->views}\n\n";
        }
    } else {
        echo "  No hay publicaciones creadas\n";
    }
    
    echo "\n";
    
    // Check users
    $users = User::all();
    echo "👥 Usuarios disponibles (" . $users->count() . "):\n";
    foreach ($users as $user) {
        echo "  - {$user->name} ({$user->email}) - Rol: {$user->role}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== Fin de la verificación ===\n";

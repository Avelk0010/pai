<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ForumPost;
use App\Models\User;

// Buscar usuario con posts
$users = User::whereHas('forumPosts')->with('forumPosts.category')->take(3)->get();

echo "=== USUARIOS CON POSTS ===\n";
foreach ($users as $user) {
    echo "👤 Usuario: {$user->name} (ID: {$user->id}, Role: {$user->role})\n";
    echo "   Posts:\n";
    foreach ($user->forumPosts as $post) {
        echo "   - ID: {$post->id} | {$post->title} | Categoría: {$post->category->name} | Aprobado: " . ($post->is_approved ? 'Sí' : 'No') . "\n";
    }
    echo "\n";
}

// Crear un post de prueba si no hay posts
if ($users->isEmpty() || $users->first()->forumPosts->isEmpty()) {
    echo "=== CREANDO POST DE PRUEBA ===\n";
    $user = User::where('role', 'teacher')->first() ?? User::first();
    $category = \App\Models\ForumCategory::first();
    
    $testPost = ForumPost::create([
        'author_id' => $user->id,
        'category_id' => $category->id,
        'title' => 'Post de prueba para edición',
        'content' => 'Este es un contenido de prueba para probar la funcionalidad de edición.',
        'is_approved' => false,
    ]);
    
    echo "✅ Post creado: ID {$testPost->id} para usuario {$user->name}\n";
}

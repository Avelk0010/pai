<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ForumPost;
use App\Models\User;
use App\Models\ForumComment;

// Verificar post ID 2
echo "=== VERIFICANDO POST ID 2 ===\n";

$post = ForumPost::with(['author', 'category', 'approvedComments.author'])->find(2);

if (!$post) {
    echo "❌ Post ID 2 no encontrado\n";
    exit;
}

echo "✅ Post encontrado: {$post->title}\n";
echo "📝 Author ID: {$post->author_id}\n";

// Verificar autor del post
if ($post->author) {
    echo "👤 Autor del post: {$post->author->name} (Role: {$post->author->role})\n";
} else {
    echo "❌ No se pudo cargar el autor del post\n";
    
    // Verificar si el usuario existe
    $user = User::find($post->author_id);
    if ($user) {
        echo "📋 Usuario existe en DB: {$user->name} (Role: {$user->role})\n";
    } else {
        echo "❌ Usuario no existe en DB con ID: {$post->author_id}\n";
    }
}

// Verificar comentarios
echo "\n=== COMENTARIOS ===\n";
$comments = $post->approvedComments;

if ($comments->count() > 0) {
    foreach ($comments as $comment) {
        echo "💬 Comentario ID {$comment->id}:\n";
        echo "   - Author ID: {$comment->author_id}\n";
        
        if ($comment->author) {
            echo "   - Autor: {$comment->author->name} (Role: {$comment->author->role})\n";
        } else {
            echo "   - ❌ No se pudo cargar el autor del comentario\n";
            
            // Verificar si el usuario existe
            $commentUser = User::find($comment->author_id);
            if ($commentUser) {
                echo "   - 📋 Usuario existe en DB: {$commentUser->name} (Role: {$commentUser->role})\n";
            } else {
                echo "   - ❌ Usuario no existe en DB con ID: {$comment->author_id}\n";
            }
        }
        echo "\n";
    }
} else {
    echo "No hay comentarios aprobados\n";
}

// Verificar todos los comentarios (incluyendo no aprobados)
echo "\n=== TODOS LOS COMENTARIOS (incluyendo no aprobados) ===\n";
$allComments = ForumComment::where('post_id', 2)->with('author')->get();

foreach ($allComments as $comment) {
    echo "💬 Comentario ID {$comment->id} (Aprobado: " . ($comment->is_approved ? 'Sí' : 'No') . "):\n";
    echo "   - Author ID: {$comment->author_id}\n";
    
    if ($comment->author) {
        echo "   - Autor: {$comment->author->name} (Role: {$comment->author->role})\n";
    } else {
        echo "   - ❌ No se pudo cargar el autor del comentario\n";
    }
    echo "\n";
}

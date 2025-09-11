<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Verificar usuario ID 2
echo "=== USUARIO ID 2 ===\n";
$user = User::find(2);

if ($user) {
    echo "Usuario encontrado:\n";
    print_r($user->toArray());
} else {
    echo "Usuario no encontrado\n";
}

// Verificar usuario ID 7
echo "\n=== USUARIO ID 7 ===\n";
$user7 = User::find(7);

if ($user7) {
    echo "Usuario encontrado:\n";
    print_r($user7->toArray());
} else {
    echo "Usuario no encontrado\n";
}

// Verificar estructura de tabla users
echo "\n=== ESTRUCTURA TABLA USERS ===\n";
$users = User::select('id', 'name', 'email', 'role')->take(3)->get();
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: '{$u->name}', Email: {$u->email}, Role: {$u->role}\n";
}

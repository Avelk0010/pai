<?php

require_once 'vendor/autoload.php';

use App\Models\LibraryResource;
use Illuminate\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

// Bootstrap Laravel
$app = new Application(dirname(__FILE__));
$app->singleton('events', function() {
    return new Dispatcher();
});

$app->bind('config', function() {
    return [
        'database' => [
            'default' => 'mysql',
            'connections' => [
                'mysql' => [
                    'driver' => 'mysql',
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'database' => 'pai2',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ],
            ],
        ],
    ];
});

Container::setInstance($app);

// Set up Eloquent
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'pai2',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test the toggle functionality
echo "Probando funcionalidad de toggle...\n\n";

// Get first resource
$resource = LibraryResource::first();
if ($resource) {
    echo "Recurso encontrado: {$resource->title}\n";
    echo "Estado actual: " . ($resource->status ? 'Activo' : 'Inactivo') . " (valor: {$resource->status})\n";
    
    // Toggle the status
    $newStatus = !$resource->status;
    $resource->status = (bool) $newStatus;
    $resource->save();
    
    echo "Nuevo estado: " . ($resource->status ? 'Activo' : 'Inactivo') . " (valor: {$resource->status})\n";
    
    // Verify the change was saved
    $resource->refresh();
    echo "Estado verificado: " . ($resource->status ? 'Activo' : 'Inactivo') . " (valor: {$resource->status})\n";
} else {
    echo "No se encontraron recursos en la base de datos.\n";
}

echo "\nPrueba completada.\n";

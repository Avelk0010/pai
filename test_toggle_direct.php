<?php

require_once 'bootstrap/app.php';

use App\Models\LibraryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\LibraryController;
use Illuminate\Support\Facades\Auth;

// Simulate admin login
$adminUser = App\Models\User::where('role', 'admin')->first();
Auth::login($adminUser);

echo "Testing toggle functionality...\n\n";

// Get a resource to test
$resource = LibraryResource::first();

if ($resource) {
    echo "Resource: {$resource->title}\n";
    echo "Current status: " . ($resource->status ? 'Active' : 'Inactive') . "\n";
    
    // Create a mock request for toggle
    $request = new Request();
    $request->merge([
        'status' => $resource->status ? '0' : '1',
        'title' => 'dummy',
        '_method' => 'PATCH'
    ]);
    
    // Test the controller method
    $controller = new LibraryController();
    
    try {
        $response = $controller->updateResource($request, $resource);
        echo "Toggle executed successfully!\n";
        
        // Refresh the resource to see the change
        $resource->refresh();
        echo "New status: " . ($resource->status ? 'Active' : 'Inactive') . "\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "No resources found.\n";
}

echo "\nTest completed.\n";

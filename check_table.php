<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'subject_assignments' ORDER BY ordinal_position");

echo "Columnas de subject_assignments:\n";
foreach ($columns as $column) {
    echo "- {$column->column_name} ({$column->data_type})\n";
}

echo "\n";

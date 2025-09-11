<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

echo "Testing Group name accessor..." . PHP_EOL;

$groups = App\Models\Group::with('gradeLevel')->get();

foreach ($groups as $group) {
    echo "Group ID: " . $group->id . PHP_EOL;
    echo "Group Letter: " . $group->group_letter . PHP_EOL;
    echo "Grade Level: " . ($group->gradeLevel ? $group->gradeLevel->name : 'No Grade Level') . PHP_EOL;
    echo "Name accessor: " . $group->name . PHP_EOL;
    echo "Full Name accessor: " . $group->full_name . PHP_EOL;
    echo "---" . PHP_EOL;
}

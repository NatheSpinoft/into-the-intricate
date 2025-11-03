<?php
//main.php
// Load all PHP files in lib folder
foreach (glob(__DIR__ . "/lib/*.php") as $file) {
    require_once $file;
}

// Get all declared classes
$allClasses = get_declared_classes();

// Filter classes from the lib namespace
$planeClasses = array_filter($allClasses, function($class) {
    return str_starts_with($class, "lib\\");
});

foreach ($planeClasses as $planeClass) {
    $plane = new $planeClass(); // Instantiate dynamically
    echo "Model: "; $plane->ModelType(); echo PHP_EOL;
    echo "Passengers: "; $plane->PassengersLimit(); echo PHP_EOL;
    echo "Altitude: "; $plane->Altitude(); echo PHP_EOL;
    echo "Fuel: "; $plane->GasLimit(); echo PHP_EOL;
    echo "------------------------" . PHP_EOL;
}

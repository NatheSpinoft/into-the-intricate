<?php
// api.php
header('Content-Type: application/json');

require_once __DIR__ . '/class.php';
require_once __DIR__ . '/src/VWRSeries.php';

use src\VWRSeries;

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'createCar':
        $carMake = $_POST['make'] ?? 'VW';
        $car = new VWRSeries();
        
        $car->SetCarMake($carMake);
        $car->SetCarModel($_POST['model'] ?? 'R Series');
        $car->SetModelGasLimit($_POST['gasLimit'] ?? 60);
        $car->SetKmLimit($_POST['kmLimit'] ?? 100000);
        $car->SetFeatures($_POST['features'] ?? 'Standard Package');
        
        echo json_encode([
            'success' => true,
            'data' => [
                'make' => $car->GetCarMake(),
                'model' => $car->GetCarModel(),
                'gasLimit' => $car->GetModelGasLimit(),
                'kmLimit' => $car->GetKmLimit(),
                'features' => $car->GetFeatures()
            ]
        ]);
        break;
        
    case 'getCarInfo':
        $car = new VWRSeries();
        $car->SetCarMake('Volkswagen');
        $car->SetCarModel('R Series');
        $car->SetModelGasLimit(60);
        $car->SetKmLimit(100000);
        $car->SetFeatures('Sport Package, Navigation, Leather Seats');
        
        echo json_encode([
            'success' => true,
            'data' => [
                'make' => $car->GetCarMake(),
                'model' => $car->GetCarModel(),
                'gasLimit' => $car->GetModelGasLimit(),
                'kmLimit' => $car->GetKmLimit(),
                'features' => $car->GetFeatures()
            ]
        ]);
        break;
        
    default:
        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
}
?>
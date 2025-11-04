<?php
//VWRSeries.php
namespace src;

require_once __DIR__ . "/../class.php";

class VWRSeries implements \CarMake{
    private $make;
    private $model;
    private $GasLimit;
    private $km;
    private $features;

    public function SetCarMake($make){
        $this->make = $make;
    }
    
    public function GetCarMake() {
        return $this->make;
    }

    public function SetCarModel($model){
        $this->model = $model;
    }
    
    public function GetCarModel(){
        return $this->model;
    }

    public function SetModelGasLimit($GasLimit){
        $this->GasLimit = $GasLimit;
    }
    
    public function GetModelGasLimit(){
        return $this->GasLimit;
    }

    public function SetKmLimit($km){
        $this->km = $km;
    }
    
    public function GetKmLimit(){
        return $this->km;
    }

    public function SetFeatures($features){
        $this->features = $features;
    }
    
    public function GetFeatures(){
        return $this->features;
    }
}
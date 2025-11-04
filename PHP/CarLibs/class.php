<?php
//class.php
interface CarMake{
    public function SetCarMake($make);
    public function GetCarMake();

    public function SetCarModel($model);
    public function GetCarModel();

    public function SetModelGasLimit($GasLimit);
    public function GetModelGasLimit();

    public function SetKmLimit($km);
    public function GetKmLimit();

    public function SetFeatures($features);
    public function GetFeatures();

}
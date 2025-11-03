<?php
//Boeing-737
namespace lib;
require_once __DIR__ . "/../Plane.php";

class boeing737 implements \Plane{
    function ModelType(){
        echo "Boeing 737";
    }
    function PassengersLimit(){
        echo "149";
    }
    function Altitude(){
        echo "41,000";
    }
    function GasLimit() {
        echo "23,800 Liters";
    }
}
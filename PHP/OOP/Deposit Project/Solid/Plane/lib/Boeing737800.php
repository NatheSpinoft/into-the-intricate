<?php
//Boeing 737-800

namespace lib;

require_once __DIR__ . "/../Plane.php";

class boeing737800 implements \Plane{
    function ModelType(){
        echo "Boeing 737 - 800";
    }
    function PassengersLimit(){
        echo "189";
    }
    function Altitude(){
        echo "65,000";
    }
    function GasLimit() {
        echo "26,022 Liters";
    }
}
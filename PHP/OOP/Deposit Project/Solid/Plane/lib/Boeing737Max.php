<?php
// lib/Boeing737800.php
namespace lib;

require_once __DIR__ . "/../Plane.php";

class Boeing737Max implements \Plane {
    public function ModelType() {
        echo "Boeing 737 - 800";
    }
    public function PassengersLimit() {
        echo "210";
    }
    public function Altitude() {
        echo "41,000 ft";
    }
    public function GasLimit() {
        echo "25,816 Liters";
    }
}

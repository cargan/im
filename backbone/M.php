<?php
require("../vendors/dijkstra/Dijkstra.php");

function generateId($start, $end) {
    $length = rand(70, 100);
    return bin2hex(openssl_random_pseudo_bytes($length));
}


$Path = new Path($g);

class Machine
{
    private $__id;

    public function __construct() {
        $this->__id = generateId(15, 20);
    }
    public function getId() {
        return $this->__id;
    }

    public function move($edge) {
        //do something
        return true;
    }
}

//Movement
class Location extends Path
{
    private $__location;

    public function __construct ($location) {
        $this->__location = $location;
    }

    protected function _getLocation() {
        return $this->__location;
    }

    protected function _setLocation($location) {
        $this->__location = $location;
    }

    protected function _getPath($to) {
       return $this->getPath($this->_getLocation(), $to);
    }
}


class MachineLocation extends Location
{
    private $__id;
    private $__Machine;

    public function __construct (Machine $Machine, $Location) {
        $this->__Machine = $Machine;
        $this->__id = generateId(7, 11);
        parent::__construct($Location);
    }

    public function getId() {
        return $this->__id;
    }

    public function getLocation() {
        return $this->_getLocation();
    }

    public function move($edge) {
        $path = $this->_getPath($edge);
        foreach ($path as $p) {
            if ($this->__Machine->move($edge)) {
                $this->_setLocation($edge);
            } else {
                throw new Exception ('Could not move');
            }
        }
    }

    public function _ggetPath($edge) {
        return parent::_getPath($edge);
    }

    public function __toString() {
        return 'ML for ' . $this->__Machine->getId();
    }
}

class MachineLocationManager {
    private $__MachineLocations;
    private $__Base;

    public function __construct($Base) {
        $this->__Base = $Base;
        $this->__MachineLocations = new SplObjectStorage();
    }

    public function register(MachineLocation $MachineLocation) {
        $this->__MachineLocations->attach($MachineLocation);
    }

    public function move($location) {
        foreach ($this->__MachineLocations as $ML) {
            $ML->move($location);
        }
    }

    public function returnToBase() {
        return $this->move($this->getBaseLocation());
    }

    public function moveMTo($number, $location) {
        if (!$number || !$location) {
            throw new Exception ('move to data provided incorectly');
        }
        $paths = [];
        foreach ($this->__MachineLocations as $key=>$ML) {
            $paths[$ML->getId()] = $ML->_ggetPath($location );
        }
        $pc = [];
        foreach ($paths as $k=>$p) {
            $pc[$k] = count($p);
        }
        arsort($pc);
        $items = array_slice($pc, 0, $number, true);
        foreach ($this->__MachineLocations as $ML) {
            if (isset($items[$ML->getId()])) {
                $ML->move($location);
            }
        }
    }

    private function getBaseLocation() {
        return $this->__Base;
    }
}


//there are more algorythoms; this is indirect appproach a->b != b->a

$Machine1 = new Machine();
$Machine2 = new Machine();
$Machine3 = new Machine();

$Location = 'a';
$Base = 'b';

$MachineLocationManager = new MachineLocationManager($Base);

$MachineLocation1 = new MachineLocation ($Machine1, $Location);
$MachineLocation2 = new MachineLocation ($Machine2, $Location);
$MachineLocation3 = new MachineLocation ($Machine3, $Location);

$MachineLocationManager->register($MachineLocation1);
$MachineLocationManager->register($MachineLocation2);
$MachineLocationManager->register($MachineLocation3);

$MachineLocationManager->move('i');

// print_r(array(
//     $MachineLocation1->getLocation(),
//     $MachineLocation2->getLocation(),
//     $MachineLocation3->getLocation()
// ));
//
// $MachineLocationManager->returnToBase();
//
// // $MachineLocationManager->moveMTo(2, 'g');
//
// print_r(array(
//     $MachineLocation1->getLocation(),
//     $MachineLocation2->getLocation(),
//     $MachineLocation3->getLocation()
// ));

$MachineLocationManager->move('b');

print_r(array(
    $MachineLocation1->getLocation(),
    $MachineLocation2->getLocation(),
    $MachineLocation3->getLocation()
));



$MachineLocationManager->moveMTo(2, 'g');
print_r(array(
    $MachineLocation1->getLocation(),
    $MachineLocation2->getLocation(),
    $MachineLocation3->getLocation()
));

$MachineLocationManager->moveMTo(1, 'a');

print_r(array(
    $MachineLocation1->getLocation(),
    $MachineLocation2->getLocation(),
    $MachineLocation3->getLocation()
));



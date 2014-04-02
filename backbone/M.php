<?php
require("../vendors/dijkstra/Dijkstra.php");

$Path = new Path($g);

class Machine
{
    private $__id;

    public function __construct() {
        $this->__id = $this->generateId();
    }

    private function generateId() {
        $length = rand(70, 100);
        return bin2hex(openssl_random_pseudo_bytes($length));
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
       return $this->getPath('a', 'i');
    }
}


class MachineLocation extends Location
{
    private $__Machine;

    public function __construct (Machine $Machine, $Location) {
        $this->__Machine = $Machine;
        parent::__construct($Location);
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
}

class MachineLocationManager {
    private $__MachineLocations;
    private $__Base;

    public function __construct($Base) {
        $this->__Base = $Base;
    }

    public function register(MachineLocation $MachineLocation) {
        $this->__MachineLocations[] = $MachineLocation;
    }

    public function move($location) {
        foreach ($this->__MachineLocations as $ML) {
            $ML->move($location);
        }
    }

    public function returnToBase() {
        return $this->move($this->getBaseLocation());
    }

    public function moveMTo($location) {

        foreach ($this->__MachineLocations as $ML) {
            print_r($ML->_ggetPath($location ));
        }
        // $this->getClosest($location);
    }

    private function getBaseLocation() {
        return $this->__Base;
    }
}


//there are more algorythoms; this is indirect appproach a->b != b->a
// $result = $Path->getPath('a', 'i');
// print_r($result );
$machines = [];
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

// $path = $g->getPath($MachineLocation1->getLocation(), 'g');
// $MachineLocation1->move($path);
//
// $path = $g->getPath($MachineLocation2->getLocation(), 'f');
// $MachineLocation2->move($path);
//
// $path = $g->getPath($MachineLocation3->getLocation(), 'i');
// $MachineLocation3->move($path);

print_r(array(
    $MachineLocation1->getLocation(),
    $MachineLocation2->getLocation(),
    $MachineLocation3->getLocation()
));

$MachineLocationManager->returnToBase();

$MachineLocationManager->moveMTo(2, 'g');

print_r(array(
    $MachineLocation1->getLocation(),
    $MachineLocation2->getLocation(),
    $MachineLocation3->getLocation()
));


// $MachineGrid1 = new MachineGrid($Machine1, 4, 6);
// $MachineGrid2 = new MachineGrid($Machine2, 5, 6);
//
// $MCommandHub = new MCommandHub();
// $MCommandHubId = $MCommandHub->register(
//     $Machine1->getId(),
//     $MachineGrid1->getCoordiantes()
// );
// $Machine1->setCommandHubId($MCommandHubId);
// $MCommandHubId = $MCommandHub->register(
//     $Machine2->getId(),
//     $MachineGrid2->getCoordiantes()
// );
// $Machine2->setCommandHubId($MCommandHubId);
//
// print_r(array($MCommandHub, $Machine1, $Machine2));
// print_r(array($Machine1->getId()));

// for($i=0;$i<3;$i++) {
//     $machines[] = new Machine();
// }

// print_r( array($Machine, $MachineGrid ));
// for($i=0;$i<3;$i++) {
//     $MachineGrid->move('up');
//     print_r( $MachineGrid->getPosition() );
// }
// for($i=0;$i<3;$i++) {
//     $MachineGrid->move('left');
//     print_r( $MachineGrid->getPosition() );
// }

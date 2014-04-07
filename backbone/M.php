<?php
require("../vendors/dijkstra/Dijkstra.php");

function generateId($start, $end) {
    $length = rand(70, 100);
    return bin2hex(openssl_random_pseudo_bytes($length));
}


$Path = new Path($g);

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


class Machine
    extends Location
{
    private $__id;

    public function __construct($Location) {
        parent::__construct($Location);
        $this->__id = generateId(15, 20);
    }
    public function getId() {
        return $this->__id;
    }

    public function move($edge) {
        $path = $this->_getPath($edge);
        foreach ($path as $p) {
            if (!$this->__move($edge)) {
                throw new Exception ('Could not move');
            }
        }
    }

    private function __move($edge) {
        $this->_setLocation($edge);
        //make phisical move
        return true;
    }

    public function getLocation() {
        return $this->_getLocation();
    }

     public function getLPath($edge) {
         return parent::_getPath($edge);
     }

}

// class MachineLocation extends Location
// {
//     private $__id;
//     private $__Machine;
//
//     public function __construct (Machine $Machine, $Location) {
//         $this->__Machine = $Machine;
//         $this->__id = generateId(7, 11);
//         parent::__construct($Location);
//     }
//
//     public function getId() {
//         return $this->__id;
//     }
//
//     public function getLocation() {
//         return $this->_getLocation();
//     }
//
//     public function move($edge) {
//         $path = $this->_getPath($edge);
//         foreach ($path as $p) {
//             if ($this->__Machine->move($edge)) {
//                 $this->_setLocation($edge);
//             } else {
//                 throw new Exception ('Could not move');
//             }
//         }
//     }
//
//     public function getLPath($edge) {
//         return parent::_getPath($edge);
//     }
// }
//
class MachineManager {
    private $__Machines;
    private $__Base;

    public function __construct($Base) {
        $this->__Base = $Base;
        $this->__Machines = new SplObjectStorage();
    }

    public function register(Machine $Machine) {
        $this->__Machines->attach($Machine);
    }

    public function move($location) {
        foreach ($this->__Machines as $ML) {
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
        foreach ($this->__Machines as $key=>$ML) {
            $paths[$ML->getId()] = $ML->getLPath($location );
        }
        $pc = [];
        foreach ($paths as $k=>$p) {
            $pc[$k] = count($p);
        }
        arsort($pc);
        $items = array_slice($pc, 0, $number, true);
        foreach ($this->__Machines as $ML) {
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

$Location = 'a';

$Machine1 = new Machine($Location);
$Machine2 = new Machine($Location);
$Machine3 = new Machine($Location);

$Base = 'b';

$MachineManager = new MachineManager($Base);

$MachineManager->register($Machine1);
$MachineManager->register($Machine2);
$MachineManager->register($Machine3);

$MachineManager->move('i');

print_r(array(
    $Machine1->getLocation(),
    $Machine2->getLocation(),
    $Machine3->getLocation()
));



$MachineManager->moveMTo(2, 'g');
print_r(array(
    $Machine1->getLocation(),
    $Machine2->getLocation(),
    $Machine3->getLocation()
));

$MachineManager->moveMTo(1, 'a');

print_r(array(
    $Machine1->getLocation(),
    $Machine2->getLocation(),
    $Machine3->getLocation()
));

$MachineManager->returnToBase();;

print_r(array(
    $Machine1->getLocation(),
    $Machine2->getLocation(),
    $Machine3->getLocation()
));

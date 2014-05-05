<?php
require("../vendors/dijkstra/Dijkstra.php");

trait Utils
{
    protected function generateId($start=5, $end=11)
    {
        $length = rand($start, $end);
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}


class Radar extends Path
{
    public function getObjects($location)
    {
        return $this->_getItems($location);
    }

    public function getTerrainObjects()
    {
        return $this->_getAllItems();
    }

    public function sightSeeNearestObjects($location, $sightseeing) //how many weight can see
    {
        //TODO
    }
}

//Movement
class Location extends Path
{
    private $__location;

    public function __construct($location, $item) {
        $this->__location = $location;
        $this->_addItem($location, $item);
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


class Machine extends Location
{
    use Utils;
    private $__id;
    private $__opts = [
        'speed'      => 5,
        'experience' => 1,
        'shield'     => 1,
        'health'     => 10,
        'damage'     => 1
    ];


    public function __construct($Location) {
        parent::__construct($Location, $this->getIdentification());
        $this->__id = $this->generateId();
    }

    public function getId() {
        return $this->__id;
    }

    public function getIdentification()
    {
        return __CLASS__;
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
        $this->_moveItem(
            $this->getLocation(),
            $edge,
            $this->getIdentification()
        );
        $this->_setLocation($edge);
        //make physical move
        return true;
    }

    public function getLocation() {
        return $this->_getLocation();
    }

    public function getLPath($edge) {
        return parent::_getPath($edge);
    }

    protected function _getOpts($name = '')
    {
        if (!$name) {
            return $this->__opts;
        } else if (isset($this->__opts[$name])) {
            return $this->__opts[$name];
        }

        throw new Exception('option not found: '.$name);
    }

    public function getDistance($node)
    {
        return $this->_getDistance($node, $this->getLocation());
    }

}

class MultiM extends Machine
{
    private $__Machines;
    private $__id;

    public function __construct($Location, $Machines)
    {
        foreach ($Machines as $Machine) {
            if ($Machine->getLocation() !== $Location) {
                throw new Exception (
                    'build of machine should be performed in one place'
                );
            }
        }
        parent::__construct($Location);
        $this->__Machines = new SplObjectStorage();

        foreach ($Machines as $Machine) {
            $this->__Machines->attach($Machine);

            $this->_removeTerrainItems(
                $Location,
                $Machine->getIdentification(),
                1
            );
        }

        $this->__id = $this->generateId();
    }

    public function getId()
    {
        return $this->__id;
    }

}

class Kali extends MultiM
{
    const NUMBER_OF_MACHINES = 3;
    //1 to 10
    private $__opts = [
        'speed'      => 3,
        'experience' => 1,
        'shield'     => 4,
        'health'     => 10,
        'damage'     => 4,
        'attackRange'=> 80,
    ];

    public function __construct ($Location, $Machines)
    {
        if (count($Machines) !== self::NUMBER_OF_MACHINES) {
            throw new Exception('number of machines needed do not match');
        }
        foreach ($Machines as $M) {
            if (!is_a($M, 'Machine')) {
                throw new Exception('not an instance of Machine');
            }
        }

        parent::__construct($Location, $Machines);
    }

    public function getIdentification()
    {
        return __CLASS__;
    }

    public function shoot()
    {
    }
}


class Eli extends MultiM
{
    const NUMBER_OF_MACHINES = 2;
    private $__opts = [
        'speed'      => 2,
        'experience' => 1,
        'shield'     => 6,
        'health'     => 10,
        'damage'     => 3,
        'attackRange'=> 50,
    ];

    public function __construct ($Location, $Machines)
    {
        if (count($Machines) !== self::NUMBER_OF_MACHINES) {
            throw new Exception('number of machines needed do not match');
        }
        foreach ($Machines as $M) {
            if (!is_a($M, 'Machine')) {
                throw new Exception('not an instance of Machine');
            }
        }
        parent::__construct($Location, $Machines);
    }

    public function getIdentification()
    {
        return __CLASS__;
    }

    public function shoot()
    {
    }

}

class EnemySoldier extends Machine
{
    private $__id;
    private $__opts = [
        'speed'      => 2,
        'experience' => 1,
        'shield'     => 2,
        'health'     => 10,
        'damage'     => 3,
        'attackRange'=> 10,
    ];

    public function __construct($Location)
    {
        parent::__construct($Location, $this->getIdentification());
        $this->__id = $this->generateId();
    }

    public function getIdentification()
    {
        return __CLASS__;
    }

    public function getId()
    {
        return $this->__id;
    }

    public function shoot()
    {
    }
}


class MachineManager
{
    private $__Machines;
    private $__Base;

    public function __construct($Base)
    {
        $this->__Base = $Base;
        $this->__Machines = new SplObjectStorage();
    }

    public function register(Machine $Machine)
    {
        $this->__Machines->attach($Machine);
    }

    public function deRegister(Machine $Machine)
    {
        $this->__Machines->detach($Machine);
    }

    public function move($location)
    {
        foreach ($this->__Machines as $ML) {
            $ML->move($location);
        }
    }

    public function returnToBase()
    {
        return $this->move($this->getBaseLocation());
    }

    public function moveMTo($number, $location)
    {
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

    private function getBaseLocation()
    {
        return $this->__Base;
    }
}

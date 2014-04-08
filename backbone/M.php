<?php
require("../vendors/dijkstra/Dijkstra.php");

trait Utils
{
    protected function generateId($start=5, $end=11) {
        $length = rand($start, $end);
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

class Radar extends Path
{
    public function getDataByNode($node)
    {
    }
}

//Movement
class Location extends Path
{
    private $__location;

    public function __construct($location) {
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
        parent::__construct($Location);
        $this->__id = $this->generateId();
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

class Kali extends Machine
{
    private $__Machines; //3 machines
    private $__id;
    //1 to 10
    private $__opts = [
        'speed'      => 3,
        'experience' => 1,
        'shield'     => 4,
        'health'     => 10,
        'damage'     => 4,
        'attackRange'=> 80,
    ];

    public function __construct(Machine $M1, Machine $M2, Machine $M3, $Location)
    {
        parent::__construct($Location);

        $this->__Machines = new SplObjectStorage();
        $this->__Machines->attach($M1);
        $this->__Machines->attach($M2);
        $this->__Machines->attach($M3);

    // @TODO some combination and transformation
        $this->__id = $this->generateId();
    }

    public function getId()
    {
        return $this->__id;
    }

    public function shoot()
    {
    }
}

class Eli extends Machine
{
    private $__Machines; //2 machines
    private $__id;

    private $__opts = [
        'speed'      => 2,
        'experience' => 1,
        'shield'     => 6,
        'health'     => 10,
        'damage'     => 3,
        'attackRange'=> 50,
    ];

    public function __construct(Machine $M1, Machine $M2, $Location)
    {
        parent::__construct($Location);

        $this->__Machines = new SplObjectStorage();
        $this->__Machines->attach($M1);
        $this->__Machines->attach($M2);

        $this->__id = $this->generateId();
    }

    public function getId()
    {
        return $this->__id;
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
        parent::__construct($Location);
        $this->__id = $this->generateId();
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

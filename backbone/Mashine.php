<?php

class Machine
{
    protected $__id;
    protected $__commandHubId;

    public function __construct() {
        $this->__id = $this->generateId();
    }

    protected function generateId() {
        $length = rand(70, 100);
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public function getId() {
        return $this->__id;
    }

    public function setCommandHubId($id) {
        return $this->__commandHubId = $id;
    }
}

class MachineGrid extends Machine
{
    protected $__coordinates = ['x' => null, 'y' => null];
    protected $__machine;
    protected $__directions = ['up', 'down', 'left', 'right'];

    public function __construct (Machine $Machine, $x, $y) {
        $this->__machine = $Machine;
        $this->__coordinates['x'] = $x;
        $this->__coordinates['y'] = $y;
    }

    public function getCoordiantes() {
        return $this->__coordinates;
    }

    public function move($direction) {
        if (in_array($direction, $this->__directions)) {
            $name = "__move" . ucfirst($direction) ."";
            return $this->$name();
        }
    }

    public function getPosition() {
        return $this->__coordinates;
    }

    protected function __moveUp() {
        $this->__coordinates['y'] -= 1;
    }

    protected function __moveDown() {
        $this->__coordinates['y'] += 1;
    }

    protected function __moveLeft() {
        $this->__coordinates['x'] -= 1;
    }

    protected function __moveRight() {
        $this->__coordinates['x'] += 1;
    }
}

class MCommandHub
{
    protected $__machines = [];

    public function register($id, $coordinates) {
        $uid = null;
        while (!$uid) {
            $tuid = $this->__getUniqueId();
            if (!isset($this->__machines[$tuid])) {
                $uid = $tuid;
            }

        };
        $this->__machines[$uid] = compact('id', 'coordianates');
        return $uid;
    }

    protected function __getUniqueId() {
        $length = rand(37, 51);
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public function updatePosition($id, $identifier, $coordinates) {
        $this->__identify($id, $identifier);
    }
}


$machines = [];
$Machine = new Machine();
$MachineGrid = new MachineGrid($Machine, 4, 6);
$MCommandHub = new MCommandHub();
$MCommandHubId = $MCommandHub->register(
    $Machine->getId(),
    $MachineGrid->getCoordiantes()
);
$Machine->setCommandHubId($MCommandHubId);
print_r(array($MCommandHub, $Machine));
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

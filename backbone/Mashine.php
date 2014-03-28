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

    public function communicationApproved($chid) {
    }

    public function karkar() {
        var_dump('kakarieku');
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
    protected $__communication = [];
    protected $__communicationArchive = [];

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

    public function initCommunicateBetweenMachines($id, $mid, $toId) {
        $fromMachine = $this->__validateMachine($id, $mid);
        $toMid = $this->__getMachineMid ($toId); //valid
        $toMachine = $this->__validateMachine($id, $mid); //is this necesery?
        if ($this->__findCommunicationRequest($id, $toId)) {
            $this->__approveCommunicationRequest($id, $toId);
        } else {
            $this->__makeCommunicationRequest($id, $toId);
        }
    }

    protected function __makeCommunicationRequest($id, $toId) {
        $this->__communication[] = [
            'from'      => $id,
            'to'        => $toId,
            'initiated' => time(),
            'valid'     => time() + 60,
            'approved'  => false
        ];
    }

    protected function __approveCommunicationRequest($id, $toId) {
        foreach ($this->__communication as $k=>$item) {
            if ($item['from'] === $fromId
                && $item['to'] === $toId
                && $item['valid'] <= time()) {
                $this->__communication[$k]['approved'] = time();

                $this->__communicationArchive[] = $this->_communication[$k];
                unset($this->_communication[$k]);

                foreach (array($id, $toId) as $idx) {
                    $this->__sendCommunicationApproved($idx);
                }
            }
        }
    }

    protected function __sendCommunicationApproved($id) {

    }

    protected function __findCommunicationRequest($id, $toId) {
        foreach ($this->__communication as $item) {
            if ($item['from'] === $fromId
                && $item['to'] === $toId
                && $item['valid'] <= time()) {
                return true;
            }
        }
    }

    protected function __validateMachine($id, $mid) {
        return isset($this->__machines[$mid][$id]);
    }

    protected function __getMachineMid($id) {
        foreach($this->__machines as $mid=>$machine) {
            if ($machine['id'] === $id) {
                return $mid;
            }
        }

        return null;
    }

}

class MCommunicate
{
    protected $__machine;

    public function __construct(Machine $Machine) {
        $this->__machine = $Machine;
    }

}



$machines = [];
$Machine1 = new Machine();
$Machine2 = new Machine();

$MachineGrid1 = new MachineGrid($Machine1, 4, 6);
$MachineGrid2 = new MachineGrid($Machine2, 5, 6);

$MCommandHub = new MCommandHub();
$MCommandHubId = $MCommandHub->register(
    $Machine1->getId(),
    $MachineGrid1->getCoordiantes()
);
$Machine1->setCommandHubId($MCommandHubId);
$MCommandHubId = $MCommandHub->register(
    $Machine2->getId(),
    $MachineGrid2->getCoordiantes()
);
$Machine2->setCommandHubId($MCommandHubId);

print_r(array($MCommandHub, $Machine1, $Machine2));
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

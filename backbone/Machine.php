<?php

class HeadquartersLocation extends Location {
    private $__location = 'e';

    public function __construct() {
        parent::__construct($this->__location);
    }
}

class Location {
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
}

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
}

class Communicate
{
    // private $__Machine;

    // public function __construct(Machine $Machine) {
    //     $this->__Machine = $Machine;
    // }
    public function sendCommand() {
    }

    public function register($id) {
    }
}


class MachineCommunication extends Communicate {
    private $__commandHubId;
    private $__communicationApproved;

    public function __construct(Machine $Machine) {
        $this->__commandHubId = $this->register($Machine->getId());
    }

    public function communicationApproved($chid) {
        $this->communicationApproved[$chid] = time();
    }
}


class MachineLocation extends Location {
    private $__Machine;

    public function __construct (Machine $Machine, $location) {
        $this->__Machine = $Machine;
        parent::__construct($location);
    }

    public function getLocation() {
        return $this->_getLocation();
    }

    public function move($edge) {
        foreach ($edge as $e) {
            $this->_setLocation($e);
        }
    }
}

class MCommandHub
{
    private $__machines = [];
    private $__communication = [];
    private $__communicationArchive = [];

    public function returnToBase() {
        foreach ($__machines as $machine) {
        }
    }

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

    private function __getUniqueId() {
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

    private function __makeCommunicationRequest($id, $toId) {
        $this->__communication[] = [
            'from'      => $id,
            'to'        => $toId,
            'initiated' => time(),
            'valid'     => time() + 60,
            'approved'  => false
        ];
    }

    private function __approveCommunicationRequest($id, $toId) {
        foreach ($this->__communication as $k=>$item) {
            if ($item['from'] === $fromId
                && $item['to'] === $toId
                && $item['valid'] <= time()) {
                $this->__communication[$k]['approved'] = time();

                $this->__communicationArchive[] = $this->_communication[$k];
                unset($this->_communication[$k]);

                // foreach (array($id, $toId) as $idx) {
                    $this->__sendCommunicationApproved($id, $toId);
                    $this->__sendCommunicationApproved($toId, $id);
                // }
            }
        }
    }

    private function __sendCommunicationApproved($id, $toId) {
        foreach ($this->__machines as $Machine) {
            if ($Machine->getId() === $id) {
                $Machine->communicationApproved($toId);
            }
        }
    }

    private function __findCommunicationRequest($id, $toId) {
        foreach ($this->__communication as $item) {
            if ($item['from'] === $fromId
                && $item['to'] === $toId
                && $item['valid'] <= time()) {
                return true;
            }
        }
    }

    private function __validateMachine($id, $mid) {
        return isset($this->__machines[$mid][$id]);
    }

    private function __getMachineMid($id) {
        foreach($this->__machines as $mid=>$machine) {
            if ($machine['id'] === $id) {
                return $mid;
            }
        }

        return null;
    }

}


//there are more algorythoms; this is indirect appproach a->b != b->a
require("../vendors/dijkstra/Dijkstra.php");

$g = new Graph();
$g->addedge("a", "b", 4);
$g->addedge("a", "d", 1);

$g->addedge("b", "a", 74);
$g->addedge("b", "c", 2);
$g->addedge("b", "e", 12);

$g->addedge("c", "b", 12);
$g->addedge("c", "j", 12);
$g->addedge("c", "f", 74);

$g->addedge("d", "g", 22);
$g->addedge("d", "e", 32);

$g->addedge("e", "h", 33);
$g->addedge("e", "d", 66);
$g->addedge("e", "f", 76);

$g->addedge("f", "j", 21);
$g->addedge("f", "i", 11);

$g->addedge("g", "c", 12);
$g->addedge("g", "h", 10);

$g->addedge("h", "g", 2);
$g->addedge("h", "i", 72);

$g->addedge("i", "j", 7);
$g->addedge("i", "f", 31);
$g->addedge("i", "h", 18);

$g->addedge("j", "f", 8);

$Headcuarters = new HeadquartersLocation();

$machines = [];
$Machine1 = new Machine();
$Machine2 = new Machine();
$Machine3 = new Machine();


$MachineLocation1 = new MachineLocation($Machine1, 'a');
$MachineLocation2 = new MachineLocation($Machine2, 'a');
$MachineLocation3 = new MachineLocation($Machine3, 'a');

$path = $g->getPath($MachineLocation1->getLocation(), 'g');
$MachineLocation1->move($path);

$path = $g->getPath($MachineLocation2->getLocation(), 'f');
$MachineLocation2->move($path);

$path = $g->getPath($MachineLocation3->getLocation(), 'i');
$MachineLocation3->move($path);

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

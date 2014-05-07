<?php
require("M.php");
//
// $MAP = [
//     'nodes' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'e', 'j', 'i'],
//     'edges' => [
//         ['from' => 'a', 'to' => 'b', 'weight' => 4],
//         ['from' => 'a', 'to' => 'd', 'weight' => 1],
//
//         ['from' => 'b', 'to' => 'a', 'weight' => 74],
//         ['from' => 'b', 'to' => 'c', 'weight' => 2],
//         ['from' => 'b', 'to' => 'e', 'weight' => 12],
//
//         ['from' => 'c', 'to' => 'b', 'weight' => 12],
//         ['from' => 'c', 'to' => 'j', 'weight' => 12],
//         ['from' => 'c', 'to' => 'f', 'weight' => 74],
//
//         ['from' => 'd', 'to' => 'g', 'weight' => 22],
//         ['from' => 'd', 'to' => 'e', 'weight' => 32],
//
//         ['from' => 'e', 'to' => 'h', 'weight' => 33],
//         ['from' => 'e', 'to' => 'd', 'weight' => 66],
//         ['from' => 'e', 'to' => 'f', 'weight' => 76],
//
//         ['from' => 'f', 'to' => 'j', 'weight' => 21],
//         ['from' => 'f', 'to' => 'i', 'weight' => 11],
//
//         ['from' => 'g', 'to' => 'c', 'weight' => 12],
//         ['from' => 'g', 'to' => 'h', 'weight' => 10],
//
//         ['from' => 'h', 'to' => 'g', 'weight' => 2],
//         ['from' => 'h', 'to' => 'i', 'weight' => 72],
//
//         ['from' => 'i', 'to' => 'j', 'weight' => 7],
//         ['from' => 'i', 'to' => 'f', 'weight' => 31],
//         ['from' => 'i', 'to' => 'h', 'weight' => 18],
//
//         ['from' => 'j', 'to' => 'f', 'weight' => 8],
//     ]
// ];
// //file_put_contents('map.json', json_encode($MAP));
//
// $MACHINES = [
//     ['location' => 'c', 'machine' => 'Kali'],
//     ['location' => 'j', 'machine' => 'Eli'],
//     // ['location' => 'f', 'machine' => 'EnemySoldier'],
// ];
//
// file_put_contents('machines.json', json_encode($MACHINES));
//
//
// $OTHERS = [
//     ['location' => 'f', 'obj' => 'EnemySoldier'],
// ];
// file_put_contents('others.json', json_encode($OTHERS));
//
//
// $CONTROLLS = [
//     ['location' => 'b', 'obj' => 'MachineManager'],
// ];
// file_put_contents('controlls.json', json_encode($CONTROLLS));
//
$dir = dirname(__FILE__) . '/_DATA/';

$MAP = json_decode(file_get_contents($dir.'map.json'), true);
$MACHINES = json_decode(file_get_contents($dir.'machines.json'), true);
$CONTROLLS = json_decode(file_get_contents($dir.'controlls.json'), true);
$OTHERS = json_decode(file_get_contents($dir.'others.json'), true);

Path::initialize($MAP);
$Radar = new Radar();

foreach ($CONTROLLS as $M) {
    $Location = $M['location'];
    $name = $M['obj'];
    $TerrainObjects[] = new $name($Location);
}

$TerrainObjects = [];
foreach ($MACHINES as $M) {
    $Location = $M['location'];
    $name = $M['machine'];
    $machines = [];
    for($i=$name::numberOfMachines();$i;$i--) {
        $machines[] = new Machine($Location);
    }

    $TerrainObjects[] = new $name($Location, $machines);
}

foreach ($OTHERS as $M) {
    $Location = $M['location'];
    $name = $M['obj'];
    $TerrainObjects[] = new $name($Location);
}
print_r($Radar->getTerrainObjects());exit;


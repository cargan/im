<?php
require("M.php");

$Radar = new Radar();
$Location = 'a';
$Machine1 = new Machine($Location);
$Machine2 = new Machine($Location);
$Machine3 = new Machine($Location);

$Base = 'b';

$Location2 = 'i';
$Machine4 = new Machine($Location2);
$Machine5 = new Machine($Location2);

$Machine4->move('c');
$MachineManager = new MachineManager($Base);

$MachineManager->register($Machine1);
$MachineManager->register($Machine2);
$MachineManager->register($Machine3);

//think of a proper way of handling
$MachineManager->deRegister($Machine1);
$MachineManager->deRegister($Machine2);
$MachineManager->deRegister($Machine3);
$Kali = new Kali($Machine1, $Machine2, $Machine3, $Location);

$MachineManager->deRegister($Machine4);
$MachineManager->deRegister($Machine5);
$Eli = new Eli($Machine4, $Machine5, $Location2);
$EnemySoldier = new EnemySoldier('h');

$MachineManager->register($Kali);
$MachineManager->register($Eli);
var_dump($Kali->getIdentification());
print_r($MachineManager );
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);

$MachineManager->moveMTo(1, 'g');
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);

$MachineManager->moveMTo(1, 'c');
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);

var_dump($Kali->getDistance('i'));
var_dump($Radar->getObjects('c'));
print_r($Radar->getTerrainObjects());
//
$MachineManager->returnToBase();;

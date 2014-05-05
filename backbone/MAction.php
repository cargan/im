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


$MachineManager = new MachineManager($Base);

$MachineManager->register($Machine1);
$MachineManager->register($Machine2);
$MachineManager->register($Machine3);

//think of a proper way of handling
$Kali = new Kali($Location, [$Machine1, $Machine2, $Machine3]);

$MachineManager->deRegister($Machine1);
$MachineManager->deRegister($Machine2);
$MachineManager->deRegister($Machine3);

$Eli = new Eli($Location2, [$Machine4, $Machine5]);
$MachineManager->deRegister($Machine4);
$MachineManager->deRegister($Machine5);

$EnemySoldier = new EnemySoldier('h');
$MachineManager->register($Kali);
$MachineManager->register($Eli);
// var_dump($Kali->getIdentification());
// print_r($MachineManager);
// exit;
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);

// $MachineManager->moveMTo(1, 'g');
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);
$MachineManager->moveMTo(1, 'c');
print_r([
    $Kali->getLocation(),
    $Eli->getLocation()
]);

$MachineManager->returnToBase();

print_r($Radar->getTerrainObjects());
exit;



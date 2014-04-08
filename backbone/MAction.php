<?php
require("M.php");

$Location = 'a';
$Machine1 = new Machine($Location);
$Machine2 = new Machine($Location);
$Machine3 = new Machine($Location);
$Base = 'b';

$Location2 = 'i';
$Machine4 = new Machine($Location);
$Machine5 = new Machine($Location);

$MachineManager = new MachineManager($Base);

// $MachineManager->register($Machine1);
// $MachineManager->register($Machine2);
// $MachineManager->register($Machine3);

// $MachineManager->move('i');
//
// print_r(array(
//     $Machine1->getLocation(),
//     $Machine2->getLocation(),
//     $Machine3->getLocation()
// ));
//
//
//
// $MachineManager->moveMTo(2, 'g');
// print_r(array(
//     $Machine1->getLocation(),
//     $Machine2->getLocation(),
//     $Machine3->getLocation()
// ));
//
// $MachineManager->moveMTo(1, 'a');
//
// print_r(array(
//     $Machine1->getLocation(),
//     $Machine2->getLocation(),
//     $Machine3->getLocation()
// ));
//
// $MachineManager->returnToBase();;
//
// print_r(array(
//     $Machine1->getLocation(),
//     $Machine2->getLocation(),
//     $Machine3->getLocation()
// ));
//

$Kali = new Kali($Machine1, $Machine2, $Machine3, $Location);
$Eli = new Eli($Machine4, $Machine5, $Location2);
$MachineManager->register($Kali);
$MachineManager->register($Eli);

// print_r($MachineManager );
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




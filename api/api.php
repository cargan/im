<?php
error_reporting(E_ALL);
ini_set( 'display_errors','1');

$dir = dirname(dirname(__FILE__)) . '/api/';
require($dir.'db.php');
require($dir.'ApiManager.php');

$actions = [
    'addMachine',
    'removeMachine',
    'moveMachine',
    'getEdges',
    'getNodes',
];

// var_dump($_GET['a']);exit;
// print_r(moveMachine(1, 'h', $dbh));

$action = !empty($_GET['a']) ? $_GET['a'] : null;
$params = !empty($_GET['p']) ? $_GET['p'] : null;

if (!in_array($action, $actions)) {
    header( "HTTP/1.0 400 Bad Request");
    die('unknown action');
}
header('content-type: application/json; charset=utf-8');

$ApiManager = new ApiManager($dbh);
$result = $ApiManager->get($action, $params);

die(json_encode(compact('result')));

<?php
error_reporting(E_ALL);
ini_set( 'display_errors','1');

$dir = dirname(dirname(__FILE__)) . '/api/';
require($dir.'db.php');
require($dir.'ApiManager.php');

$actions = [
    'post' => [
        'addMachine',
        'getEnemies',
        'getMachines',
    ],
    'get' => [
        'removeMachine',
        'moveMachine',
        'getEdges',
        'getNodes',
        'getNodesAndEdges',
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actions = $actions['post'];
    $action = !empty($_POST['a']) ? $_POST['a'] : null;
    unset($_POST['a']);
    $params = $_POST;
} else {
    $actions = $actions['get'];
    $action = !empty($_GET['a']) ? $_GET['a'] : null;
    $params = !empty($_GET['p']) ? $_GET['p'] : null;
}
// print_r($action);
// print_r($params);exit;
if (!in_array($action, $actions)) {
    header( "HTTP/1.0 400 Bad Request");
    die('unknown action');
}
header('content-type: application/json; charset=utf-8');

$ApiManager = new ApiManager($dbh);
$result = $ApiManager->get($action, $params);

die(json_encode(compact('result')));

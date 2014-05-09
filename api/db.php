<?php
try {
    $dbh = new PDO("mysql:host=localhost;dbname=im;charset=utf8", 'root', 'kugelis');
} catch(PDOException $e) {
    die($e->getMessage());
}

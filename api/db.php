<?php
try {
    $dbh = new PDO("mysql:host=localhost;dbname=im;charset=utf8", 'im', 'kakalis');
} catch(PDOException $e) {
    die($e->getMessage());
}

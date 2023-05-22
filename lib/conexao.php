<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $bd = 'escola_ead';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$bd", $user, $password);
    } catch(PDOException $e) {
        echo "Connect failed ".$e->getMessage();
    }

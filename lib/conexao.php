<?php
    $host = 'db';
    $bd = 'escola_ead';
    $user = 'root';
    $password = 'root';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$bd", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connect failed ".$e->getMessage();
    }

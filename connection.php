<?php
    $dbhost = "Localhost:3307";
    $dbname = "bakery_shop";
    $dbuser = "root";
    $dbpass = "";

    try {
        $db = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
    }
?>
<?php
    $dbhost = "localhost:3307";
    $dbname = "bakery_shop";
    $dbuser = "root";
    $dbpass = "";

    try {
        $db = new PDO("mysql:host=$dbhost; dbname=$dbname", $dbuser, $dbpass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected to DB <br>";
    } catch (PDOException $e) {
        echo "Error connecting to DB: $e";
    }
?>
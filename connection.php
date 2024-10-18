<?php
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
    $dotenv->load();

    try {
        $db = new PDO("mysql:host={$_ENV["DB_HOST"]}; dbname={$_ENV["DB_NAME"]}", $_ENV["DB_USER"], $_ENV["DB_PASS"]);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
?>
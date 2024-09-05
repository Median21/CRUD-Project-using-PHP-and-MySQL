<?php
    include("connection.php");
    session_start();

    if (empty($_SESSION)) {
        echo "Not Logged in";
    } else {
        echo "Logged in";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/header.css">
    <title>Bakery Shop</title>
</head>
<body>
    <?php include("header.html") ?>
</body>
</html>
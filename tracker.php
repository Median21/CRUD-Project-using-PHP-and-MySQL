<?php
    include("connection.php");
    session_start();

    $result = null;

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        try {
            $stmt = $db->prepare("SELECT status FROM orders WHERE order_id = :order_id");
            $stmt->bindParam(":order_id", $_GET["tracker"]);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    $result = $row["status"];
            } else {
                $result = "Invalid order number";
            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="tracker.php" method="get">
        <label for="Tracker"></label>
        <input type="text" name="tracker">
        <button>Track ORDER</button>
    </form>

    <p>Status: <?= $result ?></p>
</body>
</html>
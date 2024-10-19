<?php
    include("connection.php");
    session_start();

    try {
        $stmt = $db->query("SELECT * FROM orders WHERE status ='Pending'");
        $stmt->execute();
        $pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->query("SELECT * FROM orders WHERE status ='Preparing'");
        $stmt->execute();
        $preparing_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->query("SELECT * FROM orders WHERE status ='Completed'");
        $stmt->execute();
        $completed_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch (PDOException $e) {
        echo $e->getMessage();

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/admin-dashboard.css">
    <title>Admin Dashboard</title>
</head>
<body>

    <?php if (empty($_SESSION["type"]) || $_SESSION["type"] == "Customer") { ?>
        <?php include("unauthorized.php") ?>
    <?php } else { ?>

        <?php include("header.php") ?>
            <div class="iframe-container">
                <iframe src="track-orders.php" frameborder="0" height="800px" width="100%" id="orders-frame"></iframe>
                <hr>
                <iframe id="frame" src="" frameborder="0" height="500px" width="100%"></iframe>
            </div>


        <script src="JS/global.js"></script>
        <script>
            const ordersFrame = document.getElementById("orders-frame");
            const detailsFrame = document.getElementById("frame");     
        </script>

    <?php } ?>

</body>
</html>
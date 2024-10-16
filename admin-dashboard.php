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
    <?php include("header.php") ?>

    <!-- <h1 style="text-align: center;">Trackings</h1>
    <main>
        <section>
            <h2>Pending:</h2>
            <ul class="admin-ul">
                <?php foreach ($pending_orders as $order) { ?>
                    <li class="orders">
                        <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>">Order#: <?= $order["order_id"] ?></a>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <section>
            <h2>Preparing:</h2>
            <ul class="admin-ul">
                <?php foreach ($preparing_orders as $order) { ?>
                    <li>
                        <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>">Order#: <?= $order["order_id"] ?></a>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <section>
            <h2>Completed:</h2>
            <ul class="admin-ul">
            <?php foreach ($completed_orders as $order) { ?>
                <li>
                    <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>" >Order#: <?= $order["order_id"] ?></a>
                </li>
            <?php } ?>
            </ul>
        </section> -->

     

    
 <!--    </main> -->
   

        <main>
            <iframe src="test2.php" frameborder="1" height="800px" width="100%" id="orders-frame"></iframe>
            <iframe id="frame" src="" frameborder="1" height="500px" width="100%"></iframe>
        </main>


    <script src="JS/global.js"></script>
    <script>
        const ordersFrame = document.getElementById("orders-frame");
        const detailsFrame = document.getElementById("frame");

       
    </script>
</body>
</html>
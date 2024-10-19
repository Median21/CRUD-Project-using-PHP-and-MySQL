<?php
    include("connection.php");
    session_start();

    if (isset($_SESSION["id"])) {
        try {
            $user_id = $_SESSION["id"];
            $user_order = $db->prepare("SELECT * FROM orders WHERE user_id = :id ORDER BY order_id DESC");
            $user_order->bindParam(":id", $user_id);
            $user_order->execute();
    
            $all_user_order = $user_order->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $stmt = $db->prepare("UPDATE orders SET status = 'Cancelled' WHERE order_id = :order_id");
        $stmt->bindParam(":order_id", $_POST["order_id"]);
        $stmt->execute();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Cart Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/orders.css">
    <title>BakeMaster | Orders</title>
</head>
<body>
    <?php include("header.php") ?>

<!--     <h1 class="orders-title">Orders</h1> -->

    <!-- <table>
        <tr>
            <th>Order Number</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>

        <?php if (!empty($all_user_order)) { ?>
            <?php foreach($all_user_order as $order) { ?>
            <tr>
                <td><?= $order["order_id"] ?></td>
                <td><?= $order["order_date"] ?></td>
                <td><?= $order["status"] ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
    </table> -->

    <?php if (!empty($all_user_order)) { ?>

        <form action="<?= $_SERVER["PHP_SELF"]?>" method="POST">

        <?php foreach($all_user_order as $order) { ?>
            <div class="order-container">
                <div class="id-status">
                    <h3>Order ID: <?= $order["order_id"] ?></h3>
                    <p class="status"><?= $order["status"] ?></p>
                </div>

                <div class="other-details">
                    <p>Address: One Archers</p>
                    <p>Total Qty: 3</p>
                    <p>Total Amount: P155</p>
                </div>

                <div class="button-container">
                    <button>SEE MORE</button>
                    
                    <?php if ($order["status"] == "Pending") { ?>
                        <button name="order_id" value="<?= $order["order_id"] ?>">CANCEL</button>
                    <?php } else { ?>
                        <button name="order_id" type="button" disabled>CANCEL</button>
                    <?php } ?>


                </div>
            </div>

        <?php } ?>

        </form>

    <?php } ?>

    <script src="JS/global.js"></script>
</body>
</html>
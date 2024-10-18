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

 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/orders.css">
    <title>BakeMaster | Orders</title>
</head>
<body>
    <?php include("header.php") ?>

    <h1 class="orders-title">Orders</h1>

    <table>
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
    </table>

    <script src="JS/global.js"></script>
</body>
</html>
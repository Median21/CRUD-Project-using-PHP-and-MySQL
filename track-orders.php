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

    <link rel="stylesheet" href="CSS/global.css ">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/admin-dashboard.css">
    <title>Admin Dashboard</title>
</head>
<body style="background-image: none;">
    <h1 style="text-align: center;">Trackings</h1>
    <button id="reload">&#8634;</button>

    <main>
        <section>
            <h2>Pending:</h2>
            <ul class="admin-ul">
                <?php foreach ($pending_orders as $order) { ?>
                    <li class="orders">
                        <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>"> | Order#:<?= $order["order_id"] ?> | </a>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <section>
            <h2>Preparing:</h2>
            <ul class="admin-ul">
                <?php foreach ($preparing_orders as $order) { ?>
                    <li>
                        <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>"> | Order#:<?= $order["order_id"] ?> | </a>
                    </li>
                <?php } ?>
            </ul>
        </section>

        <section>
            <h2>Completed:</h2>
            <ul class="admin-ul">
            <?php foreach ($completed_orders as $order) { ?>
                <li>
                    <a href="javascript:void(0)" data-link="order-status.php?order=<?= $order['order_id']?>" > | Order#:<?= $order["order_id"] ?> | </a>
                </li>
            <?php } ?>
            </ul>
        </section>
    </main>

    <script>
        const links = document.querySelectorAll("a");

        links.forEach(link => {
            link.addEventListener("click", () => {
                parent.document.getElementById("frame").src = link.dataset.link;
            })
        })

        document.getElementById("reload").addEventListener("click", () => {
            window.location.reload();
        })
    </script>
</body>
</html>
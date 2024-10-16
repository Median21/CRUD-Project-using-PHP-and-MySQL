<?php
    include("connection.php");

    try {
       /*  $stmt = $db->query("SELECT * FROM orders WHERE order_id = {$_GET['order']}");
        $stmt->execute(); */

        $order_items = $db->query("SELECT
                                order_details.user_id,
                                order_details.order_id,
                                product.product_id,
                                product.name,
                                product.price,
                                order_details.quantity,
                                product.image,
                                orders.status,
                                price * quantity as amount
                            FROM
                                order_details
                            INNER JOIN product ON order_details.product_id = product.product_id
                            INNER JOIN orders ON order_details.order_id = orders.order_id
                            WHERE order_details.order_id = {$_GET['order']}");
        $order_items->execute();

        $order_items_arr = $order_items->fetchAll();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
    
            $stmt = $db->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
            $stmt->bindParam("status", $_POST["status"]);
            $stmt->bindParam(":order_id", $_POST["order_id"]);
            $stmt->execute();

            echo "Status has been updated";
           

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
    <link rel="stylesheet" href="CSS/order-status.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>Document</title>
</head>
<body>

        <h2>Order #<?= $order_items_arr[0]["order_id"] ?></h2>
        <h3>Customer ID: <?= $order_items_arr[0]["user_id"] ?></h3>
 
        <form action="order-status.php?order=<?= $order_items_arr[0]["order_id"] ?>" method="POST">
            <input type="hidden" id="hidden_order" name="order_id" value="<?= $_GET["order"]?>">

            <table>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>

                <?php foreach ($order_items_arr as $item) { ?>
                <tr>
                    <td><?= $item["name"] ?></td>
                    <td><?= $item["price"] ?></td>
                    <td><?= $item["quantity"] ?></td>
                    <td><?= $item["amount"] ?></td>
                </tr>
                <?php } ?>
            </table>
                
            <div class="status-container">
                <label for="status">Status: </label>
                <select name="status" id="status">
                    <?php if ($order_items_arr[0]["status"] == "Pending") { ?>
                        <option selected disabled><?= $order_items_arr[0]["status"] ?></option>
                        <option>Preparing</option>

                    <?php } elseif ($order_items_arr[0]["status"] == "Preparing") { ?>
                        <option selected disabled><?= $order_items_arr[0]["status"] ?></option>
                        <option>Completed</option>

                    <?php } else {?>
                        <option selected><?= $order_items_arr[0]["status"] ?></option>
                    <?php } ?>
                </select>


                <button id="update-btn" type="button">UPDATE</button>             
            </div>
        </form>
   
      

    <script>
        let orderID = document.getElementById("hidden_order").value
        let statusVal = document.getElementById("status")

        console.log("<?= $order_items_arr[0]["order_id"] ?>");

        
        document.getElementById("update-btn").addEventListener("click", () => {
            $.ajax({
                type: 'POST',
                url: 'order-status.php?order="<?= $order_items_arr[0]["order_id"] ?>".php',
                data:{'order_id': orderID, 'status': statusVal.value},
            })

            setTimeout(() => {
                parent.document.getElementById("orders-frame").src = "test2.php";
                location.reload();
            }, 1000)
           

      
        })
    </script>
</body>
</html>
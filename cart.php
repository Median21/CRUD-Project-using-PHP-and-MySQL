<?php 
    include("connection.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
        $time = time();
        date_default_timezone_set('Asia/Hong_Kong');
        $correct_time = date('H:i:s', $time);

        $current_date = date("Y:m:d $correct_time");
        
        $status = "Test";


        try {
            //Create an order record
            $new_order = $db->prepare("INSERT INTO orders (user_id, order_date)
                                       VALUES (:user_id, :order_date)");
            $new_order->bindParam(":user_id", $_SESSION["id"]);
            $new_order->bindParam(":order_date", $current_date);
            $new_order->execute();

            $user_id_from_cart = $db->prepare("SELECT cart_id FROM shopping_cart WHERE user_id = :user_id");
            $user_id_from_cart->bindParam(":user_id", $_SESSION["id"]);
            $user_id_from_cart->execute();

            $cartID_from_shoppingcart = $user_id_from_cart->fetch(PDO::FETCH_ASSOC);


            //Clear shopping cart
            $clear_cart_item = $db->prepare("DELETE FROM cart_item WHERE cart_id = :cart_id");
            $clear_cart_item->bindParam(":cart_id", $cartID_from_shoppingcart["cart_id"]);
            $clear_cart_item->execute();

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    try {
        $user_id = $_SESSION["id"];

        $user_shopping_cart = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
        $user_shopping_cart->execute();

        if ($user_shopping_cart->rowCount() > 0) {
            $cart_id = $user_shopping_cart->fetch();
            $cart_id = $cart_id["cart_id"];

            $user_cart_item = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id'");
            $user_cart_item->execute();
            $cart_item_product_join = $db->query("SELECT shopping_cart.user_id, cart_item.quantity, product.name, product.price, product.image FROM cart_item INNER JOIN product ON cart_item.product_id=product.product_id INNER JOIN shopping_cart ON shopping_cart.cart_id=cart_item.cart_id WHERE shopping_cart.user_id = '$user_id'");
            $cart_item_product_join->execute();
        }
    
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
    <link rel="stylesheet" href="CSS/cart.css">

    <title>Cart | BakeMaster</title>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="cart-container">
        <h1 class="your-cart">Your Cart</h1>

        <table>
            <tr>
                <th class="image-col">Product</th>
                <th class="name-col">Name</th>
                <th class="qty-col">QTY</th>
                <th class="price-col">Price</th>
                <th class="total-col">Total</th>
                
            </tr>

            <?php if ($user_shopping_cart->rowCount() > 0) { ?>
                <?php while($cart_item = $cart_item_product_join->fetch(PDO::FETCH_ASSOC)) {?>
                    <tr>
                        <td><img class="product-image" src="products/<?= $cart_item["image"]?>" alt="<? $cart_item['name'] ?>"></td>
                        <td><?= $cart_item["name"] ?></td>
                        <td><?= $cart_item["quantity"] ?></td>
                        <td><?= $cart_item["price"] ?></td>
                    </tr>
                <?php } ?>
                
                <form action="cart.php" method="post">
                    <button name="checkout">CHECKOUT</button>
                </form>

            <?php } else { ?>
                    <p>Cart is empty</p>
            <?php } ?>
        </table>
    </div>

    <?php include("footer.html"); ?>

    <script src="JS/global.js"></script>
</body>
</html>
<?php 
    include("connection.php");
    session_start();

    try {
        $user_id = $_SESSION["id"];

        $user_shopping_cart = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
        $user_shopping_cart->execute();
        $cart_id = $user_shopping_cart->fetch();
        $cart_id = $cart_id["cart_id"];
    
        $user_cart_item = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id'");
        $user_cart_item->execute();
        $cart_item_product_join = $db->query("SELECT shopping_cart.user_id, cart_item.quantity, product.name, product.price, product.image FROM cart_item INNER JOIN product ON cart_item.product_id=product.product_id INNER JOIN shopping_cart ON shopping_cart.cart_id=cart_item.cart_id WHERE shopping_cart.user_id = '$user_id'");
        $cart_item_product_join->execute();

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
        <h1>Your Cart</h1>

        <table>
            <tr>
                <th>Product Name</th>
                <th>QTY</th>
                <th>Price</th>
            </tr>

            <?php while($cart_item = $cart_item_product_join->fetch(PDO::FETCH_ASSOC)) {?>
                <tr>
                    <td><?= $cart_item["name"] ?></td>
                    <td><?= $cart_item["quantity"] ?></td>
                    <td><?= $cart_item["price"] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php 
    include("connection.php");
    session_start();

    if (isset($_SESSION["has_ordered"])) {
        echo "Has ordered: " . $_SESSION['has_ordered'];
        unset($_SESSION["has_ordered"]);
     }

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

            
            //Gets the latest order of the user to get the order_id
            $get_latest_order = $db->query("SELECT * FROM `orders` WHERE user_id = {$_SESSION["id"]} ORDER BY `order_date` DESC LIMIT 1");
            $get_latest_order->execute();

            if ($get_latest_order->rowCount() > 0) {
                $latest_order = $get_latest_order->fetch(PDO::FETCH_ASSOC);
            }

            echo $latest_order["order_id"];
    
            //Gets user_id from shopping cart
            $get_cart_id_from_sc = $db->query("SELECT cart_id FROM shopping_cart WHERE user_id = {$_SESSION['id']}");
            $get_cart_id_from_sc->execute();

            $cart_id_sc = $get_cart_id_from_sc->fetch();
            $cart_id_sc = $cart_id_sc["cart_id"];
            
            //INSERTS order_details record
            $copy_cart_item = $db->prepare("INSERT INTO order_details (user_id, order_id, product_id, quantity) SELECT :user_id, :order_id, product_id, quantity FROM cart_item WHERE cart_id = :cartID_sc");

            $copy_cart_item->bindParam(":user_id", $_SESSION["id"]);
            $copy_cart_item->bindParam(":order_id", $latest_order["order_id"]);
            $copy_cart_item->bindParam(":cartID_sc", $cart_id_sc);
            $copy_cart_item->execute();


            //Clear cart_item
            $clear_cart_item = $db->prepare("DELETE FROM cart_item WHERE cart_id = :cart_id");
            $clear_cart_item->bindParam(":cart_id", $cartID_from_shoppingcart["cart_id"]);
            $clear_cart_item->execute();

            
            $_SESSION['has_ordered'] = true;
            header("Location: cart.php");
            exit;
            echo "Sent";

            

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    //Shows cart items of User
    try {
        $user_id = $_SESSION["id"];

        //Gets address from profile table
        $stmt = $db->prepare("SELECT * FROM profile WHERE user_id = :id");
        $stmt->bindValue(":id", $user_id);
        $stmt->execute();

        $user_address = $stmt->fetch();
      

        
        //Change to cart ITEM
        $user_shopping_cart = $db->query("SELECT
                                            cart_item.cart_id,
                                            shopping_cart.user_id
                                        FROM
                                            cart_item
                                        INNER JOIN shopping_cart ON cart_item.cart_id = shopping_cart.cart_id
                                        WHERE
                                            user_id = $user_id");
        $user_shopping_cart->execute();

        if ($user_shopping_cart->rowCount() > 0) {
            $cart_id = $user_shopping_cart->fetch();
            $cart_id = $cart_id["cart_id"];

            $user_cart_item = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id'");
            $user_cart_item->execute();
            $cart_item_product_join = $db->query(
                "SELECT 
                    shopping_cart.user_id,
                    cart_item.quantity,
                    product.name,
                    product.price,
                    product.image,
                    cart_item.quantity * product.price AS sub_total
                FROM 
                    cart_item
                INNER JOIN 
                    product ON cart_item.product_id=product.product_id 
                INNER JOIN
                    shopping_cart ON shopping_cart.cart_id=cart_item.cart_id
                WHERE
                    shopping_cart.user_id = '$user_id'
                ");
            $cart_item_product_join->execute();



            $calculate_grand_total = $db->query(
                "SELECT 
                    shopping_cart.user_id,
                    cart_item.quantity,
                    product.name,
                    product.price,
                    product.image,
                    SUM(cart_item.quantity * product.price) AS grand_total,
                    cart_item.quantity * product.price AS sub_total
                FROM 
                    cart_item
                INNER JOIN 
                    product ON cart_item.product_id=product.product_id 
                INNER JOIN
                    shopping_cart ON shopping_cart.cart_id=cart_item.cart_id
                WHERE
                    shopping_cart.user_id = '$user_id'
                ");

            $calculate_grand_total->execute();

            $grand_total = $calculate_grand_total->fetch(PDO::FETCH_ASSOC);
            $grand_total = $grand_total["grand_total"];

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

    <!-- Cart Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/cart.css">

    <title>BakeMaster | Cart</title>
</head>
<body>
    <?php include("header.php"); ?>
    
    <div class="cart-container">
        <h1 class="your-cart">Your Cart</h1>
        <div class="address-container">
            <p> <span style="text-decoration: underline;">Address:</span> <?= !empty($user_address["address"]) ? $user_address["address"] : "No Address set" ?></p>
            <a href="profile.php">Edit</a>
        </div>
       
       
      

        <?php if ($user_shopping_cart->rowCount() > 0) { ?>
 
            <h3>Items</h3>
            <h3>Name</h3>
            <h3>Quantity</h3>
            <h3>Amount</h3>

            <?php while($cart_item = $cart_item_product_join->fetch(PDO::FETCH_ASSOC)) {?>
                <div>
                    <img src="products/<?= $cart_item['image'] ?>" alt="">
                </div>
                    <p><?= $cart_item["name"] ?></p>
                    <p>x<?= $cart_item["quantity"] ?></p>
                    <p>₱ <?= $cart_item["price"] * $cart_item["quantity"]?> </p>
            <?php } ?>
            
                <div class="line"></div>

            
                <p class="price-details">Subtotal (Incl. 12% VAT)</p>
                <p>₱ <?= $grand_total ?></p>

                
                <p class="price-details">Delivery Fee</p>
                <p>₱ 50</p>

                <p class="price-details" style="padding-top: 1rem;">GRAND TOTAL: </p>
                <p style="border-top: 1px solid black; padding-top: 1rem;">₱ <?= $grand_total + 50 ?></p>

                <h6 class="price-details">(12% VAT)</h6>
                <h6>(₱ <?= $grand_total * 0.12 ?>)</h6>
                
                <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="checkout-form">
                    <button name="checkout" id="checkout-btn">ORDER</button>
                </form>

        <?php } else { ?>
                <p style="grid-column: 1 / -1;">Cart is empty</p>
        <?php } ?>

        
    </div>

    <?php include("footer.html"); ?>

    <script src="JS/global.js"></script>
</body>
</html>
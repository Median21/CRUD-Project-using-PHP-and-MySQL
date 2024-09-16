<?php
    include("connection.php");
    session_start();

    $result = null;

    try {
        $stmt = $db->query("SELECT * FROM product");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

    }  catch(PDOException) {
        
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION["id"];
        $product_id = $_POST["add-to-cart"];


        try {
            //Find cart_id and product_id
            $find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $find_cart_id->execute();

            if ($find_cart_id->rowCount() == 0) {
                //Add shopping_cart row for the user
                $user_cart = $db->query("INSERT INTO shopping_cart (user_id)
                VALUES ($user_id)");
                $user_cart->execute();
                echo "No cart found, adding shopping_cart record";
            } else {
                echo "Already have shopping cart record";
                $cart_id = $find_cart_id->fetch();
                $cart_id = $cart_id["cart_id"];
            }

            //Check if there is an existing cart_id and product_id
            $cart_and_product = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            $cart_and_product->execute();

            if ($cart_and_product->rowCount() <= 0) { //Add cart item
                echo "ADDING TO CART...";
                //For cart_item TABLE (ADD new cart_item ROW)
                $cart_item = $db->prepare("INSERT INTO cart_item (cart_id, product_id, quantity)
                VALUES (:cart_id, :product_id, :quantity)");
                $cart_item->bindValue(":cart_id", $cart_id);
                $cart_item->bindValue(":product_id", $product_id);
                $cart_item->bindValue(":quantity", 1);
                $cart_item->execute();
            } else { //Update quantity of cart item
                echo "Already in cart, updating quantity";
                $old_quantity = $cart_and_product->fetch(PDO::FETCH_ASSOC);
                $old_quantity = $old_quantity["quantity"];
                $new_quantity = $old_quantity + 1;
                $cart_item = $db->prepare("UPDATE cart_item SET quantity = :quantity WHERE cart_id = :cart_id AND  product_id = :product_id");
                $cart_item->bindParam(":quantity", $new_quantity);
                $cart_item->bindParam(":cart_id", $cart_id);
                $cart_item->bindParam(":product_id", $product_id);
                $cart_item->execute();
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
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/menu.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <?php include("header.php") ?>
    <a href="cart.php">CART</a>
    <form action="menu.php" method="post">
        <main>
                <?php if (!empty($result)) {?>
                <?php foreach ($result as $product) {?>
                    <div class="menu-container">
                        <div class="image-container">
                            <img src="products/<?= rawurlencode($product["image"]) ?>" alt="test">
                        </div>

                        <div>
                            <h2 class="product-name"><?= $product["name"] ?></h2>
                    
                            <h3 class="price">₱<?= $product["price"] ?></h3>
                        </div>

                        <div>
                            <button class="add-btn" name="add-to-cart" value=<?= $product["product_id"] ?>>Add to cart</button>
                        </div>
                            
                    </div>
                <?php } ?>
                <?php } else { ?>
                    <h3>No products</h3>
                <?php } ?>
        </main>
    </form>
</body>
</html>
<?php
    include("connection.php");
    session_start();

    $test1 = 123;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-to-cart"]) && !empty($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
        $product_id = $_POST["add-to-cart"];
        echo $product_id;
        try {
            //Find shopping card record
            $find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $find_cart_id->execute();

            if ($find_cart_id->rowCount() == 0) { //No shopping chart
                //Add shopping_cart record for the user
                echo "No cart found, adding shopping_cart record";
                $user_cart = $db->query("INSERT INTO shopping_cart (user_id)
                                         VALUES ($user_id)");
        
            }
            echo "Before";

            $final_find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $final_find_cart_id->execute();

            $cart_id = $final_find_cart_id->fetch();
            $cart_id = $cart_id["cart_id"];
            echo "After";
            //Check if there is an existing cart_id and product_id
            $cart_and_product = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            $cart_and_product->execute();

            if ($cart_and_product->rowCount() == 0) { //Add cart item
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
           echo "test"; 
        }
    }

    //Reduce cart
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reduce-cart"]) && !empty($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
        $product_id = $_POST["reduce-cart"];

        try {
            //Find shopping card record
            $find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $find_cart_id->execute();

            $cart_id = $find_cart_id->fetch();
            $cart_id = $cart_id["cart_id"];
            echo "After";
            //Check if there is an existing cart_id and product_id
            $cart_and_product = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            $cart_and_product->execute();

             //Update quantity of cart item
                echo "Already in cart, updating quantity";
                $old_quantity = $cart_and_product->fetch(PDO::FETCH_ASSOC);
                $old_quantity = $old_quantity["quantity"];

                if ($old_quantity > 1) {
                    $new_quantity = $old_quantity - 1;
                    $cart_item = $db->prepare("UPDATE cart_item SET quantity = :quantity WHERE cart_id = :cart_id AND  product_id = :product_id");
                    $cart_item->bindParam(":quantity", $new_quantity);
                    $cart_item->bindParam(":cart_id", $cart_id);
                    $cart_item->bindParam(":product_id", $product_id);
                   
                } else {
                    $cart_item = $db->prepare("DELETE FROM cart_item WHERE cart_id = :cart_id AND product_id = :product_id");
                    $cart_item->bindParam(":cart_id", $cart_id);
                    $cart_item->bindParam(":product_id", $product_id);
                }

                $cart_item->execute();

        } catch (PDOException $e) {
           echo $e->getMessage();
           echo "test"; 
        }
    }


    if (empty($_SESSION["id"])) { //Display all items
        $user_id = null;
        $all_products = null;

        try {
            $stmt = $db->query("SELECT * FROM product");
            $stmt->execute();

            $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);   

        }  catch(PDOException $e) {
            echo $e->getMessage();
        }
    } else { //Displays the quantity of item in the cart of User

        try {
            $find_qty_per_item = $db->prepare("SELECT sc.user_id, p.product_id, p.name, p.description, p.price, p.image, ci.quantity FROM product p CROSS JOIN shopping_cart sc LEFT JOIN cart_item ci ON p.product_id = ci.product_id AND sc.cart_id = ci.cart_id WHERE sc.user_id = :id ORDER BY sc.user_id, p.product_id");
            $find_qty_per_item->bindValue(":id", $_SESSION["id"]);
            $find_qty_per_item->execute();
    
            $all_products = $find_qty_per_item->fetchAll(PDO::FETCH_ASSOC);

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

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/b0d1390b7c.js" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>BakeMaster | Menu</title>
</head>
<body>
    <?php include("header.php") ?>

    <h2 class="menu-title">MENU</h2>
    <input type="search" name="filter" id="filter" oninput="filterJS()" placeholder="Search">

    <form class="add-to-cart-form">
        <main>
            <p id="no-item" style="display: none;"></p>
            <!-- Shows all product without QTY | Not logged in -->
            <?php if (empty($_SESSION["id"])) { ?>

                <?php foreach ($all_products as $product) { ?>
                    <div class="menu-container">
                        <div class="image-container">
                            <img src="products/<?= rawurlencode($product["image"]) ?>" alt=<?= $product["name"] ?>>
                        </div>

                        <div>
                            <h2 class="product-name"><?= $product["name"] ?></h2>
                            <h3 class="price">₱<?= $product["price"] ?></h3>
                            <p class="description"><?= $product["description"] ?></p>
                        </div>

                        <div class="add-container">
                            <button type="button" class="temp-btn">Add to cart</button>
                        </div>      
                    </div>
                <?php } ?>

            <?php } else { ?>
                
                <!-- Logged In -->
                <?php foreach ($all_products as $product) { ?>

                    <div class="menu-container">
                        <div class="image-container">
                            <img src="products/<?= rawurlencode($product["image"]) ?>" alt=<?= $product["name"] ?>>
                        </div>

                        <div>
                            <h2 class="product-name"><?= $product["name"] ?></h2>
                            <h3 class="price">₱<?= $product["price"] ?></h3>
                            <p class="description"><?= $product["description"] ?></p>
                        </div>

                        <div class="add-container">

                                <?php if (isset($product["quantity"])) { ?>
                                    <!-- <button type="button" class="decrement-btn" style="background-color: white;"" data-productid=<?= $product["product_id"]?>><i class="fa-solid fa-minus"></i></button> -->
                                    <i class="fa-solid fa-minus decrement-btn" data-productid=<?= $product["product_id"]?>></i>

                                    <button type="button" class="add-to-cart-btn"><?= $product["quantity"] ?></button>
                                    <i class="fa-regular fa-plus increment-btn" data-productid=<?= $product["product_id"]?>></i>
<!--                                     <button type="button" class="increment-btn" style="background-color: white;" data-productid=<?= $product["product_id"]?>><i class="fa-regular fa-plus"></i></button>-->
                                     <?php } else { ?>
                                    
                                    <button type="button" class="add-to-cart-btn" data-productid=<?= $product["product_id"] ?>>Add to cart</button>

                                <?php } ?>

                        </div>      
                    </div>

                <?php } ?>

            <?php } ?>

        
        </main>
    </form>

    <div class="popup-container">
        <div class="popup">
            <span class="close">&times;</span>
            <h2>Please login or create an account to add items to your cart.</h2>
            <a href="login.php" class="login-link">Login</a>
        </div>
    </div>


    <?php include("footer.html") ?>




    <script src="JS/global.js"></script>
    <script src="JS/menu.js"></script>

    <script>
    const tempBtn = document.querySelectorAll(".temp-btn")
    const popupContainer = document.querySelector(".popup-container");
    const popup = document.querySelector(".popup");
    const closeSymbol = document.querySelector(".close")

         <?php if (empty($_SESSION["id"])) { ?>
            for (const btn of tempBtn) {
                btn.addEventListener("click", (e) => {
                    popupContainer.style.display = "block";
                })

                closeSymbol.addEventListener("click", (e) => {
                    popupContainer.style.display = "none";
                })
            }
        <?php } ?>

    </script>
</body>
</html>
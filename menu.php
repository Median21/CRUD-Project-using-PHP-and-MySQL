<?php
    include("connection.php");
    session_start();

    

    if (empty($_SESSION["id"])) {
        $user_id = null;
    } else {
        $user_id = $_SESSION["id"];
    }

    
    $result = null;

    try {
        $stmt = $db->query("SELECT * FROM product");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

    }  catch(PDOException) {
        
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-to-cart"]) && !empty($_SESSION["id"])) {
        $product_id = $_POST["add-to-cart"];

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
           echo "test"; 
        }
    }

    $test = "Hello";

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

    <h2 class="menu-title">MENU</h2>
    <form action="menu.php" method="post" class="add-to-cart-form">
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
                            <p class="description"><?= $product["description"] ?></p>
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

    <div class="popup-container">
        <div class="popup">
            <span class="close">&times;</span>
            <h2>Please login or create an account to add items to your cart.</h2>
            <a href="login.php" class="login-link">Login</a>
        </div>
    </div>


    <?php include("footer.html"); ?>

    <script src="JS/global.js"></script>

    <script>
        let addBtns = document.querySelectorAll(".add-btn");
        let popupContainer = document.querySelector(".popup-container");
        let popup = document.querySelector(".popup");
        let closeSymbol = document.querySelector(".close")

        let form = document.querySelector(".add-to-cart-form");
        

        addBtns.forEach(btn => {
            btn.addEventListener("click", (event) => {
                <?php if (empty($user_id)) {?>
                    event.preventDefault();
                    console.log("Not logged in");
                    popupContainer.style.display = "block";
                <?php } ?>
            })
        })

            popupContainer.addEventListener("click", (e) => {
                popupContainer.style.display = "none";
                e.stopPropagation();
       
            })

            /* document.body.addEventListener("click", (e) => {
                console.log("Current Target : " , e.currentTarget);
                console.log("Target: " , e.target);


                if (e.target.classList.contains("popup-container")) {
                    popupContainer.style.display = "none";
                }
            }) */

    



        closeSymbol.addEventListener("click", (e) => {
            popupContainer.style.display = "none";
        })

        

    </script>
</body>
</html>
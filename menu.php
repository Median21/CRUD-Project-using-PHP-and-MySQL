<?php
    include("connection.php");
    session_start();

    if (empty($_SESSION["id"])) {
        $user_id = null;
    } else {
        $user_id = $_SESSION["id"];
    }

    //Display all items
    $result = null;
    try {
        $stmt = $db->query("SELECT * FROM product");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

    }  catch(PDOException $e) {
        echo $e->getMessage();
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

    //Reduce cart
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reduce-cart"]) && !empty($_SESSION["id"])) {
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
                $new_quantity = $old_quantity - 1;
                $cart_item = $db->prepare("UPDATE cart_item SET quantity = :quantity WHERE cart_id = :cart_id AND  product_id = :product_id");
                $cart_item->bindParam(":quantity", $new_quantity);
                $cart_item->bindParam(":cart_id", $cart_id);
                $cart_item->bindParam(":product_id", $product_id);
                $cart_item->execute();

        } catch (PDOException $e) {
           echo $e->getMessage();
           echo "test"; 
        }
    }









    //Displays the quantity of item in the cart of User
    try {
        $find_qty_per_item = $db->prepare("SELECT sc.user_id, p.product_id, ci.quantity FROM product p CROSS JOIN shopping_cart sc LEFT JOIN cart_item ci ON p.product_id = ci.product_id AND sc.cart_id = ci.cart_id WHERE sc.user_id = :id ORDER BY sc.user_id, p.product_id");
        $find_qty_per_item->bindValue(":id", 23);
        $find_qty_per_item->execute();

        $qty_per_item = $find_qty_per_item->fetchAll();

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
    <link rel="stylesheet" href="CSS/menu.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>BakeMaster | Menu</title>
</head>
<body>
    <?php include("header.php") ?>

  
    <div class="test">

    </div>

    <h2 class="menu-title">MENU</h2>

    <input type="search" name="filter" id="filter" oninput="filterJS()" placeholder="Search">


    <form class="add-to-cart-form">
        <main>

            <p id="no-item" style="display: none;"></p>
                <?php if (!empty($result)) {?>
                <?php foreach ($result as $key => $product) {?>
                    <?php foreach ($qty_per_item as $cart_item) { ?>
                        <?php if ($product["product_id"] == $cart_item["product_id"]) { ?>

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
                                        <button type="button" style="background-color: white;" onclick="decrementCart(<?=$product['product_id']?>)">-</button>
                                        <button type="button" class="add-btn" onclick="addToCart(<?= $product['product_id'] ?>)"><?= $cart_item["quantity"]?></button>
                                        <button type="button" class="increment-btn" style="background-color: white;" onclick="addToCart(<?=$product['product_id']?>)">+</button>
                                
                                </div>      
                            </div>

                            <?php } ?>
                        <?php } ?>

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


    <?php include("footer.html") ?>


    <script src="JS/global.js"></script>

 <script>
    console.log(document.querySelector(".add-btn").innerHTML)
        let addBtns = document.querySelectorAll(".add-btn");
        let incrementBtns = document.querySelectorAll(".increment-btn");
        let popupContainer = document.querySelector(".popup-container");
        let popup = document.querySelector(".popup");
        let closeSymbol = document.querySelector(".close")

        let searchBar = document.getElementById("filter-menu");
        let filterForm = document.querySelector(".filter-form")
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

        closeSymbol.addEventListener("click", (e) => {
            popupContainer.style.display = "none";
        })

      

        incrementBtns.forEach(plus => {
            plus.addEventListener("click", () => {
                for (let i = 0; i < incrementBtns.length; i++) {
                    if (plus == incrementBtns[i]) {
                        incrementQty(i)

                    } else {
                        console.log("not this");
                    }
                }


            })
        })


        function incrementQty(i) {
            let addBtnCart = document.querySelectorAll(".add-btn")[i];
            let newText = addBtnCart.textContent.replace("+", "");
            addBtnCart.textContent = Number(++newText);   
        }

        function decrementQty(i) {
            let addBtnCart = document.querySelector(".add-btn");
            let newText = addBtnCart.textContent.replace("+", "");
            addBtnCart.textContent = Number(--newText);     
        }
    
        function addToCart(id){ 
                $.ajax({
                type: 'POST',
                url: 'menu.php',
                data:{'add-to-cart': id},
                success : function (data) {
                }
            }) 
        }

        function decrementCart(id){ 
                $.ajax({
                type: 'POST',
                url: 'menu.php',
                data:{'reduce-cart': id},
                success : function (data) {
                }
            }) 
        }



        let allMenu = document.querySelectorAll(".menu-container");
        let allProductName = document.querySelectorAll(".product-name");
        let filterValue = document.querySelector("#filter");

        
        function filterJS() {
            console.log("INPUT:", filterValue.value)
            let allItemStatus = [];

            for (let i = 0; i < allProductName.length; i++) {
                if (allProductName[i].textContent.toLowerCase().includes(filterValue.value) && filterValue.value !== "") {
                    console.log("Found");
                    document.getElementById('no-item').style.display = "none";
                    console.log(allProductName[i].textContent);
                    allMenu[i].style.display = "flex";
                    allItemStatus.push("true");
                  
                } else if (filterValue.value == "" || filterValue.value == " ") {
                    document.getElementById('no-item').style.display = "none";
                    allMenu[i].style.display = "flex";
                    allItemStatus.push("true");
                    console.log(allItemStatus);
                    console.log("ALL");
                } else {
                    console.log("HIDE THIS: ", allProductName[i].textContent)
                    allMenu[i].style.display = 'none';
                    allItemStatus.push("false");

                }
            }

            if (!allItemStatus.includes("true")) {
                document.getElementById('no-item').style.display = "inline";
                document.getElementById('no-item').textContent = "No item found";
            }
        }

    
       

    </script>
</body>
</html>
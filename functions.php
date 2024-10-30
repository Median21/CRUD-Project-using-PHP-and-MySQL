<?php
    include("connection.php");

    function add_to_cart($db, $product_id, $user_id) {

        /* $user_id = $_SESSION["id"]; */
       /*  $product_id = $_POST["add-to-cart"]; */

        try {
            //Find shopping cart record
            $find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $find_cart_id->execute();

            if ($find_cart_id->rowCount() == 0) { //No shopping chart
                //Add shopping_cart record for the user
                echo "No cart found, adding shopping_cart record";
                $user_cart = $db->query("INSERT INTO
                                            shopping_cart (user_id)
                                         VALUES 
                                            ($user_id)");
        
            }

            //Finds the shopping_cart of the USER
            $final_find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $final_find_cart_id->execute();
            /* cart_id | user_id */

            //Gets the cart_id
            $cart_id = $final_find_cart_id->fetch();
            $cart_id = $cart_id["cart_id"];
            /* cart_id */

            //Check if there is an existing cart_id and product_id
            $cart_and_product = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            $cart_and_product->execute();

            if ($cart_and_product->rowCount() == 0) { //Add cart item
                //For cart_item TABLE (ADD new cart_item ROW)
                $cart_item = $db->prepare("INSERT INTO cart_item (cart_id, product_id, quantity)
                                           VALUES (:cart_id, :product_id, :quantity)");
                $cart_item->bindValue(":cart_id", $cart_id);
                $cart_item->bindValue(":product_id", $product_id);
                $cart_item->bindValue(":quantity", 1);
                $cart_item->execute();
            } else { //Update quantity of cart item
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


    function reduce_cart($db, $product_id, $user_id) {


      /*   $user_id = $_SESSION["id"];
        $product_id = $_POST["reduce-cart"]; */

        try {
            //Find shopping cart record
            $find_cart_id = $db->query("SELECT * FROM shopping_cart WHERE user_id = '$user_id'");
            $find_cart_id->execute();

            $cart_id = $find_cart_id->fetch();
            $cart_id = $cart_id["cart_id"];

            //Check if there is an existing cart_id and product_id
            $cart_and_product = $db->query("SELECT * FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$product_id'");
            $cart_and_product->execute();

             //Update quantity of cart item
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
        }

    }

    function show_all_menu($db, $session_id) {
  /*       include("connection.php"); */
        if (empty($session_id)) { //Display all items
            $user_id = null;
            $all_products = null;
    
            try {
                $stmt = $db->query("SELECT * FROM product");
                $stmt->execute();
    
                $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);   
                return $all_products;
                

            }  catch(PDOException $e) {
                echo $e->getMessage();
            }
    
        } else { //Displays the quantity of item in the cart of User
    
            try {
               //Checks if user has shopping cart
               $find_user_sc = $db->prepare("SELECT * FROM shopping_cart WHERE user_id = :user_id");
               $find_user_sc->bindValue(":user_id", $session_id);
               $find_user_sc->execute();
    
                if ($find_user_sc->rowCount() == 0) {
                    $stmt = $db->query("SELECT * FROM product");
                    $stmt->execute();
        
                    $all_products = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                    return $all_products;
                
                } else {
                    $find_qty_per_item = $db->prepare("SELECT sc.user_id, p.product_id, p.name, p.description, p.price, p.image, ci.quantity FROM product p CROSS JOIN shopping_cart sc LEFT JOIN cart_item ci ON p.product_id = ci.product_id AND sc.cart_id = ci.cart_id WHERE sc.user_id = :id ORDER BY sc.user_id, p.product_id");
                    $find_qty_per_item->bindValue(":id", $session_id);
                    $find_qty_per_item->execute();
                    $all_products = $find_qty_per_item->fetchAll(PDO::FETCH_ASSOC);
                    return $all_products;
                }
    
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

    }

    $test = 123;
?>
<?php
    include("connection.php");
    session_start();

 

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-product"])) {
        $product_name = $_POST["product-name"];
        $price = $_POST["price"];
        $category = $_POST["category"];
        $description = $_POST["description"];

        //Find latest ID
        $find_max = $db->query("SELECT MAX(product_id) as latestID FROM product");
        $find_max->execute();
        
        //Gets the extension of the file
        $ext = pathinfo($_FILES["file"]["full_path"], PATHINFO_EXTENSION);

        try {
            while ($max = $find_max->fetch()) {
                $next_id = $max["latestID"] + 1; //Increments ID
                $fileName = "image{$next_id}.$ext"; //Sets fileName to imageXX.jpg/png

                //Upload to VSCODE
                $path = "products/image{$next_id}.$ext";
                move_uploaded_file($_FILES["file"]["tmp_name"], $path);
    
                //Puts filename in the DB
                $stmt = $db->prepare("INSERT INTO product (name, price, category, description, image)
                VALUES (:name, :price, :category, :description, :image)");
                $stmt->bindParam(":name", $product_name);
                $stmt->bindParam(":price", $price);
                $stmt->bindParam(":category", $category);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":image", $fileName);
                $stmt->execute();

            }

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
        $delete = $db->query("DELETE FROM product WHERE product_id = {$_POST['delete']}");
        $delete->execute();
    }

    $products = $db->query("SELECT * FROM product");
    $products->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/add-product.css">
    <title>Menu</title>
</head>
<body>
    <?php include("header.php") ?>


    <main>

    <div class="relative-container">
        <section class="manage-products">
            <h2>All Products</h2>
            <button id="show-product-form" title="CTRL Shortcut">+</button>
            <table>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
    
            <?php if ($products->rowCount() > 0) { ?>
                <?php $all_products = $products->fetchAll(PDO::FETCH_ASSOC) ?>
                <?php foreach($all_products as $product) { ?>
                    <tr>
                        <td><img src=products/<?= $product["image"]?> alt="no pic"></td>
                        <td><?= $product["name"] ?></td>
                        <td><?= $product["price"] ?></td>
                        <form action="add-product.php" method="post">
                            <td><button name="delete" class="delete-btn" value="<?= $product['product_id']?>">Delete</button></td>
                        </form>
                    </tr>
                <?php } ?>
            <?php } ?>
            </table>
            
   
        </section>
    
    
    
        <section class="add-container">
            <form action="add-product.php" method="post" class="add-product-form" enctype="multipart/form-data">
                <h2>ADD PRODUCT</h2>
                <label for="product-name">Product Name:</label>
                <input type="text" name="product-name" id="product-name" required>
        
                <label for="price">Price (₱):</label>
                <input type="number" name="price" id="price" step="0.01" required>
        
                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <option value="bread">Bread</option>
                    <option value="cookies">Cookies</option>
                </select>
        
                <label for="description">Description:</label>
                <textarea name="description" id="description" rows="7" required></textarea>
    
    
                <input type="file" name="file" id="file" accept=".jpg, .png" required>
        
                <button name="add-product" class="add-btn">ADD</button>
            </form>
        </section>

    </div>
    </main>

    <?php include("footer.html"); ?>

    <script src="JS/global.js"></script>
    <script>
        const showButton = document.getElementById("show-product-form");
        const singleBtn = document.querySelector(".delete-btn");
        const deleteBtn = document.querySelectorAll(".delete-btn");

        showButton.addEventListener("click", () =>  {
            document.querySelector(".manage-products").classList.toggle("shorten");
            document.querySelector(".add-container").classList.toggle("show-form");
        })

        deleteBtn.forEach(button => {
            button.addEventListener("click", (e) => {
                if (confirm("Are you sure you want to delete the product?")) {
                    /* Submits the form */
                } else {
                   e.preventDefault();
                }
            })
        })
            
        
    



        


    </script>
</body>
</html>
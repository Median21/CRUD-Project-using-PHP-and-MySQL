<?php
    include("connection.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                $stmt = $db->prepare("INSERT INTO product (product_id, name, price, category, description, image)
                VALUES (:id, :name, :price, :category, :description, :image)");
                $stmt->bindValue(":id", "P");
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

    $find_maxs = $db->query("SELECT MAX(price) as latestID FROM product");
    $find_maxs->execute();


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
        <h1>NEW PRODUCT</h1>
        <form action="add-product.php" method="post" class="add-product-form" enctype="multipart/form-data">
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
    
            <button class="add-btn">ADD</button>
        </form>
    </main>

</body>
</html>
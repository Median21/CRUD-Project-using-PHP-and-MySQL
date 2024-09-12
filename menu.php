<?php
    include("connection.php");
   
    $result = null;

    
            $stmt = $db->query("SELECT * FROM product");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/menu.css">
    <title>Document</title>
</head>
<body>
    <?php include("header.php") ?>
    <main>
        <?php if (!empty($result)) {?>
        <?php foreach ($result as $product) {?>
            <div class="menu-container">
                <div class="image-container">
                    <img src="products/<?= rawurlencode($product["image"]) ?>" alt="test">
                </div>

                <div>
                    <h2 class="product-name"><?= $product["name"] ?></h2>
                    <p class="description"><?= $product["description"] ?></p>
                    <h3 class="price">₱<?= $product["price"] ?></h3>
                    <button class="add-btn">Add to cart</button>
                </div>
            </div>
        <?php } ?>
        <?php } else { ?>
            <h3>No products</h3>
        <?php } ?>

    </main>
</body>
</html>
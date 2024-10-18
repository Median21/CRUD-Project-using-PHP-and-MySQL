<?php
    include("connection.php");
    session_start();

        $best_sellers = $db->query("SELECT 
                                        order_details.product_id,
                                        SUM(quantity),
                                        product.name,
                                        product.price,
                                        product.image,
                                        orders.status
                                    FROM 
                                        order_details
                                    INNER JOIN product ON order_details.product_id = product.product_id
                                    INNER JOIN orders ON orders.order_id = order_details.order_id
                                    WHERE
                                        status = 'Completed'
                                    GROUP BY 
                                        product_id
                                    ORDER BY
                                        SUM(quantity)
                                    DESC
                                    LIMIT 3");
        $best_sellers->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/indexs.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    <title>BakeMaster | Home</title>
</head>
<body>
    <?php include("header.php") ?>
    <main>
        <section class="hero" data-aos="fade-up">
            <div class="left-hero">
                <h1>Welcome to BakeMaster!</h1>
                <p>Whether you're craving a flaky croissant, a rich chocolate cake, or a slice of our famous sourdough, you'll find it here.</p>
                <button class="explore-btn" onclick="document.getElementById('menu').scrollIntoView()">Explore now</button>
            </div>

            <div class="right-hero">
                <img src="./images/hero-image.jpg" alt="Slice of cake" class="hero-img">
            </div>
        </section>
        
        <section class="menu-section" id="menu" data-aos="fade-out">
            <div class="section-title">
                <h2>Our Top Menu</h2>
                <p>Our most ordered product!</p>
                <a href="menu.php" id="view-more">View More</a>
            </div>

            <div class="top-menu">
                <?php if ($best_sellers) { ?>
                    <?php foreach($best_sellers as $best_seller) { ?>
                    <div class="food-container">
                        <img src=products/<?= $best_seller["image"] ?> alt="a">
                            <h3> <?= $best_seller["name"] ?> </h3>
                            <p>Sold: <?= $best_seller["SUM(quantity)"] ?> </p>
                            <p>₱<?= $best_seller["price"] ?></p>
                            <button class="add-to-cart-btn">Add to cart</button>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </section>
    </main>

    <?php include("footer.html") ?>

    <script src="JS/global.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
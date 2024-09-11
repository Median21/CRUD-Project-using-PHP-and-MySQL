<?php
    include("connection.php");
    session_start();

    if (empty($_SESSION)) {
        echo "Not Logged in";
    } else {
        echo "Logged in";
    }
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    <title>Bakery Shop</title>
</head>
<body>
    <?php include("header.php") ?>

    <main>
        <section class="hero" data-aos="fade-up">
            <div class="left-hero">
                <h1>Welcome to BakeMaster!</h1>
                <p>Whether you’re craving a flaky croissant, a rich chocolate cake, or a slice of our famous sourdough, you’ll find it here.</p>
                <button class="explore-btn" onClick="document.getElementById('menu').scrollIntoView()">Explore now</button>
            </div>

            <div class="right-hero">
                <img src="./images/hero-image.jpg" alt="" class="hero-img">
            </div>
        </section>
        
        <section class="menu-section" id="menu" data-aos="fade-out">
            <div class="section-title">
                <h2>Our Top Menu</h2>
                <p>Our most ordered product!</p>
                <button id="view-more-btn">View More</button>
            </div>

            <div class="top-menu">
                <div class="food-container">
                    <img src="images/Menu/image-meringue-desktop.jpg" alt="">
                    <h3>Food 1</h3>
                    <p>This is food 1</p>
                    <p>$1.50</p>
                    <button class="add-to-cart-btn">Add to cart</button>
                </div>

                <div class="food-container">
                    <img src="images/Menu/image-meringue-desktop.jpg" alt="">
                    <h3>Food 2</h3>
                    <p>This is food 2</p>
                    <p>$2.50</p>
                    <button class="add-to-cart-btn">Add to cart</button>
                </div>

                <div class="food-container">
                    <img src="images/Menu/image-meringue-desktop.jpg" alt="">
                    <h3>Food 3</h3>
                    <p>This is food 3</p>
                    <p>$10.25</p>
                    <button class="add-to-cart-btn">Add to cart</button>
                </div>
            </div>
            
        </section>   


    </main>
    <?php include("footer.html") ?>



    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>

    <script>
        document.querySelector(".hamburger-menu").addEventListener("click", () => {
            document.querySelector(".mobile-nav").classList.toggle("active");
            console.log("TEST")
        })

        document.getElementById("logout-dropdown").addEventListener("click", () => {
            document.querySelector("form").submit();
        })
    </script>
</body>
</html>
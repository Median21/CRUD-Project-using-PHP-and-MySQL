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
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/index.css">
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
            <h2>MENU</h2>
            <hr>

            <div class="menu-category">
                <h3>Bread</h3>
                <div class="category-item">
                    <div>
                        <img src="images/Menu/image-brownie-desktop.jpg" alt="">
                        <p>Chocolate CAKE</p>
                    </div>

                    <div>
                        <img src="images/Menu/image-brownie-desktop.jpg" alt="">
                        <p>TEST</p>
                    </div>
                </div>
            </div>
            
            <div class="menu-category">
                <h3>Cakes</h3>
                <div class="category-item">
                    <img src="images/Menu/image-cake-desktop.jpg" alt="">
                </div>
            </div class="menu-category">


            <div>
                <h3>Cookies</h3>
                <div class="category-item">
                    <img src="images/Menu/image-waffle-desktop.jpg" alt="">
                </div>
            </div>

            <div class="menu-category">
                <h3>Something</h3>
                <div class="category-item">
                    <img src="images/Menu/image-tiramisu-desktop.jpg" alt="">
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
        document.getElementById("logout-dropdown").addEventListener("click", () => {
            document.querySelector("form").submit();
        })
    </script>
</body>
</html>
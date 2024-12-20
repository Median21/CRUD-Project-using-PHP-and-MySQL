<?php
    include("connection.php");

    (empty($_SESSION) ? $logged_in = false : $logged_in = true);
?>

    <header data-aos="fade-in">
        <nav class="nav">
            <div class="logo-name-container">
                <a href="index.php" class="logo-link">
                    <img src="images/bakery-logo.jpg" alt="A whisk and a rolling pin" class="logo">
                </a>

                <h1 class="business-name">
                    <a href="index.php" id="home-link">BakeMaster</a>
                </h1>
            </div>

            <ul class="desktop-nav">
                <li class="list-item"><a href="index.php">Home</a></li>
                <li class="list-item"><a href="menu.php">Menu</a></li>
            
                <?php if (!$logged_in) { ?>
                    <li class="list-item"><a href="register.php">Sign up</a></li>
                    <li class="list-item"><a href="login.php">Login</a></li>

                <?php } elseif ($logged_in) { ?>
                    <?php if ($_SESSION["type"] == "Customer") { ?>
                        <li class="list-item"><a href="cart.php">Cart <i class="fa fa-shopping-cart"></i></a></li>
                        <li class="list-item"><a href="orders.php">Orders</a></li>
                    <?php } elseif ($_SESSION["type"] == "Admin") { ?>
                        <li class="list-item"><a href="admin-dashboard.php">Dashboard</a></li>
                        <li class="list-item"><a href="add-product.php">Add</a></li>
                        <li class="list-item"><a href="accounts.php">Accounts</a></li>
                    <?php } ?>
                    <li class="list-item" class="dropdown-list"><a>More &#x25BE;</a>
                    <ul class="dropdown">
                        <li><a href="profile.php">Profile</a</li>
                        <li><a href="">Settings</a></li>
                        <form action="./login.php" method="post" class="logout-form">
                            <li><a class="logout-dropdown">Logout</a></li>
                            <input type="hidden" name="logout" value="logout">
                        </form>
                    </ul>
                </li>

                <?php } ?>
            </ul>

            <div class="hamburger-menu">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
    
            <ul class="mobile-nav">
                    <li class="list-item"><a href="index.php">Home</a></li>
                    <li class="list-item"><a href="menu.php">Menu</a></li>

                <?php if ($logged_in) { ?>
                    <li class="list-item"><a href="cart.php">Cart <i class="fa fa-shopping-cart"></i></a></li>
                    <li class="list-item"><a href="add-product.php">Add</a></li>
                    <li class="list-item"><a href="orders.php">Orders</a></li>

                    <form action="./login.php" method="post" class="logout-form">
                        <li class="list-item">
                            <a class="logout-dropdown">Logout</a>
                            <input type="hidden" name="logout" value="logout">
                        </li>
                    </form>

                <?php } else { ?>
                    <li class="list-item"><a href="register.php">Sign up</a></li>
                    <li class="list-item"><a href="login.php">Login</a></li>
                <?php } ?>
                    <li class="list-item"><a href="accounts.php">Accounts</a></li>
            </ul>
        </nav>
    </header>


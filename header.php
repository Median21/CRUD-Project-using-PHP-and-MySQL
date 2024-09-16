<?php
    if (empty($_SESSION)) {
        $logged_in = false;
    } else {
        $logged_in = true;
    }
?>

<header data-aos="fade-in">
    <nav class="nav">
        <div class="logo-name">
            <a href="index.php" class="logo-link"><img src="images/bakery-logo.jpg" alt="" class="logo"></a>
            <a href="index.php"><h1 class="business-name">BakeMaster</h1></a>
        </div>
        <ul class="desktop-nav">
            <li class="list-item"><a href="index.php">Home</a></li>
            <li class="list-item"><a href="menu.php">Menu</a></li>
            <li class="list-item"><a href="add-product.php">Add</a></li>
            <li class="list-item"><a href="cart.php">Cart</a></li>
            <li class="list-item"><a href="register.php">Sign up</a></li>
            <li class="list-item"><a href="login.php">Login</a></li>
            <li class="list-item"><a href="accounts.php">Accounts</a></li>
            <?php if ($logged_in) { ?>
            <li class="list-item" class="dropdown-list"><a>More &#x25BE;</a>
                <ul class="dropdown">
                    <li><a href="profile.php">Profile</a</li>
                    <li><a href="">Settings</a></li>
                    <form action="./login.php" method="post">
                        <li><a id="logout-dropdown" name="logout">Logout</a></li>
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
            <li class="list-item"><a href="index.php#menu">Menu</a></li>
            <li class="list-item"><a href="register.php">Sign up</a></li>
            <li class="list-item"><a href="login.php">Login</a></li>
            <li class="list-item"><a href="accounts.php">Accounts</a></li>
        </ul>
    </nav>
</header>

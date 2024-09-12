<?php
    include("connection.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        try {
            $stmt = $db->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->bindValue(':email', $_POST["email"]);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    if (password_verify($_POST["password"], $row["password"])) {
                        $_SESSION["id"] = $row["id"];
                        $_SESSION["email"] = $_POST["email"];
                        header("Location: index.php");
                    } else {
                        echo "Incorrect credentials <br>";
                    }

                 }
            } else {
                echo "No user found <br>";
            }
    
        } catch (PDOException) {
            echo "Error";
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
    }


    if (empty($_SESSION)) {
        echo "Not logged in <br>";
    } else {
        echo "Logged in <br>";
        echo "Current user: {$_SESSION['email']}";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    <title>Bakery Shop | Login</title>
</head>
<body>
    <?php include("header.php") ?>

    <div class="flex-container">
        <div class="left-side"></div>

        <form action="login.php" method="post" class="login-form">
            <?php if (empty($_SESSION)) {?>
                <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
                <input type="password" name="password" id="password" placeholder="Password">
                <button name="login" class="login">Login</button>
                <p class="or">OR</p>
                <a href="register.php" class="create-account">Create an account</a>
            <?php } else {?>
                <?php echo "<h1>Welcome back, {$_SESSION["email"]}</h1>" ?>
                <button name="logout">Logout</button>
            <?php } ?>
        </form>
    </div>


    
    <script>
        //Logout
        document.getElementById("logout-dropdown").addEventListener("click", () => {document.querySelector("form").submit();})
    </script>
</body>
</html>
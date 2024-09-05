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
    <link rel="stylesheet" href="CSS/header.css">
    <link rel="stylesheet" href="CSS/login.css">
    <title>Bakery Shop | Login</title>
</head>
<body>
    <?php include("header.html") ?>

    <form action="login.php" method="post">

    <?php if (empty($_SESSION)) {?>
        <h1>Login Form</h1>
        <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
        <input type="password" name="password" id="password" placeholder="Password">
        <button name="login">Login</button>
    </form>
    <?php } else {?>
        <?php echo "<h1>Welcome back, {$_SESSION["email"]}</h1>" ?>
        <button name="logout">Logout</button>
    </form>
    <?php } ?>
        
        
    </form>
</body>
</html>
<?php
    include("connection.php");
    session_start();

    $result = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO user (email, password)
                                  VALUES (:email, :password)");
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":password", $password);
            $stmt->execute();
            $result = "User has been registered";
            header("Location: login.php");
        } catch(PDOException) {
            $result = "User can't be registered";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/register.css">
    <title>BakeMaster | Register</title>
</head>
<body>
    <?php include("header.php") ?>

        <form action="register.php" method="post" class="register-form">

            <div class="container">
                <h2>CREATE ACCOUNT</h2>
                <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
                <input type="password" name="password" id="password" placeholder="Password">   
                <button>SIGN UP</button>
                <?php
            if (!empty($result)) {
                echo "<p style=text-align:center>$result</p>";
            }
            ?>
            </div>

        </form>

        
    <?php include("footer.html"); ?>

        <script src="JS/global.js"></script>
</body>
</html>
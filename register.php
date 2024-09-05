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
        } catch(PDOException) {
            $result = "User can't be registered";
        }
    }

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
    <link rel="stylesheet" href="CSS/register.css">
    <title>Bakery Shop | Register</title>
</head>
<body>
    <?php include("header.html") ?>

    <form action="register.php" method="post">
        <h1>Create an account</h1>
            <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
            <input type="password" name="password" id="password" placeholder="Password">

        <button>Sign up</button>

        <?php
            if (!empty($result)) {
                echo "<p>$result</p>";
            }

        ?>
    </form>
</body>
</html>
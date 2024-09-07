<?php
    include("connection.php");
    session_start();


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $stmt = $db->prepare("INSERT INTO user_profile (id, first_name, last_name) 
                                    VALUES (:id, :first_name, :last_name)");
            $stmt->bindParam(":id", $_SESSION["id"]);
            $stmt->bindParam(":first_name", $_POST["first-name"]);
            $stmt->bindParam(":last_name", $_POST["last-name"]);
            $stmt->execute();
            echo "Profile saved successfully";
        } catch (PDOException $e) {
            $stmt = $db->prepare("UPDATE user_profile SET first_name = :first_name, last_name = :last_name WHERE id = :id");
            $stmt->bindParam(":id", $_SESSION["id"]);
            $stmt->bindParam(":first_name", $_POST["first-name"]);
            $stmt->bindParam(":last_name", $_POST["last-name"]);
            $stmt->execute();
            echo "Profile update successfully";
        }
    }


        $stmt = $db->prepare("SELECT * FROM user_profile WHERE id = :id");
        $stmt->bindParam(":id", $_SESSION["id"]);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

    

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
    <link rel="stylesheet" href="CSS/profile.css">
    <title>Document</title>
</head>
<body>
    <?php include("header.php"); ?>
    <form action="profile.php" method="post" class="profile-form">
        <h1>Profile</h1>
        <input type="text" name="first-name" id="first-name" placeholder="First name" value=<?php echo ($row ? $row["first_name"] : "") ?>>
        <input type="text" name="last-name" id="last-name" placeholder="Last name" value=<?php echo ($row ? $row["last_name"] : "") ?>>
        <button name="save" value=<?php $_SESSION["id"]?>>Save</button>

    </form>
</body>
</html>
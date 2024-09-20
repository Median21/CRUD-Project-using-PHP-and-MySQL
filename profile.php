<?php
    include("connection.php");
    session_start();


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $stmt = $db->prepare("INSERT INTO profile (user_id, first_name, last_name, address, contact_number) 
                                    VALUES (:user_id, :first_name, :last_name, :address, :contact_number)");
            $stmt->bindParam(":user_id", $_SESSION["id"]);
            $stmt->bindParam(":first_name", $_POST["first-name"]);
            $stmt->bindParam(":last_name", $_POST["last-name"]);
            $stmt->bindParam(":address", $_POST["address"]);
            $stmt->bindParam(":contact_number", $_POST["contact-number"]);
            $stmt->execute();
            echo "Profile saved successfully";
        } catch (PDOException $e) {
            $stmt = $db->prepare("UPDATE profile SET first_name = :first_name, last_name = :last_name, address = :address, contact_number = :contact_number WHERE user_id = :id");
            $stmt->bindParam(":id", $_SESSION["id"]);
            $stmt->bindParam(":first_name", $_POST["first-name"]);
            $stmt->bindParam(":last_name", $_POST["last-name"]);
            $stmt->bindParam(":address", $_POST["address"]);
            $stmt->bindParam(":contact_number", $_POST["contact-number"]);
            $stmt->execute();
            echo "Profile update successfully";
            echo $e->getMessage();
        }
    }
    
    $stmt = $db->prepare("SELECT * FROM profile WHERE user_id = :id");
    $stmt->bindParam(":id", $_SESSION["id"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/profile.css">
    <title>Document</title>
</head>
<body>
    <?php include("header.php"); ?>
    <form action="profile.php" method="post" class="profile-form">
<!--         <h1 class="form-title">Personal Information</h1> -->
            <label for="first-name">First name:
            <input type="text" name="first-name" id="first-name" placeholder="First name" value=<?php echo ($row ? $row["first_name"] : "") ?> >
            </label>
    
            <label for="last-name">Last name:
                <input type="text" name="last-name" id="last-name" placeholder="Last name" value=<?php echo ($row ? $row["last_name"] : "") ?> >
                </label>
    
            <label for="contact-number">Contact number:
                <input type="text" name="contact-number" id="contact-number" placeholder="Contact number" value=<?php echo ($row ? $row["contact_number"] : "") ?> >
            </label>
    
            <hr>
            
            <label for="address">Current Address:
                <input type="text" name="address" id="address" value=<?php echo ($row ? $row["address"] : "") ?> >
            </label>


        <button name="save">SAVE</button>
    </form>


    <script src="JS/global.js"></script>
</body>
</html>
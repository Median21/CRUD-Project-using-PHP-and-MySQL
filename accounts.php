<?php
    include("connection.php");
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["button"])) {
        try {
            $stmt = $db->prepare("DELETE FROM user WHERE id = :id");
            $stmt->bindValue(":id", $_POST["button"]);
            $stmt->execute();
        } catch (PDOException) {
            echo "Error deleting";
        }
    }


    try {
        $stmt = $db->prepare("SELECT * FROM user");
        $stmt->execute();
       
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } 
        
    } catch (PDOException) {
        echo "Error retrieving Users";
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
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/accounts.css">
    <title>Accounts</title>
</head>
<body>
    <?php include("header.php") ?>

    <?php if (!empty($result)) { ?>
    <form action="accounts.php" method="post">
        <table>
            <caption>Users</caption>
            <tr>
                <th>Email</th>
                <th>Reg Date</th>
                <th>Action</th>
            </tr>
            <?php foreach($result as $row) { ?>
            <tr>
                <td><?= $row["email"] ?></td>
                <td><?= $row["reg_date"] ?></td>
                <td><button name="button" value=<?= $row["id"]?>>Delete</button></td>
            </tr>
            <?php } ?>
        </table>
    </form>
    <?php } else { ?>
        <h2>No Users</h2>
    <?php } ?>

    
    <script>
        //Logout
        document.getElementById("logout-dropdown").addEventListener("click", () => {document.querySelector("form").submit();})
    </script>
</body>
</html>
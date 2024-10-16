<?php
    include("connection.php");
    session_start();

        try {
            $email = $_GET["account"];
            $url_code = $_GET["code"];


            $findCode = $db->prepare("SELECT * FROM user WHERE email = '$email'");
            $findCode->execute();

            $row = $findCode->fetch(PDO::FETCH_ASSOC);
  

            if ($row["email"] == $email  && $row["code"] == $url_code && $row["verified"] == false) {
                try {
                    $activate_account = $db->prepare("UPDATE user SET verified = :verified WHERE email = :email");
                    $activate_account->bindValue(":verified", true);
                    $activate_account->bindValue(":email",  $email);
                    $activate_account->execute();
                    echo "Account is now verified!";

                    

                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

            } else {
                echo "Already verified / Link invalid";
            }
       
        

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    

?>

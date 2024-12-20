<?php
    include("connection.php");
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
    $dotenv->load();

    $result = "";
    
    if (isset($_SESSION["temp_email"])) {
        echo "Temp Email: " . $_SESSION['temp_email'];
        unset($_SESSION["temp_email"]);
     }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $random_code = rand(0,999999);

        try {
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $type = $_POST["type"];

            $stmt = $db->prepare("INSERT INTO user (email, password, type, code)
                                  VALUES (:email, :password, :type, :code)");
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":password", $password);
            $stmt->bindValue(":type", $type);
            $stmt->bindValue(":code", $random_code);
            $stmt->execute();
       
 
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV["GMAIL_EMAIL"];
            $mail->Password = $_ENV["APP_PASS"];
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
    
            $mail->setFrom($_ENV["GMAIL_EMAIL"]);
    
            $mail->addAddress($email);
    
            $mail->isHTML(true);
    
            $mail->Subject = "Please verify your account";
            $mail->Body = 
            "
            Click the link to verify your account
                <br>
                <a href=http://localhost/CRUD-Project-using-PHP-and-MySQL/verify.php?account=$email&code=$random_code>VERIFY ACCOUNT</a>
                <br>
            ";
    
            $mail->send();

            $result = "User has been registered <br> An email verification has been sent to your email";

            $_SESSION['temp_email'] = $_POST['email'];
            header("Location: register.php");
            exit;
            echo "Sent";
      
        } catch(PDOException $e) {
            echo $e->getMessage();
            $result = "User can't be registered";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Cart Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="CSS/global.css">
    <link rel="stylesheet" href="CSS/headers.css">
    <link rel="stylesheet" href="CSS/register.css">
    <title>BakeMaster | Register</title>
</head> 
<body>
    <?php include("header.php") ?>

        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post" class="register-form">
            <div class="container">
                <h2 id="create-account">CREATE ACCOUNT</h2>
                <select name="type" id="type">
                    <option>Customer</option>
                    <option>Admin</option>
                </select>
                <input type="email" name="email" id="email" placeholder="Email" autocomplete="off">
                <input type="password" name="password" id="password" placeholder="Password">   
                <button>SIGN UP</button>
    
                <?php if (!empty($result)) { ?>
                    <p style="text-align: center;"><?= $result ?></p>
                <?php } ?>
            </div>
        </form>

    <?php include("footer.html"); ?>

    <script src="JS/global.js"></script>
    <script>
        const accountType = document.getElementById("type");

    </script>
</body>
</html>
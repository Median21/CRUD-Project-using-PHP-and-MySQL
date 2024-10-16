<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';

    if (isset($_POST["send"])) {

 
            $mail = new PHPMailer(true);
    
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = "anslydy77@gmail.com";
            $mail->Password = "zeyl qljq rxpk ocaz"; //App pass
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
    
            $mail->setFrom("anslydy77@gmail.com");
    
            $mail->addAddress(($_POST["email"]));
    
            $mail->isHTML(true);
    
            $mail->Subject = $_POST["subject"];
            $mail->Body = $_POST["message"];
    
            $mail->send();
    
            echo "Sent";
      

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="test.php" method="post">
        Email
        <input type="text" name="email" id="email">
        Subject
        <input type="text" name="subject" id="subject">
        Message
        <input type="text" name="message" id="message">

        <button name="send">Send</button>
    </form>
</body>
</html>
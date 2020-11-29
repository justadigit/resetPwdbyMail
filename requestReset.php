<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'config/dbh.inc.php';
// Instantiation and passing `true` enables exceptions

  if(isset($_POST['email'])){
    $mailTo = $_POST['email'];
    $code = uniqid(true);

    $stmt = mysqli_stmt_init($conn);
    $sql = "INSERT INTO resetPasswords(code,email) VALUES(?,?);";

    if(!mysqli_stmt_prepare($stmt,$sql)){
      exit("Mail Error");
    }

    mysqli_stmt_bind_param($stmt,"ss",$code,$mailTo);
    mysqli_stmt_execute($stmt);
    //printf("%d Row Inserted.", mysqli_stmt_affected_rows($stmt));
    //close resource
    mysqli_stmt_close($stmt);

    
    $mail = new PHPMailer(true);
    

    try {
        // //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'your email';                     // SMTP username
        $mail->Password   = 'your password';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('serveratzero@gmail.com', 'Server 0');
        $mail->addAddress($mailTo);               // Name is optional
        // $mail->addReplyTo('no-reply@serveratzero@gmail.com', 'No reply');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);
        $url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/resetPassword.php?code=".$code;
        // Set email format to HTML
        $mail->Subject = 'Reset Password Link';
        $mail->Body    = '<h1>You have password request Link</h1>';
        $mail->Body    .= '<p>Click <a href='.$url.'>the link</a> to reset your password';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo '<h3> Reset Password Link was sent to the Mail!</h3>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Pasword</title>
</head>

<body>
  <form action="" method="POST">
    <input type="email" name="email" placeholder="Enter Reset Email ..." autocomplete="off">
    <br><br>
    <button type="submit" name="submit">Reset</button>
  </form>
</body>

</html>
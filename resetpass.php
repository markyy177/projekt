<?php
require_once "kapcsolat.php";
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["forgot_password"])) {
    $email = $_POST['email'];
    
    $query="SELECT * FROM adatok WHERE email =:email";
    
            $lekerdez = $kapcsolat -> prepare($query);
            $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
            $lekerdez -> execute();
            $user = $lekerdez -> fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $reset_token = bin2hex(random_bytes(50));
                $expiry = date("Y-m-d H:i:s",strtotime("+1 hour"));
                $query="UPDATE adatok SET reset_token = :reset_token, reset_token_expiry = :expiry WHERE email = :email";
                $lekerdez = $kapcsolat -> prepare($query);
                $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
                $lekerdez -> bindParam(':reset_token',$reset_token,PDO::PARAM_STR);
                $lekerdez -> bindParam(':expiry',$expiry,PDO::PARAM_STR);
                $lekerdez -> execute();

                $mail = new PHPMailer(true); try { // Server settings 
                    echo"teszt";
                $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true; $mail->Username = 'ajanlokonyv7@gmail.com'; // Your Gmail address 
                $mail->Password = 'cgibuvtzpmvzyznd'; // Use App Password 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587; // Sender & Recipient 
                $mail->setFrom('ajanlokonyv7@gmail.com', 'Your Name'); $mail->addAddress($email, $username); // Recipient // Email content 
                $mail->isHTML(true); 
                    $mail->Subject = 'Elfelejtett jelszó'; 
                    $mail->Body = "<h3><a href='http://localhost/regist-login/reset_password.php?token=$reset_token'>Kattintson a linkre az új jelszó megadásához</a></h3>"; 
                    $mail->AltBody = 'Ez az email tartalmának szöveges változata.'; // Send email 
                $mail->send(); echo 'Email has been sent successfully!'; } catch (Exception $e) { echo "Email sending failed. Error: {$mail->ErrorInfo}"; }
                        echo"sikeres email küldés";
            }
            else{
                echo"Hibás email";
            }
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST">
            Email cím: <input type="email" name="email" required><br>
            <button type="submit" name="forgot_password">Jelszó visszaállítása</button>         
        </form>
</body>
</html>
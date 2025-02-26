<?php
require_once "kapcsolat.php";
use PHPMailer\PHPMailer\PHPMailer; use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
//include "send_email.php";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reg"])) {
    $email = $_POST['email'];
    $query0="SELECT COUNT(*) FROM adatok WHERE email= :email;";
    $lekerdez = $kapcsolat -> prepare($query0);
    $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
    $lekerdez -> execute();
    if ($lekerdez -> fetchColumn()==1) {
        echo"ez az email már foglalt";
    }
    else{
    
        $felhasznalo = $_POST['user'];
        $jelszo = password_hash($_POST['pass'],PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(50));
        if(!empty($felhasznalo)&&!empty($email)){
            $query="INSERT INTO adatok (felhasznalonev, email, jelszo, megerositve, token) VALUES (:felhasznalonev, :email, :jelszo, 0, :token);";
        $lekerdez = $kapcsolat -> prepare($query);
        $lekerdez -> bindParam(':felhasznalonev',$felhasznalo,PDO::PARAM_STR);
        $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
        $lekerdez -> bindParam(':jelszo',$jelszo,PDO::PARAM_STR);
        $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
        if($lekerdez -> execute()){
            echo"Sikeres regisztráció";
        }
        else{
            echo"Hiba történt";
        }
        $mail = new PHPMailer(true); try { // Server settings 
            echo"teszt";
        $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true; $mail->Username = 'ajanlokonyv7@gmail.com'; // Your Gmail address 
        $mail->Password = 'cgibuvtzpmvzyznd'; // Use App Password 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587; // Sender & Recipient 
        $mail->setFrom('ajanlokonyv7@gmail.com', 'Your Name'); $mail->addAddress($email, $username); // Recipient // Email content 
        $mail->isHTML(true); 
            $mail->Subject = 'Regisztráció megerősítése'; 
            $mail->Body = "<h3><a href='http://localhost/regist-login/confirm.php?token=$token'>Kérjük, erősítse meg regisztrációját</a></h3>"; 
            $mail->AltBody = 'Ez az email tartalmának szöveges változata.'; // Send email 
        $mail->send(); echo 'Email has been sent successfully!'; } catch (Exception $e) { echo "Email sending failed. Error: {$mail->ErrorInfo}"; }
                echo"sikeres email küldés";
            }
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
    <h1>Regisztráció</h1>
        <form method="POST">
            Email cím: <input type="email" name="email" required><br>
            Felhasználónév: <input type="text" name="user"><br>
            Jelszó: <input type="password" name="pass"><br>
            <button type="submit" name="reg">regisztráció</button>
        </form>
        
   
</body>
</html>

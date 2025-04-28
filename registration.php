<?php
require_once "kapcsolat.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reg"])) {
    $email = $_POST['email'];
    $query0 = "SELECT COUNT(*) FROM adatok WHERE email = :email";
    $lekerdez = $kapcsolat->prepare($query0);
    $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
    $lekerdez->execute();
    
    if ($lekerdez->fetchColumn() == 1) {
        $error = "Ez az email már foglalt";
    } else {
        $felhasznalo = $_POST['user'];
        $jelszo = password_hash($_POST['pass'], PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(50));
        
        if (!empty($felhasznalo) && !empty($email)) {
            $query = "INSERT INTO adatok (felhasznalonev, email, jelszo, megerositve, token, role) VALUES (:felhasznalonev, :email, :jelszo, 0, :token, 'user')";
            $lekerdez = $kapcsolat->prepare($query);
            $lekerdez->bindParam(':felhasznalonev', $felhasznalo, PDO::PARAM_STR);
            $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
            $lekerdez->bindParam(':jelszo', $jelszo, PDO::PARAM_STR);
            $lekerdez->bindParam(':token', $token, PDO::PARAM_STR);
            
            if ($lekerdez->execute()) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ajanlokonyv7@gmail.com';
                    $mail->Password = 'cgibuvtzpmvzyznd';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    $mail->setFrom('ajanlokonyv7@gmail.com', 'Your Name');
                    $mail->addAddress($email, $felhasznalo);
                    
                    $mail->isHTML(true);
                    $mail->Subject = 'Regisztráció megerősítése';
                    $mail->Body = "<h3><a href='http://localhost/projekt/confirm.php?token=$token'>Kérjük, erősítse meg regisztrációját</a></h3>";
                    $mail->AltBody = 'Ez az email tartalmának szöveges változata.';
                    
                    $mail->send();
                    header("Location: bejelentkezes.php");
                    exit();
                } catch (Exception $e) {
                    $error = "Email küldése sikertelen. Hiba: {$mail->ErrorInfo}";
                }
            } else {
                $error = "Hiba történt a regisztráció során";
            }
        } else {
            $error = "Minden mezőt ki kell tölteni";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            padding: 1rem;
            border: 1px solid #d0d7e1;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button[type="submit"] {
            background-color: #3498db;
            color: #fff;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 400;
            transition: background-color 0.3s, transform 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        a {
            color: #3498db;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .login-link {
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .login-btn {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
        }

        .error {
            color: #dc3545;
            text-align: center;
            margin-top: 1rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Regisztráció</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email cím" required>
            <input type="text" name="user" placeholder="Felhasználónév" required>
            <input type="password" name="pass" placeholder="Jelszó" required>
            <button type="submit" name="reg">Regisztráció</button>
            <div class="login-link">
                Már van fiókod? 
                <button class="login-btn"><a href="bejelentkezes.php">Bejelentkezés</a></button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
require_once "kapcsolat.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reg"])) {
    $email = $_POST['email'];
    $query0 = "SELECT COUNT(*) FROM adatok WHERE email = :email;";
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
            $query = "INSERT INTO adatok (felhasznalonev, email, jelszo, megerositve, token) VALUES (:felhasznalonev, :email, :jelszo, 0, :token);";
            $lekerdez = $kapcsolat->prepare($query);
            $lekerdez->bindParam(':felhasznalonev', $felhasznalo, PDO::PARAM_STR);
            $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
            $lekerdez->bindParam(':jelszo', $jelszo, PDO::PARAM_STR);
            $lekerdez->bindParam(':token', $token, PDO::PARAM_STR);
            
            if ($lekerdez->execute()) {
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'ajanlokonyv7@gmail.com'; // Your Gmail address
                    $mail->Password = 'cgibuvtzpmvzyznd'; // Use App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    // Sender & Recipient
                    $mail->setFrom('ajanlokonyv7@gmail.com', 'Your Name');
                    $mail->addAddress($email, $felhasznalo); // Recipient
                    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Regisztráció megerősítése';
                    $mail->Body = "<h3><a href='http://localhost/prr/projekt/confirm.php?token=$token'>Kérjük, erősítse meg regisztrációját</a></h3>";
                    $mail->AltBody = 'Ez az email tartalmának szöveges változata.';
                    
                    // Send email
                    $mail->send();
                    // Redirect to login page after successful registration
                    header("Location: bejelentkezes.php");
                    exit(); // Ensure no further code is executed after redirect
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
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(4px);
        }

        h1 {
            text-align: center;
            color: #6e48aa;
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
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #6e48aa;
            outline: none;
        }

        button[type="submit"] {
            background: linear-gradient(90deg, #6e48aa, #9d50bb);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 500;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(110, 72, 170, 0.4);
        }

        a {
            color: #6e48aa;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #9d50bb;
            text-decoration: underline;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
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
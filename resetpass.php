<?php
require_once "kapcsolat.php";
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["forgot_password"])) {
    $email = $_POST['email'];
    
    $query = "SELECT * FROM adatok WHERE email = :email";
    $lekerdez = $kapcsolat->prepare($query);
    $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
    $lekerdez->execute();
    $user = $lekerdez->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $reset_token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $query = "UPDATE adatok SET reset_token = :reset_token, reset_token_expiry = :expiry WHERE email = :email";
        $lekerdez = $kapcsolat->prepare($query);
        $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
        $lekerdez->bindParam(':reset_token', $reset_token, PDO::PARAM_STR);
        $lekerdez->bindParam(':expiry', $expiry, PDO::PARAM_STR);
        $lekerdez->execute();

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
            $mail->addAddress($email, $user['felhasznalonev']); // Recipient

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Elfelejtett jelszó';
            $mail->Body = "<h3><a href='http://localhost/projekt/reset_password.php?token=$reset_token'>Kattintson a linkre az új jelszó megadásához</a></h3>";
            $mail->AltBody = 'Ez az email tartalmának szöveges változata.';

            // Send email
            $mail->send();
            $success = 'Email elküldve! Kérjük, ellenőrizze a postafiókját.';
        } catch (Exception $e) {
            $error = "Email küldése sikertelen. Hiba: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Hibás email cím!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelszó visszaállítása</title>
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

        .reset-container {
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

        input[type="email"] {
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus {
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

        .error, .success {
            text-align: center;
            margin-top: 1rem;
            font-weight: 500;
        }

        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Jelszó visszaállítása</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email cím" required>
            <button type="submit" name="forgot_password">Jelszó visszaállítása</button>
            <div class="login-link">
                Vissza a bejelentkezéshez? 
                <button class="login-btn"><a href="bejelentkezes.php">Bejelentkezés</a></button>
            </div>
        </form>
    </div>
</body>
</html>
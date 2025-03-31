<?php
require_once "kapcsolat.php";
$user = null;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $query0 = "SELECT * FROM adatok WHERE reset_token = :token";
    $lekerdez = $kapcsolat->prepare($query0);
    $lekerdez->bindParam(':token', $token, PDO::PARAM_STR);
    $lekerdez->execute();
    $user = $lekerdez->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $expiry = $user["reset_token_expiry"];
        $current_time = date("Y-m-d H:i:s");

        if ($current_time < $expiry) {
            // Jelszó-visszaállítási űrlap megjelenítése
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_pass"])) {
                $jelszo = $_POST['password'];
                $confirmpassword = $_POST['password_confirm'];

                if (empty($jelszo) || empty($confirmpassword)) {
                    $error = "Minden mezőt ki kell tölteni!";
                } elseif ($jelszo !== $confirmpassword) {
                    $error = "A jelszavak nem egyeznek!";
                } else {
                    $hashpass = password_hash($jelszo, PASSWORD_BCRYPT);
                    $email = $user["email"];
                    $query = "UPDATE adatok SET jelszo = :jelszo, reset_token = NULL, reset_token_expiry = NULL WHERE email = :email";
                    $lekerdez = $kapcsolat->prepare($query);
                    $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
                    $lekerdez->bindParam(':jelszo', $hashpass, PDO::PARAM_STR);

                    if ($lekerdez->execute()) {
                        $success = "Sikeres jelszóváltás! <a href='bejelentkezes.php'>Jelentkezz be itt</a>.";
                    } else {
                        $error = "Hiba történt a jelszóváltás során.";
                    }
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

        .reset-password-container {
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

        input[type="password"] {
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

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
    <div class="reset-password-container">
        <h1>Jelszó visszaállítása</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php else: ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Új jelszó" required>
                <input type="password" name="password_confirm" placeholder="Jelszó megerősítése" required>
                <button type="submit" name="reset_pass">Új jelszó létrehozása</button>
                <div class="login-link">
                    Vissza a bejelentkezéshez? 
                    <button class="login-btn"><a href="bejelentkezes.php">Bejelentkezés</a></button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
        } else {
            echo "<div style='text-align: center; color: #dc3545; font-family: Arial, sans-serif;'>Lejárt token!</div>";
        }
    } else {
        echo "<div style='text-align: center; color: #dc3545; font-family: Arial, sans-serif;'>Nem megfelelő token!</div>";
    }
} else {
    echo "<div style='text-align: center; color: #dc3545; font-family: Arial, sans-serif;'>Token nem található!</div>";
}
?>
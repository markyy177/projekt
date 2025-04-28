<?php
require_once "kapcsolat.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $remember = isset($_POST['rememberme']);
    $query = "SELECT * FROM adatok WHERE email = :email AND megerositve = 1";
    
    $lekerdez = $kapcsolat->prepare($query);
    $lekerdez->bindParam(':email', $email, PDO::PARAM_STR);
    $lekerdez->execute();
    $user = $lekerdez->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($pass, $user["jelszo"])) {
        $_SESSION["user"] = $user["felhasznalonev"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = $user["role"]; // Szerepkör tárolása
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time() + (86400 * 30);
            $query0 = "UPDATE adatok SET remember_token = :token WHERE id = :id";
            $lekerdez = $kapcsolat->prepare($query0);
            $lekerdez->bindParam(':token', $token, PDO::PARAM_STR);
            $lekerdez->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $lekerdez->execute();
            setcookie("remember_me", $token, $expires, "/", "", true, true);
        }
        header("Location: konyvek.php");
        exit();
    } else {
        $error = "Hibás email vagy jelszó";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
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

        .login-container {
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
        input[type="password"] {
            padding: 1rem;
            border: 1px solid #d0d7e1;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
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

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
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

        .register-link {
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .register-btn {
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
    <div class="login-container">
        <h1>Bejelentkezés</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email cím" required>
            <input type="password" name="password" placeholder="Jelszó" required>
            <div class="checkbox-container">
                <input type="checkbox" name="rememberme" id="remember">
                <label for="remember">Jegyezzen meg!</label>
            </div>
            <button type="submit" name="login">Bejelentkezés</button>
            <a href="resetpass.php">Elfelejtettem a jelszavamat</a>
            <div class="register-link">
                Nincs még fiókod? 
                <button class="register-btn"><a href="registration.php">Regisztráció</a></button>
            </div>
        </form>
    </div>
</body>
</html>
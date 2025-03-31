<?php
require_once "kapcsolat.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $remember = isset($_POST['rememberme']);
    $query="SELECT * FROM adatok WHERE email =:email AND megerositve = 1";
    
    $lekerdez = $kapcsolat -> prepare($query);
    $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
    $lekerdez -> execute();
    $user = $lekerdez -> fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($pass,$user["jelszo"])) {
        $_SESSION["user"]=$user["felhasznalonev"];
        $_SESSION["user_id"]=$user["id"];
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $expires = time()+(86400*30);
            $query0 = "UPDATE adatok SET remember_token = :token WHERE id=:id";
            $lekerdez = $kapcsolat->prepare($query0);
            $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
            $lekerdez -> bindParam(':id',$user['id'],PDO::PARAM_INT);
            $lekerdez -> execute();
            setcookie("remember_me",$token,$expires,"/","",true,true);
        }
        // Removed the success message echo
        header("Location: konyvek.html");
    } else {
        echo '<p class="error">Hibás email vagy jelszó</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
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

        .login-container {
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
        input[type="password"] {
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="email"]:focus,
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

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
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

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
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
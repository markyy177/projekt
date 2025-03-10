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
                echo"sikeres bejelentkezés"." üdv, ".$_SESSION["user"];
                header("Location: account.php");
            }
            else{
                echo"Hibás email vagy jelszó";
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
<h1>Bejelentkezés</h1>
        <form method="POST">
            Email cím: <input type="email" name="email"><br>
            Jelszó: <input type="password" name="password"><br>
            <button type="submit" name="login">Bejelentkezés</button>
            <input type="checkbox" name="rememberme"> Jegyezzen meg!
            <a href="resetpass.php">Elfelejtettem a jelszavamat</a>
            <br>Nincs még fiókod? : <button><a href="registration.php">Regisztráció</a></button>
        </form>
</body>
</html>
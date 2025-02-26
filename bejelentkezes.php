<?php
require_once "kapcsolat.php";
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $query="SELECT * FROM adatok WHERE email =:email AND megerositve = 1";
    
            $lekerdez = $kapcsolat -> prepare($query);
            $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
            $lekerdez -> execute();
            $user = $lekerdez -> fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($pass,$user["jelszo"])) {
                $_SESSION["user"]=$user["felhasznalonev"];
                echo"sikeres bejelentkezés"." üdv, ".$_SESSION["user"];
                header("Location: registration.php");
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
            <a href="resetpass.php">Elfelejtettem a jelszavamat</a>
        </form>
</body>
</html>
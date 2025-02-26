<?php
require_once "kapcsolat.php";
$user = null;


// $query = "SELECT * FROM adatok WHERE token = :token";
//         $stmt = $kapcsolat->prepare($query);
//         $stmt->bindParam(':token', $token, PDO::PARAM_STR);
//         $stmt->execute();

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $query0="SELECT * FROM adatok WHERE reset_token= :token;";
    $lekerdez = $kapcsolat -> prepare($query0);
    $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
    $lekerdez -> execute();
    $user = $lekerdez -> fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $expiry = $user["reset_token_expiry"];
        $current_time = date("Y-m-d H:i:s");
        if ($current_time<$expiry) {
            //reset password mezők ide jönnek
            ?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Jelszó visszaállítása</h1>
        <form method="POST">
            Jelszó: <input type="password" name="password"><br>
            Jelszó megerősítése: <input type="password" name="password_confirm"><br>
            <button type="submit" name="reset_pass">Új jelszó létrehozása</button>
        </form>
</body>
</html>
<?php
        }
        else{
            echo"lejárt token";
        }
    }
    else{
        echo"nem megfelelő token";
        }
        
    }
    else {
        echo"token nem található";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_pass"])) {
        $jelszo = $_POST['password'];
        $confirmpassword = $_POST['password_confirm'];
        if ($jelszo==$confirmpassword) {
            $hashpass = password_hash($jelszo,PASSWORD_BCRYPT);
            $email = $user["email"];
            $query="UPDATE adatok SET jelszo = :jelszo, reset_token = null, reset_token_expiry = null WHERE email = :email ";
            $lekerdez = $kapcsolat -> prepare($query);    
            $lekerdez -> bindParam(':email',$email,PDO::PARAM_STR);
            $lekerdez -> bindParam(':jelszo',$hashpass,PDO::PARAM_STR);
            
            if($lekerdez -> execute()){
                echo"Sikeres jelszóváltás";
            }
            else{
                echo"Hiba történt";
            }
                
            }
            }
        
?>
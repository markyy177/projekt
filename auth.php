<?php
session_start();
require_once("kapcsolat.php");

if (!isset($_SESSION['user_id'])&&isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE["remember_me"];
    $query0 = "SELECT id,felhasznalonev FROM adatok WHERE remember_token=:token";
    $lekerdez=$kapcsolat->prepare($query0);
    $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
    
    $lekerdez->execute();
    $user = $lekerdez -> fetch();
    if ($user) {
        $_SESSION["user_id"]=$user["id"];
        $_SESSION["user"]=$user["felhasznalonev"];
        echo"session beállítva";
    }
}
?>
<?php
require_once "kapcsolat.php";


// $query = "SELECT * FROM adatok WHERE token = :token";
//         $stmt = $kapcsolat->prepare($query);
//         $stmt->bindParam(':token', $token, PDO::PARAM_STR);
//         $stmt->execute();

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $query0="SELECT COUNT(*) FROM adatok WHERE token= :token;";
    $lekerdez = $kapcsolat -> prepare($query0);
    $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
    $lekerdez -> execute();
    if ($lekerdez -> fetchColumn()==0) {
        echo"token nem található";
    }
    else{
    
    $query="UPDATE adatok SET megerositve = 1 WHERE token= :token;";
    
        $lekerdez = $kapcsolat -> prepare($query);
        $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
        
        if($lekerdez -> execute()){
            echo"Regisztráció megerősítve";
            $query="UPDATE adatok SET token = NULL WHERE token= :token;";
    
            $lekerdez = $kapcsolat -> prepare($query);
            $lekerdez -> bindParam(':token',$token,PDO::PARAM_STR);
            $lekerdez -> execute();
        }
        else{
            echo"Hiba történt";
        }
    }
}
?>

<?php
require_once "kapcsolat.php";
session_start();
$konyv_id = isset($_GET['konyv_id'])?$_GET['konyv_id']:0;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kuldes"]) && isset($_POST["szam_ertek"])) {
    $szov_e = $_POST['szov_ertek'];
    $szam_e = $_POST['szam_ertek'];
    
    $query="INSERT INTO ertekelesek (konyv_id,szov_ertekeles,szam_ertekeles) VALUES (:konyv_id,:szov_ertek,:szam_ertek)";
    
            $lekerdez = $kapcsolat -> prepare($query);
            $lekerdez -> bindParam(':konyv_id',$konyv_id,PDO::PARAM_INT);
            $lekerdez -> bindParam(':szov_ertek',$szov_e,PDO::PARAM_STR);
            $lekerdez -> bindParam(':szam_ertek',$szam_e,PDO::PARAM_INT);
            
            
            if ($lekerdez -> execute()) {
                
                echo"sikeres értékelés";
                
            }
            else{
                echo"Hiba történt a művelet során";
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
<form method="POST">
            <textarea name="szov_ertek" id="szov_ertek">

            </textarea>
            <select name="szam_ertek" id="szam_ertek" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit" name="kuldes">Elküldés</button>
</form>
</body>
</html>
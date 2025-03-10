<?php
require_once("kapcsolat.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feltolt"])) {
    $szerzo = $_POST['szerzo'];
    $cim = $_POST['cim'];
    $leiras = $_POST['leiras'];
    $kiadev = $_POST['kiadev'];
    $file = $_FILES['borito'];
    $user_id = 38;

    $img_tmppass = $file['tmp_name'];
    if (isset($_FILES['borito']) && $_FILES['borito']['error'] === UPLOAD_ERR_OK) {
        $img = file_get_contents($img_tmppass);
        $img_data = base64_encode($img);
        $query0 = "INSERT INTO konyvek (cim, leiras, szerzo, kiadas, user_id, borito) VALUES (:cim, :leiras, :szerzo, :kiadev, :user_id, :borito)";
        $lekerdez = $kapcsolat->prepare($query0);
        $lekerdez->bindParam(':cim', $cim, PDO::PARAM_STR);
        $lekerdez->bindParam(':leiras', $leiras, PDO::PARAM_STR);
        $lekerdez->bindParam(':szerzo', $szerzo, PDO::PARAM_STR);
        $lekerdez->bindParam(':kiadev', $kiadev, PDO::PARAM_STR);
        $lekerdez->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $lekerdez->bindParam(':borito', $img_data, PDO::PARAM_STR);
    }
    if ($lekerdez->execute()) {
        echo "sikeres beszúrás";
    } else {
        echo "hiba történt";
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
    <form method="post" enctype="multipart/form-data">
        Könyv szerzője: <input type="text" name="szerzo">
        Könyv címe: <input type="text" name="cim">
        Könyv leírása: <input type="text" name="leiras">
        Kiadás éve: <input type="text" placeholder="(csak az év)" name="kiadev">
        Borítókép: <input type="file" name="borito" id="borito">
        <button type="submit" name="feltolt">Feltöltés</button>
    </form>
</body>
</html>
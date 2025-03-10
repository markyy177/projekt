<?php
require_once("kapcsolat.php");
require_once("auth.php");
echo"<br>szia, ".$_SESSION["user"];
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="logout.php"><button>Kijelentkezés</button></a>
<a href="konyv_feltolt.php"><button>Könyv feltöltése</button></a>
</body>
</html>

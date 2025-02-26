<?php

try {
    $kapcsolat = new PDO("mysql:host=127.0.0.1;dbname=felhasznalok", "root", "");
    $kapcsolat->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kapcsolódás sikeres!";
} catch (PDOException $e) {
    echo "Kapcsolódási hiba: " . $e->getMessage();
}
?>
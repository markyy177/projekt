<?php
require_once "kapcsolat.php";
session_start();
$konyv_id = isset($_GET['konyv_id']) ? $_GET['konyv_id'] : 0;
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["kuldes"]) && isset($_POST["szam_ertek"])) {
    $szov_e = $_POST['szov_ertek'];
    $szam_e = $_POST['szam_ertek'];
    
    $query = "INSERT INTO ertekelesek (konyv_id, szov_ertekeles, szam_ertekeles) VALUES (:konyv_id, :szov_ertek, :szam_ertek)";
    
    $lekerdez = $kapcsolat->prepare($query);
    $lekerdez->bindParam(':konyv_id', $konyv_id, PDO::PARAM_INT);
    $lekerdez->bindParam(':szov_ertek', $szov_e, PDO::PARAM_STR);
    $lekerdez->bindParam(':szam_ertek', $szam_e, PDO::PARAM_INT);
    
    if ($lekerdez->execute()) {
        $success_message = '<p class="success">Sikeres értékelés</p>';
    } else {
        $error_message = '<p class="error">Hiba történt a művelet során</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Értékelés</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative; /* Hozzáadva, hogy a back-button pozícionálása működjön */
        }

        /* Vissza gomb doboz */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: linear-gradient(90deg, #d3d3d3, #f0f0f0); /* Világos szürke színátmenet */
            padding: 0.8rem 1.2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .back-button a {
            color: #333;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-button a:hover {
            color: #6e48aa; /* Lila szín a hoverhez, hogy illeszkedjen a témához */
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
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

        textarea {
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s ease;
        }

        textarea:focus {
            border-color: #6e48aa;
            outline: none;
        }

        select {
            padding: 1rem;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            transition: border-color 0.3s ease;
        }

        select:focus {
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

        .success {
            color: #000000;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 500;
            font-size: 1.2rem;
        }

        .error {
            color: #dc3545;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 500;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Vissza a főoldalra gomb -->
    <div class="back-button">
        <a href="index.html">Vissza a főoldalra</a>
    </div>

    <?php
    // A siker- és hibaüzenet kiírása a doboz előtt
    if (!empty($success_message)) {
        echo $success_message;
    }
    if (!empty($error_message)) {
        echo $error_message;
    }
    ?>
    <div class="form-container">
        <h1>Értékelés</h1>
        <form method="POST">
            <textarea name="szov_ertek" id="szov_ertek" placeholder="Írd meg az értékelésedet itt..."></textarea>
            <select name="szam_ertek" id="szam_ertek" required>
                <option value="" disabled selected>Válassz pontszámot</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit" name="kuldes">Elküldés</button>
        </form>
    </div>
</body>
</html>
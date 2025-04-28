<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Könyvek</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .box-area {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }
        .book-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s;
        }
        .book-card:hover {
            transform: translateY(-5px);
        }
        .book-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .book-card h3 {
            font-size: 1.2em;
            margin: 10px 0;
            color: #333;
        }
        .book-card p {
            font-size: 0.9em;
            color: #666;
            margin: 5px 0;
        }
        .book-card .author {
            font-style: italic;
            color: #888;
        }
        .book-card .year {
            font-weight: bold;
            color: #444;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li>
                <a href="index.html"><i class="fa fa-home"></i>   Kezdőlap</a>
            </li>
            <li>
                <a href="konyvek.html"><i class="fa fa-book"></i>   Könyvek</a>
            </li>
            <li>
                <a href="bejelentkezes.php"><i class="fa fa-book"></i>   Fiók</a>
            </li>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="section">
            <div class="box-area">
                <?php
                $id = isset($_GET['id'])?$_GET['id']:0;
                if ($id>0) {
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
                // Adatbázis kapcsolat
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "felhasznalok";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Kapcsolódási hiba: " . $conn->connect_error);
                }

                // Adatok lekérése
                $sql = "SELECT id, cim, leiras, szerzo, kiadas, user_id, borito, jovahagyva FROM jovahagyando_konyvek";
                $result = $conn->query($sql);

                // Adatok megjelenítése kártyák formájában
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="book-card">';
                        echo '<img src="' . $row["borito"] . '" alt="' . $row["cim"] . ' borító">';
                        echo '<h3>' . $row["cim"] . '</h3>';
                        echo '<p class="author">Szerző: ' . $row["szerzo"] . '</p>';
                        echo '<p class="year">Kiadás: ' . $row["kiadas"] . '</p>';
                        echo '<p>' . $row["leiras"] . '</p>';
                        echo '<p><a href="jovahagyando.php?id=' . $row["id"] . '">Jóváhagyás</a></p>';
                        echo '</div>';
                    }
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
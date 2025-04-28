<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin Felület</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Menüsor teljesen felül */
        .header {
            background-color: #2c3e50;
            width: 100%;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        nav ul li {
            margin: 0 25px;
        }

        nav ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
            font-weight: 400;
            transition: color 0.3s;
        }

        nav ul li a i {
            margin-right: 8px;
        }

        nav ul li a:hover {
            color: #3498db;
        }

        /* Logó doboz jobb felső sarokban */
        .logo-container {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-box {
            background-color: #fff;
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        .logo-box svg {
            width: 40px;
            height: 40px;
            animation: rotateBook 4s infinite linear;
        }

        @keyframes rotateBook {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            75% { transform: rotate(-10deg); }
            100% { transform: rotate(0deg); }
        }

        .admin-icon {
            color: #ecf0f1;
            font-size: 24px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .admin-icon:hover {
            color: #3498db;
        }

        /* Tartalom terület */
        .admin-area {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
            max-width: 1200px;
            margin: 100px auto 40px auto; /* Menüsor alá tolva */
        }

        .admin-area h1 {
            font-size: 2em;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            width: 100%;
        }

        .request-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 250px;
            padding: 15px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .request-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .request-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .request-card h3 {
            font-size: 1.2em;
            margin: 10px 0;
            color: #2c3e50;
            font-weight: 600;
        }

        .request-card p {
            font-size: 0.9em;
            color: #666;
            margin: 5px 0;
        }

        .request-card .author {
            font-style: italic;
            color: #888;
        }

        .request-card .year {
            font-weight: bold;
            color: #444;
        }

        .request-card .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .request-card .actions button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            font-size: 0.9em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .request-card .actions .approve {
            background-color: #28a745;
            color: #ffffff;
        }

        .request-card .actions .approve:hover {
            background-color: #218838;
        }

        .request-card .actions .reject {
            background-color: #e74c3c;
            color: #ffffff;
        }

        .request-card .actions .reject:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    // Csak adminok férhetnek hozzá
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: konyvek.php");
        exit();
    }
    ?>
    <div class="header">
        <nav>
            <ul>
                <li>
                    <a href="kezdolap.html"><i class="fa fa-home"></i> Kezdőlap</a>
                </li>
                <li>
                    <a href="konyvek.php"><i class="fa fa-book"></i> Könyvek</a>
                </li>
                <li>
                    <a href="bejelentkezes.php"><i class="fa fa-user"></i> Fiók</a>
                </li>
            </ul>
        </nav>
        <div class="logo-container">
            <div class="logo-box">
                <!-- SVG könyv logó -->
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="20" width="60" height="60" fill="#3498db" rx="5"/>
                    <rect x="25" y="25" width="50" height="50" fill="#ecf0f1" rx="3"/>
                    <path d="M25 50 H75" stroke="#2c3e50" stroke-width="2"/>
                    <path d="M25 55 H75" stroke="#2c3e50" stroke-width="2"/>
                </svg>
            </div>
            <a href="admin.php" class="admin-icon"><i class="fa fa-user-secret"></i></a>
        </div>
    </div>

    <div class="admin-area">
        <h1>Admin Felület - Könyv kérelmek</h1>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "felhasznalok";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Kapcsolódási hiba: " . $conn->connect_error);
        }

        // Kérelem kezelése
        if (isset($_POST['approve'])) {
            $id = $_POST['id'];
            // Másolás a konyvek táblába
            $sql = "INSERT INTO konyvek (cim, leiras, szerzo, kiadas, user_id, borito)
                    SELECT cim, leiras, szerzo, kiadas, user_id, borito
                    FROM pending_books WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();

            // Státusz frissítése
            $sql = "UPDATE pending_books SET status = 'approved' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        if (isset($_POST['reject'])) {
            $id = $_POST['id'];
            $sql = "UPDATE pending_books SET status = 'rejected' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        // Függőben lévő kérelmek lekérése
        $sql = "SELECT * FROM pending_books WHERE status = 'pending'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="request-card">';
                echo '<img src="' . $row["borito"] . '" alt="' . $row["cim"] . ' borító">';
                echo '<h3>' . $row["cim"] . '</h3>';
                echo '<p class="author">Szerző: ' . $row["szerzo"] . '</p>';
                echo '<p class="year">Kiadás: ' . $row["kiadas"] . '</p>';
                echo '<p>' . $row["leiras"] . '</p>';
                echo '<div class="actions">';
                echo '<form method="POST">';
                echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                echo '<button type="submit" name="approve" class="approve">Elfogadás</button>';
                echo '<button type="submit" name="reject" class="reject">Elutasítás</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>Nincsenek függőben lévő kérelmek.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
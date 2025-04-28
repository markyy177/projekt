<?php
session_start();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Könyvek</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            color: #1a2a44;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            background-color: #1a2a44;
            width: 100%;
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            margin: 0 20px;
        }

        nav ul li a {
            color: #f0f4f8;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        nav ul li a i {
            margin-right: 8px;
        }

        nav ul li a:hover {
            color: #ffd700;
        }

        /* Logó vagy admin ikon a jobb felső sarokban */
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
            color: #ffd700;
            font-size: 24px;
            text-decoration: none;
            transition: color 0.3s;
        }

        .admin-icon:hover {
            color: #e6c200;
        }

        .box-area {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            justify-content: center;
            padding: 30px;
            max-width: 1200px;
            margin: 80px auto 40px auto;
        }

        .book-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            width: 280px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            cursor: pointer;
        }

        .book-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
        }

        .book-card h3 {
            font-size: 1.3em;
            margin: 12px 0;
            color: #1a2a44;
            font-weight: 600;
        }

        .book-card p {
            font-size: 0.95em;
            color: #5a6b88;
            margin: 5px 0;
        }

        .book-card .author {
            font-style: italic;
            color: #7a8aa8;
        }

        .book-card .year {
            font-weight: bold;
            color: #3a4b66;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 25px;
            max-width: 550px;
            width: 90%;
            position: relative;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .modal-content img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .modal-content h3 {
            font-size: 1.6em;
            color: #1a2a44;
            margin-bottom: 10px;
        }

        .modal-content p {
            font-size: 1.05em;
            color: #5a6b88;
            margin-bottom: 10px;
        }

        .modal-content .author {
            font-style: italic;
            color: #7a8aa8;
        }

        .modal-content .year {
            font-weight: bold;
            color: #3a4b66;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 15px;
            font-size: 28px;
            color: #5a6b88;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #ff4d4d;
        }

        /* Feltöltési űrlap modal stílusa */
        .upload-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #ffd700;
            color: #1a2a44;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }

        .upload-btn:hover {
            background-color: #e6c200;
        }

        .upload-modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .upload-modal-content {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 25px;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .upload-modal-content h3 {
            font-size: 1.6em;
            color: #1a2a44;
            margin-bottom: 20px;
        }

        .upload-modal-content form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .upload-modal-content input,
        .upload-modal-content textarea {
            padding: 10px;
            border: 1px solid #d0d7e1;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
        }

        .upload-modal-content button {
            background-color: #1a2a44;
            color: #f0f4f8;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-modal-content button:hover {
            background-color: #2b3a55;
        }
    </style>
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li>
                    <a href="index.html"><i class="fa fa-home"></i> Kezdőlap</a>
                </li>
                <li>
                    <a href="konyvek.php"><i class="fa fa-book"></i> Könyvek</a>
                </li>
                <li>
                    <a href="bejelentkezes.php"><i class="fa fa-user"></i> Fiók</a>
                </li>
            </ul>
        </nav>
        
            <?php
            // Ha a felhasználó admin, megjelenítjük az admin ikont
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                echo '<a href="admin.php" class="admin-icon"><i class="fa fa-user-secret"></i></a>';
            }
            ?>
        </div>
    </div>

    <!-- Modal ablak a könyv részleteihez -->
    <div id="bookModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">×</span>
            <img id="modalImage" src="" alt="Könyv borító">
            <h3 id="modalTitle"></h3>
            <p class="author" id="modalAuthor"></p>
            <p class="year" id="modalYear"></p>
            <p id="modalDescription"></p>
        </div>
    </div>

    <!-- Feltöltési modal -->
    <div id="uploadModal" class="upload-modal">
        <div class="upload-modal-content">
            <span class="close-btn">×</span>
            <h3>Könyv feltöltése</h3>
            <form action="konyvek.php" method="POST">
                <input type="text" name="cim" placeholder="Könyv címe" required>
                <textarea name="leiras" placeholder="Leírás" required></textarea>
                <input type="text" name="szerzo" placeholder="Szerző" required>
                <input type="number" name="kiadas" placeholder="Kiadási év" required>
                <input type="text" name="borito" placeholder="Borító URL" required>
                <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; ?>">
                <button type="submit" name="upload_book">Feltöltés</button>
            </form>
        </div>
    </div>

    <div class="box-area">
        <?php
        // Adatbázis kapcsolat
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "felhasznalok";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Kapcsolódási hiba: " . $conn->connect_error);
        }

        // Könyv feltöltés kezelése
        if (isset($_POST['upload_book'])) {
            $cim = $_POST['cim'];
            $leiras = $_POST['leiras'];
            $szerzo = $_POST['szerzo'];
            $kiadas = $_POST['kiadas'];
            $borito = $_POST['borito'];
            $user_id = $_POST['user_id'];

            $sql = "INSERT INTO pending_books (cim, leiras, szerzo, kiadas, user_id, borito) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssiss", $cim, $leiras, $szerzo, $kiadas, $user_id, $borito);
            $stmt->execute();
            $stmt->close();
        }

        // Adatok lekérése
        $sql = "SELECT id, cim, leiras, szerzo, kiadas, user_id, borito FROM konyvek";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="book-card" 
                      data-title="' . htmlspecialchars($row["cim"]) . '" 
                      data-author="' . htmlspecialchars($row["szerzo"]) . '" 
                      data-year="' . htmlspecialchars($row["kiadas"]) . '" 
                      data-description="' . htmlspecialchars($row["leiras"]) . '" 
                      data-image="' . htmlspecialchars($row["borito"]) . '">';
                echo '<img src="' . $row["borito"] . '" alt="' . $row["cim"] . ' borító">';
                echo '<h3>' . $row["cim"] . '</h3>';
                echo '<p class="author">Szerző: ' . $row["szerzo"] . '</p>';
                echo '<p class="year">Kiadás: ' . $row["kiadas"] . '</p>';
                echo '<p>' . $row["leiras"] . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>Nincsenek könyvek az adatbázisban.</p>";
        }

        $conn->close();
        ?>
    </div>

    <!-- Feltöltési gomb -->
    <div class="upload-btn" onclick="openUploadModal()">Könyv feltöltése</div>

    <script>
        const modal = document.getElementById('bookModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        const modalAuthor = document.getElementById('modalAuthor');
        const modalYear = document.getElementById('modalYear');
        const modalDescription = document.getElementById('modalDescription');
        const closeBtns = document.querySelectorAll('.close-btn');
        const bookCards = document.querySelectorAll('.book-card');
        const uploadModal = document.getElementById('uploadModal');

        bookCards.forEach(card => {
            card.addEventListener('click', () => {
                const title = card.getAttribute('data-title');
                const author = card.getAttribute('data-author');
                const year = card.getAttribute('data-year');
                const description = card.getAttribute('data-description');
                const image = card.getAttribute('data-image');

                modalImage.src = image;
                modalImage.alt = title + ' borító';
                modalTitle.textContent = title;
                modalAuthor.textContent = 'Szerző: ' + author;
                modalYear.textContent = 'Kiadás: ' + year;
                modalDescription.textContent = description;

                modal.style.display = 'flex';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
                uploadModal.style.display = 'none';
            });
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
            if (event.target === uploadModal) {
                uploadModal.style.display = 'none';
            }
        });

        function openUploadModal() {
            uploadModal.style.display = 'flex';
        }
    </script>
</body>
</html>
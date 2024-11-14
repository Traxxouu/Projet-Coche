<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion
    header("Location: ../registration/login/index.html");
    exit();
}

require_once('../config.php');

// Connexion à la base de données
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Récupérer les informations de l'utilisateur
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, gender, country, custom_background FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $gender, $country, $custom_background);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coche - Mon Compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <a href="../accueil/index.php"><h1><i class="fas fa-check-circle"></i> Coche</h1></a> 
            </div>
            <div class="menu-icon" id="menu-icon">
                <i class="fas fa-bars"></i>
            </div>
            <ul class="navbar-menu" id="navbar-menu">
                <li><a href="../accueil/index.php" class="accueil"><i class="fas fa-home"></i> Accueil</a></li>
                <li><a href="logout.php" class="deconnexion"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Conteneur principal -->
    <div class="container">
        <div class="dashboard-container">
            <!-- Carte utilisateur -->
            <div class="user-card">
                <h2>Bienvenue, @<?php echo htmlspecialchars($username ?? ''); ?> !</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email ?? ''); ?></p>
                <p><strong>Genre:</strong> <?php echo ($gender == 'male') ? 'Homme' : 'Femme'; ?></p>
                <p><strong>Pays:</strong> <?php echo htmlspecialchars($country ?? ''); ?></p>
                <button id="import-btn" class="import-btn"><i class="fas fa-file-import"></i> Importer des listes en .csv (for PC only)</button>
                <button id="export-btn" class="export-btn"><i class="fas fa-file-export"></i> Exporter mes listes en .csv</button>
            </div>

            <!-- Carte de téléchargement de fond d'écran -->
            <div class="upload-background user-card">
                <h2>Personnaliser le fond d'écran de l'accueil</h2>
                <form id="uploadForm" action="../accueil/upload_background.php" method="POST" enctype="multipart/form-data">
                    <label class="file-label">
                        <input type="file" name="background" accept=".png, .jpeg, .jpg, .webp" required>
                        <span>Choisir un fichier</span>
                    </label>
                    <button type="submit" class="upload-btn"><i class="fas fa-upload"></i> Charger l'image</button>
                </form>
                <form id="deleteForm" action="../accueil/delete_background.php" method="POST" enctype="multipart/form-data">
                    <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Supprimer l'image</button>
                </form>
                <p class="format-info">Formats acceptés: PNG, JPG. Taille: 1920x1080 pixels</p>
            </div>
        </div>
    </div>

    <!-- Switch -->
    <div class="switch-container">
        <input type="checkbox" id="checkboxInput">
        <label class="toggleSwitch" for="checkboxInput"></label>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <ul class="footer-menu">
                <li><a href="../accueil/index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
                <li><a href="https://decoche.netlify.app/">>????<</a></li>
            </ul>
            <p class="footer-text">Site réalisé dans le cadre d'un projet JavaScript par : <a href="https://mael-dev-card.netlify.app/" target="_blank">Mael</a>, <a href="https://github.com/DevThomas0" target="_blank">Thomas</a>, <a href="https://github.com/starlague" target="_blank">Sarah</a>, <a href="https://github.com/Number6272" target="_blank"> Julien</a></p>
        </div>
    </footer>

    <!-- Inclure le fichier JavaScript -->
    <script src="main.js"></script>
</body>
</html>

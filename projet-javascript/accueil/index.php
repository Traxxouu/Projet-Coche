<?php
session_start();
require_once('../config.php');

//--------------[Vérification de la connexion de l'utilisateur]-----------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login/index.html");
    exit();
}

//--------------[Récupération de l'image de fond personnalisée de l'utilisateur]-----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$user_id = $_SESSION['user_id'];
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
$stmt = $conn->prepare("SELECT custom_background FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($custom_background);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coche - Accueil</title>
    <!----------------[Google Fonts]----------------------- -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!----------------[Font Awesome - icônes]----------------------- -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="styles.css">

    <style>
        body {
            background-image: url('<?php echo htmlspecialchars($custom_background ? "../uploads/" . $custom_background : "media/background-accueil.png"); ?>');
            background-size: cover;
        }
    </style>
</head>
<body>
    <!----------------[En-tête avec bouton de connexion]----------------------- -->
    <header>
        <h1><i class="fas fa-check-circle"></i> Coche</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../dashboard/index.php"><button class="account-btn"><i class="fas fa-user"></i> Mon Compte</button></a>
            <?php else: ?>
                <a href="../registration/login/index.html"><button class="login-btn"><i class="fas fa-sign-in-alt"></i> Connexion</button></a>
            <?php endif; ?>
        </nav>
    </header>

    <!----------------[Contenu principal]----------------------- -->
    <main>
        <!----------------[Section de création de liste]----------------------- -->
        <section class="list-creation">
            <h2>Créer une nouvelle liste</h2>
            <div class="input-group">
                <input type="text" id="new-list-name" placeholder="Nom de la liste...">
                <button id="create-list-btn"><i class="fas fa-plus"></i> Créer la liste</button>
            </div>
        </section>

        <!----------------[Listes existantes]----------------------- -->
        <section class="lists" id="lists">
            <!-- Les listes seront générées dynamiquement ici -->
        </section>
    </main>

    <script src="main.js"></script>
</body>
</html>

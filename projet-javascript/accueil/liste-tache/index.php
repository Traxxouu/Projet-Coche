<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion
    header("Location: ../../registration/login/index.html");
    exit();
}

// Vérifier si l'ID de la liste est fourni dans l'URL
if (!isset($_GET['id'])) {
    // Pas d'ID de liste fourni, rediriger vers l'accueil
    header("Location: ../index.php");
    exit();
}

$list_id = intval($_GET['id']);

// Connexion à la BDD en loc sur waamp
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Vérifier que la liste appartient à l'utilisateur avec l'id de session
$stmt = $conn->prepare("SELECT name FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Liste non trouvée ou accès non autorisé
    header("Location: ../index.php");
    exit();
}

$list = $result->fetch_assoc();
$list_name = $list['name'];

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coche - <?php echo htmlspecialchars($list_name); ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <!-- Font Awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!-- Bouton "Retour" -->
        <a href="../../accueil/index.php">
            <button id="back-btn" class="back-btn">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
        </a>
        <!-- Filtres -->
        <div class="filters">
            <button class="filter-btn" data-filter="all" id="toutes"><i class="fas fa-tasks"></i> Toutes</button>
            <button class="filter-btn" data-filter="in-progress" id="encours"><i class="fas fa-spinner"></i> En cours</button>
            <button class="filter-btn" data-filter="completed" id="terminee"><i class="fas fa-check-circle"></i> Terminées</button>
        </div>
        <!-- Titre de la liste -->
        <h1 id="list-title"><?php echo htmlspecialchars($list_name); ?></h1>
        <p class="subtitle">Cochez les tâches au fur et à mesure que vous les accomplissez.</p>
        <!-- Formulaire d'ajout de tâche -->
        <div class="todo-input">
            <input type="text" id="new-task" placeholder="Nom de la tâche">
            <input type="date" id="new-task-date" placeholder="Date de la tâche">
            <button id="add-task-btn"><i class="fas fa-plus"></i> Ajouter</button>
        </div>
        <!-- Liste des tâches -->
        <ul id="task-list">
            <!-- Les tâches seront ajoutées dynamiquement ici -->
        </ul>
        <!-- Barre de progression -->
        <div class="progress-container">
            <div id="progress-bar" class="progress-bar"></div>
        </div>
    </div>

    <!-- Passer l'ID de la liste au script JavaScript -->
    <script>
        const currentListId = <?php echo $list_id; ?>;
    </script>

    <script src="main.js"></script>
</body>
</html>

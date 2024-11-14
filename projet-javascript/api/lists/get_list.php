<?php
session_start();

//-------------[Vérification de la session utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Non autorisé
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

$list_id = intval($_GET['id']);

//-------------[Connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    http_response_code(500); // Erreur interne du serveur - ChatG à verif
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit();
}

$user_id = $_SESSION['user_id'];

//-------------[Obtention de la liste]----------------------
$stmt = $conn->prepare("SELECT id, name FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(['error' => 'Liste non trouvée ou accès non autorisé.']);
    exit();
}

$list = $result->fetch_assoc();
$stmt->close();

//-------------[Obtention des tâches]----------------------
$stmt = $conn->prepare("SELECT id, text, completed, date FROM tasks WHERE list_id = ?");
$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

echo json_encode(['success' => true, 'list' => $list, 'tasks' => $tasks]);

$stmt->close();
$conn->close();
?>

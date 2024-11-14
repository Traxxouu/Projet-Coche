<?php
session_start();

//-------------[Vérification de la connexion utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Non autorisé
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

//-------------[Lecture et validation des données de la requête]----------------------
$data = json_decode(file_get_contents('php://input'), true);
$list_id = isset($data['list_id']) ? intval($data['list_id']) : null;
$method = isset($data['method']) ? $data['method'] : null;

if (!$list_id || !$method) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(['error' => 'list_id et méthode sont requis.']);
    exit();
}

//-------------[Connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    http_response_code(500); // Erreur serveur
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit();
}

//-------------[Vérification de la liste de l'utilisateur]----------------------
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403); // Interdit
    echo json_encode(['error' => 'Liste non trouvée ou accès non autorisé.']);
    exit();
}

$list = $result->fetch_assoc();
$stmt->close();

//-------------[Récupération des tâches selon la méthode]----------------------
if ($method === "pending") {
    $stmt = $conn->prepare("SELECT id, text, completed, created_at, date FROM tasks WHERE list_id = ? AND completed = 0");
} elseif ($method === "done") {
    $stmt = $conn->prepare("SELECT id, text, completed, created_at, date FROM tasks WHERE list_id = ? AND completed = 1");
} else {
    http_response_code(400); // Mauvaise requête
    echo json_encode(['error' => 'Méthode invalide.']);
    exit();
}

$stmt->bind_param("i", $list_id);
$stmt->execute();
$result = $stmt->get_result();

//-------------[Préparation et envoi de la réponse]----------------------
$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        'id' => $row['id'],
        'text' => $row['text'],
        'completed' => $row['completed'],
        'created_at' => $row['created_at'],
        'date' => $row['date']
    ];
}

echo json_encode(['success' => true, 'list' => $list, 'tasks' => $tasks]);

$stmt->close();
$conn->close();
?>

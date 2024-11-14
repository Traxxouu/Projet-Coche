<?php
session_start();

//-------------[Vérification de la session utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Non autorisé
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

//-------------[Récupération des données de la requête]----------------------
$data = json_decode(file_get_contents('php://input'), true);
$task_id = intval($data['id']);
$text = isset($data['text']) ? trim($data['text']) : null;
$date = isset($data['date']) ? $data['date'] : null;
$completed = isset($data['completed']) ? $data['completed'] : null;

if ($text === null && $completed === null) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(['error' => 'Aucune donnée à mettre à jour.']);
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

$user_id = $_SESSION['user_id'];

//-------------[Vérification de la propriété de la tâche]----------------------
$stmt = $conn->prepare("SELECT t.id FROM tasks t JOIN lists l ON t.list_id = l.id WHERE t.id = ? AND l.user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403); // Interdit
    echo json_encode(['error' => 'Tâche non trouvée ou accès non autorisé.']);
    exit();
}
$stmt->close();

//-------------[Construction de la requête de mise à jour]----------------------
$fields = [];
$params = [];
$types = '';

if ($text !== null) {
    $fields[] = 'text = ?';
    $params[] = $text;
    $types .= 's';
}

if ($date !== null) {
    $fields[] = 'date = ?';
    $params[] = $date;
    $types .= 's';
}

if ($completed !== null) {
    $fields[] = 'completed = ?';
    $params[] = $completed ? 1 : 0;
    $types .= 'i';
}

$params[] = $task_id;
$types .= 'i';

$sql = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

//-------------[Exécution de la requête de mise à jour]----------------------
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la mise à jour de la tâche.']);
}

$stmt->close();
$conn->close();
?>

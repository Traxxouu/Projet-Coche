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
$list_id = intval($data['id']);

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

//-------------[Vérification de la propriété de la liste]----------------------
$stmt = $conn->prepare("SELECT id FROM lists WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $list_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403); // Interdit
    echo json_encode(['error' => 'Liste non trouvée ou accès non autorisé.']);
    exit();
}
$stmt->close();

//-------------[Suppression de la liste]----------------------
$stmt = $conn->prepare("DELETE FROM lists WHERE id = ?");
$stmt->bind_param("i", $list_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la suppression de la liste.']);
}

$stmt->close();
$conn->close();
?>

<?php
session_start();

//-------------[Vérification de la session utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Non autorisé error
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

//-------------[Récupération des données d'entrée]----------------------
$data = json_decode(file_get_contents('php://input'), true);

// Vérifier si 'name' est défini et non nul
if (!isset($data['name']) || is_null($data['name'])) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(['error' => 'Le nom de la liste est requis.']);
    exit();
}

$name = trim($data['name']); // Utilisation de trim() maintenant sécurisée

if (empty($name)) {
    http_response_code(400); // Mauvaise requête
    echo json_encode(['error' => 'Le nom de la liste est requis.']);
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

//-------------[Insertion des données dans la base]----------------------
$stmt = $conn->prepare("INSERT INTO lists (user_id, name) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $name);

if ($stmt->execute()) {
    $list_id = $stmt->insert_id;
    echo json_encode(['success' => true, 'list_id' => $list_id, 'name' => $name]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la création de la liste.']);
}

$stmt->close();
$conn->close();
?>
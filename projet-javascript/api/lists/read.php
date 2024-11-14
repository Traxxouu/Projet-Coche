<?php
session_start();

//-------------[Vérification de la session utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utilisateur non connecté.']);
    exit();
}

//-------------[Connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données.']);
    exit();
}

$user_id = $_SESSION['user_id'];

//-------------[Requête pour obtenir les listes et les tâches]----------------------
$sql = "
SELECT l.id, l.name,
       COUNT(t.id) AS total_tasks,
       SUM(t.completed) AS completed_tasks
FROM lists l
LEFT JOIN tasks t ON l.id = t.list_id
WHERE l.user_id = ?
GROUP BY l.id, l.name
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$lists = [];
while ($row = $result->fetch_assoc()) {
    // S'assurer que completed_tasks n'est pas null
    $row['completed_tasks'] = $row['completed_tasks'] ? intval($row['completed_tasks']) : 0;
    $row['total_tasks'] = intval($row['total_tasks']);
    $lists[] = $row;
}

//-------------[Retourner les résultats en JSON]----------------------
echo json_encode(['success' => true, 'lists' => $lists]);

$stmt->close();
$conn->close();
?>

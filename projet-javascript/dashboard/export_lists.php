<?php
session_start();

//-------------[Vérification de la connexion utilisateur]----------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login/index.html");
    exit();
}

//-------------[Connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

//-------------[Récupération des listes et tâches de l'utilisateur]----------------------
$user_id = $_SESSION['user_id'];

// Récupérer les listes
$lists_stmt = $conn->prepare("SELECT id, name FROM lists WHERE user_id = ?");
$lists_stmt->bind_param("i", $user_id);
$lists_stmt->execute();
$lists_result = $lists_stmt->get_result();

$csv_data = [];

// Boucle à travers les listes et récupérer les tâches
while ($list = $lists_result->fetch_assoc()) {
    $list_id = $list['id'];
    $list_name = $list['name'];

    // Récupérer les tâches pour la liste
    $tasks_stmt = $conn->prepare("SELECT text,date,completed FROM tasks WHERE list_id = ?");
    $tasks_stmt->bind_param("i", $list_id);
    $tasks_stmt->execute();
    $tasks_result = $tasks_stmt->get_result();

    while ($task = $tasks_result->fetch_assoc()) {
        $csv_data[] = [
            'List Name' => $list_name,
            'Task' => $task['text'],
            'Date' => $task['date'],
            'Completed' => $task['completed'] ? 'Yes' : 'No',
        ];
    }
}

$lists_stmt->close();
$conn->close();

//-------------[Génération du fichier CSV]----------------------
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=mes_listes.csv');

$output = fopen('php://output', 'w');

// En-têtes des colonnes
fputcsv($output, array('List Name', 'Task', 'Date', 'Completed'));

// Données
foreach ($csv_data as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>

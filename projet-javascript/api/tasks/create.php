<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

//-------------[Informations de connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

try {
    //-------------[Connexion à la base de données]----------------------
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //-------------[Récupération des données JSON envoyées par la requête]----------------------
    $data = json_decode(file_get_contents('php://input'), true);

    //-------------[Vérification des données reçues]----------------------
    if (!isset($data['list_id'], $data['text'], $data['date'])) {
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
        exit;
    }

    //-------------[Assignation des valeurs reçues aux variables PHP]----------------------
    $listId = $data['list_id'];
    $text = $data['text'];
    $date = $data['date'];

    // Convertir la date au format "YYYY-MM-DD" si nécessaire (par exemple, pour le type DATE en MySQL)
    $formattedDate = date("Y-m-d", strtotime($date));

    //-------------[Préparation de la requête SQL pour insérer la nouvelle tâche]----------------------
    $stmt = $pdo->prepare("INSERT INTO tasks (list_id, text, date) VALUES (:list_id, :text, :date)");
    $stmt->bindParam(':list_id', $listId, PDO::PARAM_INT);
    $stmt->bindParam(':text', $text, PDO::PARAM_STR);
    $stmt->bindParam(':date', $formattedDate, PDO::PARAM_STR);
    $stmt->execute();

    //-------------[Envoi de la réponse JSON indiquant que l'insertion a réussi]----------------------
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    //-------------[En cas d'erreur, envoi de l'erreur sous forme de JSON]----------------------
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

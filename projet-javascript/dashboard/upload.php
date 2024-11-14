<?php
session_start();

// Assurer que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>Utilisateur non connecté.</p>";
    exit();
}

$user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur connecté

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['csv_file']['tmp_name'];
    $file_name = $_FILES['csv_file']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Vérification que le fichier est bien un CSV
    if ($file_ext !== 'csv') {
        echo "<p style='color:red;'>Le fichier doit être un fichier CSV.</p>";
    } else {
        // Ouvrir et lire le fichier CSV
        if (($handle = fopen($file_tmp, 'r')) !== FALSE) {
            // Sauter la ligne d'en-tête
            fgetcsv($handle);

            // Lire ligne par ligne
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $list_name = trim($data[0]);  // Nom de la liste
                $task_name = trim($data[1]);  // Nom de la tâche
                $task_date = trim($data[2]);  // Date de la tâche
                $task_completed = strtolower(trim($data[3])) === 'yes' ? 1 : 0;  // Tâche complétée (0/1)

                // Vérifier si la liste existe déjà pour cet utilisateur
                $stmt = $conn->prepare("SELECT id FROM lists WHERE name = ? AND user_id = ?");
                $stmt->bind_param("si", $list_name, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Si la liste existe, récupérer son ID
                    $list = $result->fetch_assoc();
                    $list_id = $list['id'];
                } else {
                    // Si la liste n'existe pas, la créer pour cet utilisateur
                    $stmt = $conn->prepare("INSERT INTO lists (user_id, name) VALUES (?, ?)");
                    $stmt->bind_param("is", $user_id, $list_name);
                    $stmt->execute();
                    $list_id = $stmt->insert_id;  // Récupérer l'ID de la nouvelle liste
                }

                // Insérer la tâche dans la base de données
                $stmt = $conn->prepare("INSERT INTO tasks (list_id, text, date, completed) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("issi", $list_id, $task_name, $task_date, $task_completed);
                $stmt->execute();
            }

            fclose($handle);
            echo "<p style='color:green;'>Fichier CSV importé avec succès dans la base de données.</p>";
            
            // Redirection après succès
            header('Location: ../accueil/liste-tache/index.php');
            exit();
        } else {
            echo "<p style='color:red;'>Impossible de lire le fichier CSV.</p>";
        }
    }
} else {
    echo "<p style='color:red;'>Aucun fichier n'a été téléchargé ou il y a eu une erreur lors de l'upload.</p>";
}

$conn->close();
?>

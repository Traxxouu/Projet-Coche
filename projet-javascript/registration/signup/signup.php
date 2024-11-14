<?php
// Démarrer la session
session_start();

//-------------[Connexion à la base de données]----------------------
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

//-------------[Traitement du formulaire]----------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et assainir les données du formulaire
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];

    // Hacher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Préparer la déclaration SQL pour éviter les injections SQL
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, country) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $gender, $country);

    // Exécuter la déclaration
    if ($stmt->execute()) {
        // Inscription réussie, rediriger vers la page de connexion
        header("Location: ../login/index.html");
        exit();
    } else {
        // Gérer les erreurs (par exemple, email déjà existant)
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

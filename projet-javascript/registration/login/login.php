
<?php
// Démarrer la session
session_start();

// Connexion à la base de données
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Gérer la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et assainir les données du formulaire
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Préparer la déclaration SQL
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Exécuter la décla.
    $stmt->execute();
    $stmt->store_result();

    // Vérifier si l'utilisateur existe
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password); //hash pour la SECU
        $stmt->fetch();

        // Vérifier le mot de passe et le hasher
        if (password_verify($password, $hashed_password)) {
            // Le mot de passe est correct alords => démarrer la session utilisateur
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Rediriger vers le tableau de bord
            header("Location: ../../dashboard/index.php");
            exit();
            // erreur mot de passe ou pas d'users
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun utilisateur trouvé avec cet email.";
    }

    $stmt->close();
}

$conn->close();
?>

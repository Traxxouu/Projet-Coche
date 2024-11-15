----------------------[A MODIFIER]-----------------------

<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_dir = "../uploads/";



$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "db_coche";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
$stmt = $conn->prepare("SELECT custom_background FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($custom_background);
$stmt->fetch();
$stmt->close();

if ($custom_background) {
    $file_path = $upload_dir . $custom_background;
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    $stmt = $conn->prepare("UPDATE users SET custom_background = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: /projet-javascript/accueil/index.php");
exit();
?>

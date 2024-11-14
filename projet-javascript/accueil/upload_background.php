<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../registration/login/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$upload_dir = "../uploads/";
$allowed_types = ['image/png', 'image/jpeg', 'image/webp'];

// ChatG - a verif 
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // 0777 permissions
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['background'])) {
    $file = $_FILES['background'];

    if (!in_array($file['type'], $allowed_types)) {
        die("Erreur: Format d'image non supporté.");
    }

    list($width, $height) = getimagesize($file['tmp_name']);
    if ($width != 1920 || $height != 1080) {
        die("Erreur: L'image doit être de 1920x1080 pixels.");
    }

    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = "background_user_$user_id.$file_extension";
    $target_path = $upload_dir . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        $stmt = $conn->prepare("UPDATE users SET custom_background = ? WHERE id = ?");
        $stmt->bind_param("si", $new_filename, $user_id);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        
        header("Location: index.php");
    } else {
        echo "Erreur: Échec de l'upload.";
    }
} else {
    echo "Erreur: Aucune image uploadée.";
}

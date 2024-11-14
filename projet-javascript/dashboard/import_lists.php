<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto-Upload CSV</title>
</head>
<body>

<h2>Appuyez sur n'importe quelle touche pour sélectionner un fichier CSV</h2>

<!-- Formulaire caché -->
<form id="csvUploadForm" action="upload.php" method="post" enctype="multipart/form-data" style="display:none;">
    <input type="file" id="csvFileInput" name="csv_file" accept=".csv">
</form>

<script>
// Attendre que l'utilisateur appuie sur une touche pour ouvrir les dossier
document.addEventListener('keydown', function() {
    // Ouvrir la boîte de dialogue de sélection de fichier
    const fileInput = document.getElementById('csvFileInput');
    fileInput.click();

    // Lorsque le fichier est sélectionné, soumettre automatiquement le formulaire
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('csvUploadForm').submit();
        }
    });
}, { once: true }); // L'événement ne se déclenche qu'une seule fois
</script>

</body>
</html>

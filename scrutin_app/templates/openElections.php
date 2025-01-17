<?php
ini_set('session.save_path', '/tmp');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: acceuil.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scrutins ouverts</title>
</head>
<body>
    <h1>Scrutins ouverts</h1>
    <p>Liste des scrutins ouverts ici...</p>
    <a href="home.php">Retour à l'accueil</a>
</body>
</html>
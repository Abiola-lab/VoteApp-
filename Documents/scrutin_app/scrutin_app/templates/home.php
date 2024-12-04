<?php
session_start();


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
    <title>Page d'accueil</title>
</head>
<body>
    <h1>Bienvenue sur la plateforme de scrutin</h1>
    
    <h2>Bonjour, 
        <?php
        
        echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Utilisateur';
        ?> !
    </h2>
    <a href="profile.php">Voir mon profil</a><br>
    <a href="openElections.php">Voir les scrutins ouverts</a><br>
    <a href="logout.php">Se déconnecter</a>
</body>
</html>

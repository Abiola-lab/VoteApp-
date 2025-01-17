<?php
ini_set('session.save_path', '/tmp');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$usernameDB = 'root'; 
$passwordDB = 'abiola'; 

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['username'])) {
        header("Location: login.php"); 
        exit();
    }

    // Récupérer le nom d'utilisateur depuis la session
    $usernameInput = $_SESSION['username'];

    // Requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT firstname, lastname, email, username, role FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $usernameInput]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'utilisateur</title>
</head>
<body>
    <h1>Profil de l'utilisateur</h1>
    <p>Nom complet : <?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']); ?></p>
    <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Nom d'utilisateur : <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Rôle : <?php echo htmlspecialchars($user['role']); ?></p>

    <a href="home.php">Retour à l'accueil</a><br>
    <a href="logout.php">Déconnexion</a> 
</body>
</html>. 
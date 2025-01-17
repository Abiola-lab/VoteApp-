<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root'; 
$password = 'abiola'; 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['username'])) {
        header("Location: login.php"); 
        exit();
    }

    // Récupérer les informations de l'utilisateur
    $usernameInput = $_SESSION['username'];
    $sql = "SELECT firstname, lastname, email, username, role FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $usernameInput]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si l'utilisateur existe
    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit();
    }

    // Récupérer les scrutins auxquels l'utilisateur a participé
    $sql = "
        SELECT elections.question, elections.opening_date, elections.closing_date
        FROM votes
        JOIN elections ON votes.election_id = elections.id
        WHERE votes.user_id = :user_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $participatedElections = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
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

    <h2>Scrutins auxquels vous avez participé :</h2>
    <?php if (!empty($participatedElections)): ?>
        <ul>
            <?php foreach ($participatedElections as $election): ?>
                <li>
                    <strong>Question :</strong> <?php echo htmlspecialchars($election['question']); ?><br>
                    <strong>Ouverture :</strong> <?php echo htmlspecialchars($election['opening_date']); ?><br>
                    <strong>Fermeture :</strong> <?php echo htmlspecialchars($election['closing_date']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Vous n'avez participé à aucun scrutin pour le moment.</p>
    <?php endif; ?>

    <a href="logout.php">Déconnexion</a> 
</body>
</html>
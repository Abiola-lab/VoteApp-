<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: acceuil.html");
    exit();
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$usernameDB = 'root'; // Remplace si nécessaire
$passwordDB = ''; // Remplace si nécessaire

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Gestion des votes
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
        $election_id = $_POST['election_id'];
        $choice = $_POST['choice'];
        $user_id = $_SESSION['user_id'];
        $voting_date = date('Y-m-d H:i:s');
        $mode = "direct";

        // Vérifier si l'utilisateur a déjà voté
        $stmt = $pdo->prepare("SELECT * FROM votes WHERE user_id = ? AND election_id = ?");
        $stmt->execute([$user_id, $election_id]);
        $existing_vote = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_vote) {
            $stmt = $pdo->prepare("INSERT INTO votes (user_id, election_id, voting_date, choice, mode) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $election_id, $voting_date, $choice, $mode]);
            $_SESSION['message'] = "Votre vote a été enregistré avec succès.";
        } else {
            $_SESSION['message'] = "Vous avez déjà voté pour ce scrutin.";
        }
        header("Location: home.php");
        exit();
    }

    // Requête pour récupérer les 10 derniers scrutins actifs
    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT * FROM elections WHERE closing_date >= ? ORDER BY opening_date DESC LIMIT 10");
    $stmt->execute([$current_date]);
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</p>";
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

    <hr>

    <!-- Affichage du message de confirmation -->
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_SESSION['message']); ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <h2>Derniers scrutins actifs</h2>
    <?php if (!empty($elections)): ?>
        <ul>
            <?php foreach ($elections as $election): ?>
                <li>
                    <strong>Question :</strong> <?php echo htmlspecialchars($election['question']); ?><br>
                    <strong>Ouverture :</strong> <?php echo htmlspecialchars($election['opening_date']); ?><br>
                    <strong>Fermeture :</strong> <?php echo htmlspecialchars($election['closing_date']); ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucun scrutin actif pour le moment.</p>
    <?php endif; ?>
</body>
</html>
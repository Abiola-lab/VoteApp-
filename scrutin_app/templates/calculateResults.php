<?php
// Inclusion de la classe ElectionSystem
require '../src/ElectionSystem.php';

use Akobiabiolaabdbarr\ScrutinApp\ElectionSystem;

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root'; // Remplace si nécessaire
$password = 'abiola'; // Remplace si nécessaire

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier l'ID du scrutin depuis l'URL
    $scrutinId = isset($_GET['scrutin']) ? (int) $_GET['scrutin'] : 0;
    if ($scrutinId <= 0) {
        die("ID de scrutin invalide.");
    }

    // Charger les données du scrutin
    $stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ?");
    $stmt->execute([$scrutinId]);
    $scrutin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$scrutin) {
        die("Scrutin introuvable.");
    }

    // Charger les options associées au scrutin
    $stmt = $pdo->prepare("SELECT * FROM options WHERE election_id = ?");
    $stmt->execute([$scrutinId]);
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Charger les votes associés au scrutin
    $stmt = $pdo->prepare("SELECT * FROM votes WHERE election_id = ?");
    $stmt->execute([$scrutinId]);
    $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($candidates)) {
        die("Aucune option trouvée pour ce scrutin.");
    }

    // Vérifier qu'il y a des votes
    if (empty($votes)) {
        die("Aucun vote enregistré pour ce scrutin.");
    }

    // Initialiser l'objet ElectionSystem
    $electionSystem = new ElectionSystem();

    // Déterminer le système de vote
    $electionType = $scrutin['election_type'];

    // Calculer les résultats en fonction du type d'élection
    $results = [];
    switch ($electionType) {
        case 'majoritarian':
            $results['Vote majoritaire'] = $electionSystem->majoritarian($votes);
            break;

        case 'two_round':
            $results['Vote à deux tours'] = $electionSystem->twoRoundSystem($votes, $candidates);
            break;

        case 'borda':
            $results['Vote par points (Borda)'] = $electionSystem->borda($votes, $candidates);
            break;

        case 'condorcet':
            $results['Vote de Condorcet'] = $electionSystem->condorcet($votes, $candidates);
            break;

        default:
            die("Type d'élection inconnu.");
    }

    // Distribution des votes pour chaque candidat
    $voteCounts = [];
    foreach ($candidates as $candidate) {
        $candidateId = $candidate['id'];
        $voteCounts[$candidate['option_text']] = array_reduce(
            $votes,
            function ($carry, $vote) use ($candidateId) {
                return $carry + ($vote['choice'] == $candidateId ? 1 : 0);
            },
            0
        );
    }
} catch (PDOException $e) {
    die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats</title>
</head>
<body>
    <h1>Résultats pour : <?php echo htmlspecialchars($scrutin['question']); ?></h1>

    <h2>Distribution des votes</h2>
    <ul>
        <?php foreach ($voteCounts as $option => $count): ?>
            <li><?php echo htmlspecialchars($option); ?> : <?php echo $count; ?> votes</li>
        <?php endforeach; ?>
    </ul>

    <h2>Résultats des systèmes de vote</h2>
    <?php foreach ($results as $system => $winner): ?>
        <p><strong><?php echo htmlspecialchars($system); ?></strong> : 
           Le gagnant est <?php echo htmlspecialchars($winner); ?></p>
    <?php endforeach; ?>
</body>
</html>
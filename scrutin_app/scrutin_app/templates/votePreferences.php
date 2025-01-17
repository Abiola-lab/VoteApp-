<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour classer vos préférences.";
    exit();
}

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8'; // Correction de la base de données
$username = 'root';
$password = 'abiola';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les options disponibles pour l'élection en cours
    $election_id = $_GET['election_id'];
    $sql = "SELECT * FROM options WHERE election_id = :election_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['election_id' => $election_id]);
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($options) {
        echo '<form action="processPreferences.php" method="post">';
        echo '<label for="preferences">Classez vos préférences :</label>';
        echo '<ol id="preferenceList">';

        foreach ($options as $option) {
            echo '<li><input type="text" name="preference[]" value="' . htmlspecialchars($option['option_text']) . '" readonly></li>';
        }

        echo '</ol>';
        echo '<button type="submit">Soumettre mes préférences</button>';
        echo '</form>';
    } else {
        echo "<p>Aucune option disponible pour cette élection.</p>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voter.";
    exit();
}

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root';  
$password = 'abiola';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['election_id'])) {
        $election_id = $_GET['election_id'];

        $sql = "SELECT * FROM options WHERE election_id = :election_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['election_id' => $election_id]);
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($options) {
            echo "<h1>Vote pour l'élection</h1>";
            echo '<form action="processVote.php" method="POST">';
            echo '<input type="hidden" name="election_id" value="' . htmlspecialchars($election_id) . '">';

            // Affichage des options avec un champ pour le classement
            foreach ($options as $option) {
                echo '<div>';
                echo '<label>' . htmlspecialchars($option['option_text']) . '</label>';
                // Champ pour entrer le rang de l'option
                echo '<input type="number" name="ranking[' . htmlspecialchars($option['id']) . ']" min="1" required>';
                echo '</div>';
            }

            echo '<input type="submit" value="Voter">';
            echo '</form>';
        } else {
            echo "<p>Aucune option disponible pour cette élection.</p>";
        }
    } else {
        echo "<p>ID d'élection manquant.</p>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root'; 
$password = 'abiola';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql = "SELECT * FROM elections WHERE opening_date <= NOW() AND closing_date >= NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h1>Scrutins ouverts</h1>";
    
    if (!empty($elections)) {
        foreach ($elections as $election) {
            echo "<div>";
            echo "<h2>" . htmlspecialchars($election['question']) . "</h2>";
            echo "<p>" . htmlspecialchars($election['explanation']) . "</p>";
            echo "<p>Type d'élection: " . htmlspecialchars($election['election_type']) . "</p>";
            echo "<p>Date de fermeture: " . htmlspecialchars($election['closing_date']) . "</p>";
            
            
            echo '<form action="vote.php" method="GET">';
            echo '<input type="hidden" name="election_id" value="' . htmlspecialchars($election['id']) . '">';
            echo '<input type="submit" value="Voter">';
            echo '</form>';

            echo "</div><hr>";
        }
    } else {
        echo "<p>Aucun scrutin ouvert.</p>";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

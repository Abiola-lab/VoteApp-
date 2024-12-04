<?php
// Initialisation de la matrice de préférences
$preferenceMatrix = [];

// Supposons que $allVotersPreferences est déjà défini avec les préférences des votants
// Vous devez récupérer les préférences depuis la base de données
$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root';  
$password = 'abiola';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT choice FROM votes";
    $stmt = $pdo->query($sql);
    $allVotersPreferences = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Parcourir chaque préférence de chaque votant
    foreach ($allVotersPreferences as $preferences) {
        $numOptions = count($preferences);
        for ($i = 0; $i < $numOptions; $i++) {
            for ($j = $i + 1; $j < $numOptions; $j++) {
                $optionA = $preferences[$i];
                $optionB = $preferences[$j];

                // Initialiser les paires si elles n'existent pas encore
                if (!isset($preferenceMatrix[$optionA][$optionB])) {
                    $preferenceMatrix[$optionA][$optionB] = 0;
                }
                if (!isset($preferenceMatrix[$optionB][$optionA])) {
                    $preferenceMatrix[$optionB][$optionA] = 0;
                }

                // Comparaison par paires : ajouter/soustraire selon les préférences
                if ($i < $j) {
                    $preferenceMatrix[$optionA][$optionB] += 1;
                    $preferenceMatrix[$optionB][$optionA] -= 1;
                } else {
                    $preferenceMatrix[$optionA][$optionB] -= 1;
                    $preferenceMatrix[$optionB][$optionA] += 1;
                }
            }
        }
    }

    // Déterminer les options gagnantes ici selon la méthode de Condorcet
    // Ajoutez la logique pour trouver le gagnant selon la matrice de préférences

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

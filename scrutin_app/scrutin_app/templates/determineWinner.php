<?php
function determineCondorcetWinner($preferenceMatrix) {
    $options = array_keys($preferenceMatrix);
    
    foreach ($options as $optionA) {
        $isWinner = true;
        
        foreach ($options as $optionB) {
            if ($optionA !== $optionB) {
                if (($preferenceMatrix[$optionA][$optionB] ?? 0) <= 0) {
                    $isWinner = false;
                    break;
                }
            }
        }
        
        if ($isWinner) {
            return $optionA; // Gagnant Condorcet trouvé
        }
    }
    
    return null; // Aucun gagnant Condorcet trouvé
}

// Utilisation de la fonction avec la matrice de préférences
$gagnant = determineCondorcetWinner($preferenceMatrix);
echo $gagnant ? "Le gagnant Condorcet est : $gagnant" : "Aucun gagnant Condorcet trouvé.";
?>
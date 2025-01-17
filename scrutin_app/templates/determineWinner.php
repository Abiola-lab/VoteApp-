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

// Utilisation de la fonction avec une matrice de préférences simulée
$preferenceMatrix = [
    'Option1' => ['Option2' => 3, 'Option3' => 1],
    'Option2' => ['Option1' => -3, 'Option3' => 2],
    'Option3' => ['Option1' => -1, 'Option2' => -2],
];

$winner = determineCondorcetWinner($preferenceMatrix);
echo $winner ? "Le gagnant Condorcet est : $winner" : "Aucun gagnant Condorcet trouvé.";
?>
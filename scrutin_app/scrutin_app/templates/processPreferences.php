<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $preferences = $_POST['preferences']; // Récupère les préférences ordonnées

    try {
        // Préparation pour insérer chaque option avec son ordre de préférence
        $pdo->beginTransaction();

        // Suppression des anciennes préférences pour ce votant (si besoin)
        $deleteStmt = $pdo->prepare("DELETE FROM preferences WHERE user_id = :user_id");
        $deleteStmt->execute([':user_id' => $userId]);

        // Insertion des nouvelles préférences
        $insertStmt = $pdo->prepare("INSERT INTO preferences (user_id, option_id, preference_order) VALUES (:user_id, :option_id, :preference_order)");
        
        foreach ($preferences as $order => $optionId) {
            $insertStmt->execute([
                ':user_id' => $userId,
                ':option_id' => $optionId,
                ':preference_order' => $order + 1 // Index +1 pour l'ordre
            ]);
        }

        $pdo->commit();
        echo "Préférences enregistrées avec succès !";
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erreur lors de l'enregistrement des préférences : " . $e->getMessage();
    }
} else {
    echo "Vous devez être connecté pour soumettre vos préférences.";
}
?>

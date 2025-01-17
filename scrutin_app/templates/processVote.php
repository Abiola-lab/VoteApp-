<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['election_id']) || !isset($_POST['ranking'])) {
        echo "Paramètres de vote manquants. Veuillez vérifier que vous avez sélectionné une option.";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $election_id = $_POST['election_id'];
    $voting_date = date("Y-m-d H:i:s");
    $mode = 'online';

    $rankings = $_POST['ranking'];

    $dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
    $username = 'root';
    $password = 'abiola';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Vérifier si l'utilisateur a déjà voté pour cette élection
        $sql = "SELECT COUNT(*) FROM votes WHERE user_id = :user_id AND election_id = :election_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':election_id' => $election_id]);
        $voteCount = $stmt->fetchColumn();

        if ($voteCount > 0) {
            echo "Vous avez déjà voté pour cette élection.";
            exit();
        }

        // Enregistrer chaque vote avec son classement
        foreach ($rankings as $option_id => $rank) {
            $sql = "INSERT INTO votes (user_id, election_id, voting_date, choice, mode) 
                    VALUES (:user_id, :election_id, :voting_date, :choice, :mode)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $user_id,
                ':election_id' => $election_id,
                ':voting_date' => $voting_date,
                ':choice' => $option_id,
                ':mode' => $mode
            ]);
        }

        echo "Votre vote a été enregistré avec succès.";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Méthode de requête non autorisée.";
}
?>

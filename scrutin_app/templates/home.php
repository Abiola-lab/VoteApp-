<?php
ini_set('session.save_path', '/tmp');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: acceuil.html");
    exit();
}

$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$usernameDB = 'root';
$passwordDB = 'abiola';

try {
    $pdo = new PDO($dsn, $usernameDB, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Gestion de la création d'une élection
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_election'])) {
        $opening_date = $_POST['opening_date'];
        $closing_date = $_POST['closing_date'];
        $election_type = $_POST['election_type'];
        $question = $_POST['question'];
        $explanation = $_POST['explanation'];
        $options = $_POST['options'];

        // Enregistrer l'élection dans la table `elections`
        $stmt = $pdo->prepare("INSERT INTO elections (opening_date, closing_date, election_type, question, explication) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$opening_date, $closing_date, $election_type, $question, $explanation]);
        $election_id = $pdo->lastInsertId();

        // Enregistrer les options dans la table `options`
        $stmt = $pdo->prepare("INSERT INTO options (election_id, option_text) VALUES (?, ?)");
        foreach ($options as $option) {
            $stmt->execute([$election_id, $option]);
        }

        header("Location: home.php");
        exit();
    }

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
        }
    }
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
    <h2>Bonjour, <?php echo htmlspecialchars($_SESSION['firstname'] ?? ''); ?> !</h2>

    <a href="profile.php">Voir mon profil</a><br>

    <hr>

    <h2>Créer une nouvelle élection</h2>
    <form method="post" action="home.php">
        <input type="hidden" name="create_election" value="1">
        
        <label>Question du scrutin :</label><br>
        <input type="text" name="question" required><br>

        <label>Explication :</label><br>
        <textarea name="explanation" rows="4" cols="50" placeholder="Ajoutez une explication pour ce scrutin..." required></textarea><br>

        <label>Date d'ouverture :</label><br>
        <input type="date" name="opening_date" required><br>

        <label>Date de fermeture :</label><br>
        <input type="date" name="closing_date" required><br>

        <label>Type d'élection :</label><br>
        <select name="election_type">
            <option value="majoritarian">Vote majoritaire</option>
            <option value="two_round">Vote à deux tours</option>
            <option value="borda">Vote par points (Borda)</option>
            <option value="condorcet">Vote de Condorcet</option>
        </select><br>

        <label>Options :</label><br>
        <div id="options-container">
            <input type="text" name="options[]" placeholder="Option 1" required><br>
        </div>
        <button type="button" id="add-option">Ajouter une option</button><br><br>

        <button type="submit">Créer l'élection</button>
    </form>

    <script>
        // Script pour ajouter dynamiquement des champs pour les options
        document.getElementById('add-option').addEventListener('click', function () {
            const container = document.getElementById('options-container');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.placeholder = `Option ${container.children.length + 1}`;
            input.required = true;
            container.appendChild(input);
            container.appendChild(document.createElement('br'));
        });
    </script>

    <hr>

    <h2>Élections en cours</h2>
    <?php
    $current_date = date('Y-m-d');
    $stmt = $pdo->prepare("SELECT * FROM elections WHERE closing_date >= ?");
    $stmt->execute([$current_date]);
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($elections)) {
        echo "<p>Aucune élection en cours.</p>";
    } else {
        echo "<ul>";
        foreach ($elections as $election) {
            echo "<li>";
            echo "<strong>Question :</strong> " . htmlspecialchars($election['question'] ?? '') . "<br>";
            echo "<strong>Explication :</strong> " . htmlspecialchars($election['explication'] ?? 'Non renseignée') . "<br>";
            echo "Ouverture : " . htmlspecialchars($election['opening_date'] ?? '') . "<br>";
            echo "Fermeture : " . htmlspecialchars($election['closing_date'] ?? '') . "<br>";

            // Charger les options associées au scrutin
            $options_stmt = $pdo->prepare("SELECT * FROM options WHERE election_id = ?");
            $options_stmt->execute([$election['id']]);
            $options = $options_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Charger les votes associés au scrutin
            $votes_stmt = $pdo->prepare("SELECT choice FROM votes WHERE election_id = ?");
            $votes_stmt->execute([$election['id']]);
            $votes = $votes_stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($options)) {
                // Construire un tableau associatif des options (id => texte)
                $optionMap = [];
                foreach ($options as $option) {
                    $optionMap[$option['id']] = $option['option_text'];
                }

                // Formulaire pour voter
                echo "<h3>Votez pour ce scrutin :</h3>";
                echo "<form method='post' action='home.php'>";
                echo "<input type='hidden' name='election_id' value='" . htmlspecialchars($election['id']) . "'>";
                foreach ($options as $option) {
                    echo "<input type='radio' name='choice' value='" . htmlspecialchars($option['id']) . "' required> " . htmlspecialchars($option['option_text']) . "<br>";
                }
                echo "<button type='submit' name='vote'>Soumettre mon vote</button>";
                echo "</form>";

                // Afficher les résultats
                echo "<h3>Résultats (provisoires) :</h3>";
                require_once '../src/ElectionSystem.php';
                $electionSystem = new \Akobiabiolaabdbarr\ScrutinApp\ElectionSystem();

                switch ($election['election_type']) {
                    case 'majoritarian':
                        echo "<p><strong>Système :</strong> Vote majoritaire</p>";
                        if (!empty($votes)) {
                            $winnerId = $electionSystem->majoritarian($votes);
                            $winnerText = $optionMap[$winnerId] ?? "Inconnu";
                            echo "<p>Gagnant : " . htmlspecialchars($winnerText) . "</p>";
                        } else {
                            echo "<p>Aucun vote enregistré pour ce scrutin.</p>";
                        }
                        break;

                    case 'two_round':
                        echo "<p><strong>Système :</strong> Vote à deux tours</p>";
                        if (!empty($votes)) {
                            $result = $electionSystem->twoRoundSystem($votes, $options);
                            echo "<p>Résultat : " . htmlspecialchars($result) . "</p>";
                        } else {
                            echo "<p>Aucun vote enregistré pour ce scrutin.</p>";
                        }
                        break;

                    case 'borda':
                        echo "<p><strong>Système :</strong> Vote par points (Borda)</p>";
                        if (!empty($votes)) {
                            $winnerId = $electionSystem->borda($votes, $options);
                            $winnerText = $optionMap[$winnerId] ?? "Inconnu";
                            echo "<p>Gagnant : " . htmlspecialchars($winnerText) . "</p>";
                        } else {
                            echo "<p>Aucun vote enregistré pour ce scrutin.</p>";
                        }
                        break;

                    case 'condorcet':
                        echo "<p><strong>Système :</strong> Vote de Condorcet</p>";
                        if (!empty($votes)) {
                            $winnerId = $electionSystem->condorcet($votes, $options);
                            $winnerText = $optionMap[$winnerId] ?? "Inconnu";
                            echo "<p>Gagnant : " . htmlspecialchars($winnerText) . "</p>";
                        } else {
                            echo "<p>Aucun vote enregistré pour ce scrutin.</p>";
                        }
                        break;

                    default:
                        echo "<p><strong>Erreur :</strong> Type de scrutin inconnu ou non géré. Veuillez vérifier les données enregistrées dans la base de données.</p>";
                        break;
                }
            } else {
                echo "<p>Aucune option enregistrée pour ce scrutin.</p>";
            }

            echo "</li><br>";
        }
        echo "</ul>";
    }
    ?>

    <hr>

    <!-- Bouton de déconnexion -->
    <a href="logout.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px;">Se déconnecter</a>
</body>
</html>
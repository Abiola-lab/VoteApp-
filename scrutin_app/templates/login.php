<?php
ini_set('session.save_path', '/tmp'); // Chemin pour stocker les sessions
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
    $usernameDB = 'root';
    $passwordDB = 'abiola';

    try {
        $pdo = new PDO($dsn, $usernameDB, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $usernameInput = trim($_POST['username']);
        $passwordInput = trim($_POST['password']);

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $usernameInput]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($passwordInput, $user['password'])) {
            // Stocker les données utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $usernameInput;
            $_SESSION['firstname'] = $user['firstname'];

            // Redirection vers home.php après connexion réussie
            header("Location: home.php");
            exit();
        } else {
            echo "<p style='color:red;'>Nom d'utilisateur ou mot de passe incorrect.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur de connexion : " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion utilisateur</title>
</head>
<body>
    <h1>Connexion</h1>
    <form action="login.php" method="POST">
        <div>
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required />
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required />
        </div>
        <div>
            <input type="submit" value="Se connecter" />
        </div>
    </form>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un utilisateur</title>
</head>
<body>
    <h1>Inscription</h1>
    <form action="createUser.php" method="post">
        <div>
            <label for="firstName">Prénom</label>
            <input type="text" name="firstName" id="firstName" required />
        </div>
        <div>
            <label for="lastName">Nom de famille</label>
            <input type="text" name="lastName" id="lastName" required />
        </div>
        <div>
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" id="username" required />
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required />
        </div>
        <div>
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required />
        </div>
        <div>
            <label for="role">Rôle</label>
            <select name="role" id="role">
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
        </div>
        <div>
            <input type="submit" name="submit" id="submit" value="Créer" />
        </div>
    </form>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Vote Preferences</title>
</head>
<body>
    <form method="post" action="processPreferences.php">
        <h2>Classez les candidats par ordre de préférence :</h2>
        <input type="text" name="preferences[]" placeholder="1er choix"><br>
        <input type="text" name="preferences[]" placeholder="2ème choix"><br>
        <input type="text" name="preferences[]" placeholder="3ème choix"><br>

        <h2>Choisissez le système de vote :</h2>
        <select name="system">
            <option value="majoritarian">Vote majoritaire simple</option>
            <option value="two_round">Vote à deux tours</option>
            <option value="borda">Vote par points (Borda)</option>
            <option value="condorcet">Vote de Condorcet</option>
        </select><br><br>

        <button type="submit">Soumettre</button>
    </form>
</body>
</html>
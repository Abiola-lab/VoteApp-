<?php
require 'db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $email = $_POST['email'];
    $role = 'user'; 

    $sql = "INSERT INTO users (firstName, lastName, username, password, email, role) 
            VALUES (:firstName, :lastName, :username, :password, :email, :role)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':username' => $username,
        ':password' => $password, 
        ':email' => $email,
        ':role' => $role
    ]);

    echo "Utilisateur créé avec succès. <br>";
    echo "<a href='login.php'>Connectez-vous</a> pour voter.";
}
?>

<?php
$dsn = 'mysql:host=localhost;dbname=scrutin;charset=utf8';
$username = 'root';
$password = 'abiola';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $email = $_POST['email'];
    $role = $_POST['role'];

    
    $sql = "INSERT INTO users (firstname, lastname, username, password, email, role) 
            VALUES (:firstname, :lastname, :username, :password, :email, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'firstname' => $firstName,
        'lastname' => $lastName,
        'username' => $username,
        'password' => $password,
        'email' => $email,
        'role' => $role
    ]);

    
    header("Location: login.php");
    exit();

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

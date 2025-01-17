<?php
$host = 'localhost'; 
$dbname = 'scrutin'; 
$username = 'root'; 
$password = 'abiola'; 

try {
   
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
} catch (PDOException $e) {
    
    echo "Erreur de connexion : " . $e->getMessage();
    die(); 
}
?>

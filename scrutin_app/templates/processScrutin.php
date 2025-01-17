<?php
ini_set('session.save_path', '/tmp');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: acceuil.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scrutinsFile = __DIR__ . '/scrutins.json';

    $scrutins = json_decode(file_get_contents($scrutinsFile), true) ?? [];
    $scrutin = [
        'name' => $_POST['name'],
        'date' => $_POST['date'],
        'system' => $_POST['system'],
        'candidates' => explode(',', $_POST['candidates']),
        'votes' => []
    ];

    $scrutins[] = $scrutin;
    file_put_contents($scrutinsFile, json_encode($scrutins));

    header('Location: home.php');
    exit();
}
?>
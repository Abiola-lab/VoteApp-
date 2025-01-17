<?php
require '../src/ElectionSystem.php';

use Akobiabiolaabdbarr\ScrutinApp\ElectionSystem;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Charger les votes existants
    $votes = json_decode(file_get_contents('votes.json'), true) ?? [];
    $preferences = $_POST['preferences'];
    $votes[] = $preferences;
    file_put_contents('votes.json', json_encode($votes));

    echo "Vote enregistré avec succès.";
}
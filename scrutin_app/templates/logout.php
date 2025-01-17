<?php
ini_set('session.save_path', '/tmp');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

session_destroy();
header("Location: acceuil.html");
exit();
?>
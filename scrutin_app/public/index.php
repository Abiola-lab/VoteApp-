<?php

require_once __DIR__ . " /../vendor/autoload.php";

use   Akobiabiolaabdbarr\ScrutinApp\ClassUser;  
$user = new ClassUser('John', 'password');

$user->setPassWord('pass');
$user->setUserName('Durand_Albert');
$user->setFirstName('Durand');
$user->setLastName('Albert');
$user->setEmail('albert@example.com');
$user->setRole('Admin');

require_once "../templates/profile.html.php";


?> 
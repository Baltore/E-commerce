<?php

// config/database.php

$host = '127.0.0.1';  // Localhost WAMP
$dbname = 'ecommerce_books'; // nom de BDD
$username = 'root'; // Par défaut sous WAMP
$password = ''; // Pas de mdp

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

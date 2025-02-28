<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';

try {
    $stmt = $pdo->query("SELECT 'Connexion réussie !' AS message");
    $result = $stmt->fetch();
    echo "<h2 style='color: green;'>✅ " . $result['message'] . "</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>❌ Erreur de connexion : " . $e->getMessage() . "</h2>";
}
?>

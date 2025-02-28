<?php
require_once __DIR__ . '/../../core/Session.php';
redirectIfNotAdmin();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../public/navbar.css">
</head>
    <header class="admin-header">
        <nav class="admin-navbar">
            <div class="admin-logo">
                <a href="index">⚙️ Admin Panel</a>
            </div>
            <ul class="admin-nav-links">
                <li><a href="dashboard">📊 Mon Site</a></li>
                <li><a href="manage_orders">📦 Commandes</a></li>
                <li><a href="manage_books">📚 Livres</a></li>
                <li><a href="manage_users">👥 Utilisateurs</a></li>
                <li><a href="../views/logout" class="logout-btn">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>
</html>

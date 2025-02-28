<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php';
include __DIR__ . '/../views/partials/navbar.php';
require_once __DIR__ . '/../core/Middleware.php';

Middleware::requireAdmin();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tableau de Bord</title>
    <link rel="stylesheet" href="../public/admin.css">
</head>
<body>

    <section class="admin-dashboard">
        <h2>🔧 Tableau de Bord Administrateur</h2>
        <p>Bienvenue dans l'espace d'administration. Sélectionnez une action :</p>

        <div class="admin-links">
            <a href="dashboard" class="admin-btn">📊 Données du Site </a>
            <a href="manage_orders" class="admin-btn">📦 Gérer les Commandes</a>
            <a href="manage_books" class="admin-btn">📚 Gérer les Livres</a>
            <a href="manage_users" class="admin-btn">👥 Gérer les Utilisateurs</a>
        </div>
    </section>

</body>
</html>

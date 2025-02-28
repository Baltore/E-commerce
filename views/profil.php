<?php
session_start();
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../core/Middleware.php';

Middleware::requireAuth();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupération des informations utilisateur
$stmt = $pdo->prepare("SELECT nom, prenom, email, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Récupération des commandes de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id_user = ? ORDER BY date_commande DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="../public/profil.css">
</head>
<body>

    <section class="profile-container">
        <!-- Carte Profil -->
        <div class="profile-card">
            <h2>👤 Mon Profil <?= ucfirst(htmlspecialchars($user['role'])) ?></h2>
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>

        <!-- Commandes de l'utilisateur -->
        <div class="orders-container">
            <h2>📦 Mes Commandes</h2>
            <?php if (!empty($orders)): ?>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Total (€)</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= number_format($order['total_prix'], 2) ?> €</td>
                                <td><?= $order['date_commande'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty">❌ Aucune commande passée.</p>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>

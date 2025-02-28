<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php';
include __DIR__ . '/../views/partials/navbar.php';
require_once __DIR__ . '/../core/Middleware.php';

Middleware::requireAdmin();

// Récupérer toutes les commandes avec les informations clients
$stmt = $pdo->query("
    SELECT orders.id, users.nom, users.prenom, orders.date_commande, orders.total_prix
    FROM orders
    JOIN users ON orders.id_user = users.id
    ORDER BY orders.date_commande DESC
");
$orders = $stmt->fetchAll();

// Récupérer les détails des commandes
$orderDetails = [];
foreach ($orders as $order) {
    $stmt = $pdo->prepare("
        SELECT books.titre, order_items.quantite, order_items.prix_unitaire
        FROM order_items
        JOIN books ON order_items.id_book = books.id
        WHERE order_items.id_order = ?
    ");
    $stmt->execute([$order['id']]);
    $orderDetails[$order['id']] = $stmt->fetchAll();
}

// Suppression d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = $_POST['delete_order'];

    try {
        $pdo->beginTransaction();

        // Supprimer les items liés à cette commande
        $stmt = $pdo->prepare("DELETE FROM order_items WHERE id_order = ?");
        $stmt->execute([$order_id]);

        // Supprimer la facture liée
        $stmt = $pdo->prepare("DELETE FROM invoices WHERE id_order = ?");
        $stmt->execute([$order_id]);

        // Supprimer la commande
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);

        $pdo->commit();
        header("Location: manage_orders.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erreur lors de la suppression de la commande.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link rel="stylesheet" href="../public/admin.css">
</head>
<body>

    <section class="admin-dashboard">
        <h2>📦 Gestion des Commandes</h2>

        <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Détails</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['nom']) ?> <?= htmlspecialchars($order['prenom']) ?></td>
                            <td><?= $order['date_commande'] ?></td>
                            <td><?= number_format($order['total_prix'], 2) ?> €</td>
                            <td>
                                <ul>
                                    <?php foreach ($orderDetails[$order['id']] as $item): ?>
                                        <li><?= htmlspecialchars($item['titre']) ?> (x<?= $item['quantite'] ?>) - <?= number_format($item['prix_unitaire'], 2) ?> €</li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <form method="POST">
                                    <button type="submit" name="delete_order" value="<?= $order['id'] ?>" class="btn delete-btn">❌ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

    </section>

</body>
</html>

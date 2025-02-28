<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Middleware.php';
include __DIR__ . '/../views/partials/navbar.php';

Middleware::requireAdmin();
redirectIfNotAdmin();

// Récupération des statistiques de base
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?? 0;
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;
$total_books = $pdo->query("SELECT SUM(stock) FROM books")->fetchColumn() ?? 0;
$total_revenue = $pdo->query("SELECT SUM(total_prix) FROM orders")->fetchColumn() ?? 0;

// Commandes par mois (12 derniers mois)
$orders_by_month = $pdo->query("
    SELECT DATE_FORMAT(date_commande, '%Y-%m') AS mois, COUNT(*) AS nombre
    FROM orders
    WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY mois
    ORDER BY mois
")->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Revenus par mois
$revenues_by_month = $pdo->query("
    SELECT DATE_FORMAT(date_commande, '%Y-%m') AS mois, SUM(total_prix) AS total
    FROM orders
    WHERE date_commande >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY mois
    ORDER BY mois
")->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Répartition des rôles utilisateurs
$roles_count = $pdo->query("
    SELECT role, COUNT(*) AS total
    FROM users
    GROUP BY role
")->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Vérification et mise à jour des données JSON pour éviter les erreurs
$orders_labels = json_encode(array_column($orders_by_month, 'mois') ?: ['Aucune donnée']);
$orders_data = json_encode(array_column($orders_by_month, 'nombre') ?: [0]);

$revenues_labels = json_encode(array_column($revenues_by_month, 'mois') ?: ['Aucune donnée']);
$revenues_data = json_encode(array_column($revenues_by_month, 'total') ?: [0]);

$roles_labels = json_encode(array_column($roles_count, 'role') ?: ['Aucun rôle']);
$roles_data = json_encode(array_column($roles_count, 'total') ?: [0]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="../public/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <section class="admin-dashboard">
        <h2>📊 Tableau de Bord Administrateur</h2>

        <div class="stats-container">
            <div class="stat-card">
                <h3>📦 Commandes</h3>
                <p><?= $total_orders ?></p>
            </div>
            <div class="stat-card">
                <h3>👥 Utilisateurs</h3>
                <p><?= $total_users ?></p>
            </div>
            <div class="stat-card">
                <h3>📚 Livres en stock</h3>
                <p><?= $total_books ?></p>
            </div>
            <div class="stat-card">
                <h3>💰 Revenus Totaux</h3>
                <p><?= number_format($total_revenue, 2) ?> €</p>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box">
                <p>📈 Nombre de commandes par mois</p>
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="chart-box">
                <p>💶 Revenus mensuels</p>
                <canvas id="revenuesChart"></canvas>
            </div>
            <div class="chart-box">
                <p>📊 Répartition des utilisateurs</p>
                <canvas id="rolesChart" width="300" height="300" style="height: 350px; width: 350px; display: block; box-sizing: border-box;"></canvas>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Graphique des commandes par mois
            new Chart(document.getElementById('ordersChart'), {
                type: 'line',
                data: {
                    labels: <?= $orders_labels ?>,
                    datasets: [{
                        label: 'Commandes par mois',
                        data: <?= $orders_data ?>,
                        borderColor: '#007BFF',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        fill: true
                    }]
                }
            });

            // Graphique des revenus par mois
            new Chart(document.getElementById('revenuesChart'), {
                type: 'bar',
                data: {
                    labels: <?= $revenues_labels ?>,
                    datasets: [{
                        label: 'Revenus (€)',
                        data: <?= $revenues_data ?>,
                        backgroundColor: '#28a745'
                    }]
                }
            });

            // Graphique de la répartition des rôles utilisateurs
            new Chart(document.getElementById('rolesChart'), {
                type: 'pie',
                data: {
                    labels: <?= $roles_labels ?>,
                    datasets: [{
                        label: 'Répartition des utilisateurs',
                        data: <?= $roles_data ?>,
                        backgroundColor: ['#007BFF', '#FF5733', '#28a745', '#ffc107']
                    }]
                }
            });
        });
    </script>

</body>
</html>

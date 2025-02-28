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
$success = false;

// Vérification et validation de la commande si le panier contient des articles
if (!empty($_SESSION['cart']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse = trim($_POST['adresse']);
    $ville = trim($_POST['ville']);
    $code_postal = trim($_POST['code_postal']);
    $errors = [];

    if (empty($adresse) || empty($ville) || empty($code_postal)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Récupérer les livres du panier
            $cart_books = [];
            $total_price = 0;
            $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
            $stmt->execute(array_keys($_SESSION['cart']));
            $cart_books = $stmt->fetchAll();

            foreach ($cart_books as $book) {
                $quantity = $_SESSION['cart'][$book['id']];
                $total_price += $book['prix'] * $quantity;
            }

            // Insérer la commande
            $stmt = $pdo->prepare("INSERT INTO orders (id_user, date_commande, total_prix) VALUES (?, NOW(), ?)");
            $stmt->execute([$user_id, $total_price]);
            $order_id = $pdo->lastInsertId();

            // Insérer les détails de la commande
            $stmt = $pdo->prepare("INSERT INTO order_items (id_order, id_book, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            foreach ($cart_books as $book) {
                $stmt->execute([$order_id, $book['id'], $_SESSION['cart'][$book['id']], $book['prix']]);
            }

            // Insérer la facture
            $stmt = $pdo->prepare("INSERT INTO invoices (id_order, date_facture, montant_total, adresse, ville, code_postal) VALUES (?, NOW(), ?, ?, ?, ?)");
            $stmt->execute([$order_id, $total_price, $adresse, $ville, $code_postal]);

            // Vider le panier après commande
            $_SESSION['cart'] = [];
            $pdo->commit();
            $success = true;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Erreur lors du traitement de la commande.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valider ma commande</title>
    <link rel="stylesheet" href="../public/orders.css">
</head>
<body>

    <section class="checkout">
        <h2>🛒 Valider ma commande</h2>

        <?php if ($success): ?>
            <p class="success">
                ✅ Commande validée avec succès ! Vous allez être redirigé vers la page d'accueil dans 
                <span id="timer">5</span> secondes.
            </p>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p>❌ <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h3>📍 Adresse de livraison</h3>
            <form method="POST" class="order-form">
                <div class="input-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" required>
                </div>

                <div class="input-group">
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" required>
                </div>

                <div class="input-group">
                    <label for="code_postal">Code Postal</label>
                    <input type="text" name="code_postal" required>
                </div>

                <button type="submit" class="btn">✅ Confirmer la commande</button>
            </form>
        <?php endif; ?>
    </section>

    <?php if ($success): ?>
    <script>
        let countdown = 5; // Temps en secondes
        let timerElement = document.getElementById('timer');

        function updateTimer() {
            if (countdown > 0) {
                countdown--;
                timerElement.textContent = countdown;
            } else {
                window.location.href = "home.php"; // Redirection
            }
        }

        setInterval(updateTimer, 1000); // Décrémente chaque seconde
    </script>
    <?php endif; ?>

</body>
</html>

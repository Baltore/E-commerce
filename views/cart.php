<?php
session_start();
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../core/Middleware.php';

Middleware::requireAuth();

// Initialisation du panier si non défini
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$errors = []; 

// Gestion des actions (mise à jour, suppression)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        foreach ($_POST['quantities'] as $book_id => $quantity) {
            $stmt = $pdo->prepare("SELECT stock FROM books WHERE id = ?");
            $stmt->execute([$book_id]);
            $stock = $stmt->fetchColumn();

            if ($quantity > $stock) {
                $errors[$book_id] = "⚠️ Stock insuffisant ! Maximum : $stock exemplaire(s).";
            } elseif ($quantity > 0) {
                $_SESSION['cart'][$book_id] = (int) $quantity;
            } else {
                unset($_SESSION['cart'][$book_id]); // Supprimer l'article si quantité 0
            }
        }
    }

    if (isset($_POST['remove'])) {
        $book_id = $_POST['remove'];
        unset($_SESSION['cart'][$book_id]); // Supprimer l'article sélectionné
    }

    if (isset($_POST['clear'])) {
        $_SESSION['cart'] = []; // Vider entièrement le panier
    }
}

// Récupération des livres du panier depuis la BDD
$cart_books = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $cart_books = $stmt->fetchAll();

    // Calcul du total
    foreach ($cart_books as $book) {
        $quantity = $_SESSION['cart'][$book['id']];
        $total_price += $book['prix'] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - E-Livres</title>
    <link rel="stylesheet" href="../public/cart.css">
</head>
<body>

    <section class="cart">
        <h2>🛒 Votre Panier</h2>

        <?php if (empty($cart_books)): ?>
            <p class="empty-cart">Votre panier est vide.</p>
            <a href="catalog.php" class="btn">← Retour au catalogue</a>
        <?php else: ?>
            <form method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Livre</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_books as $book): ?>
                            <tr>
                                <td><img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>" class="cart-img"></td>
                                <td><?= htmlspecialchars($book['titre']) ?></td>
                                <td><?= number_format($book['prix'], 2) ?> €</td>
                                <td>
                                    <input type="number" 
                                           name="quantities[<?= $book['id'] ?>]" 
                                           value="<?= $_SESSION['cart'][$book['id']] ?>" 
                                           min="1" 
                                           max="<?= $book['stock'] ?>">
                                    <?php if (isset($errors[$book['id']])): ?>
                                        <p class="error"><?= $errors[$book['id']] ?></p>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($book['prix'] * $_SESSION['cart'][$book['id']], 2) ?> €</td>
                                <td>
                                    <button type="submit" name="remove" value="<?= $book['id'] ?>" class="btn remove-btn">❌ Supprimer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="total">Total : <strong><?= number_format($total_price, 2) ?> €</strong></p>

                <div class="cart-buttons">
                    <button type="submit" name="update" class="update-btn">
                        🔄 Mettre à jour
                    </button>

                    <button type="submit" name="clear" class="empty-btn">
                        🗑️ Vider le panier
                    </button>

                    <a href="orders" class="checkout-btn">
                        📦 Commander
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </section>

</body>
</html>

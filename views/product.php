<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php'; // Ajoute la session ici
include __DIR__ . '/partials/header.php';

// Vérifier si un ID de livre est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h2 style='color: red; text-align: center;'>❌ Livre introuvable</h2>");
}

// Récupérer les informations du livre
$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

// Vérifier si le livre existe
if (!$book) {
    die("<h2 style='color: red; text-align: center;'>❌ Livre introuvable</h2>");
}

// Gestion de l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isAuthenticated()) { // Vérifie si l'utilisateur est connecté
        header("Location: login.php"); // Redirige vers la page de connexion
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $book_id = $book['id'];
    if (!isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id] = 1; // Ajouter 1 exemplaire du livre
    } else {
        $_SESSION['cart'][$book_id]++; // Augmenter la quantité si déjà ajouté
    }

    $message = "📚 Livre ajouté au panier !";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['titre']) ?> - E-Livres</title>
    <link rel="stylesheet" href="../public/product.css">
</head>
<body>

    <section class="product">
        <div class="product-image">
            <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>">
        </div>
        <div class="product-details">
            <h1><?= htmlspecialchars($book['titre']) ?></h1>
            <h3>de <?= htmlspecialchars($book['auteur']) ?></h3>
            <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
            <p class="price"><?= number_format($book['prix'], 2) ?> €</p>

            <?php if (isset($message)): ?>
                <p class="success"><?= $message ?></p>
            <?php endif; ?>

            <form method="POST">
                <button type="submit" class="btn">🛒 Ajouter au panier</button>
            </form>

            <a href="catalog.php" class="btn back-btn">← Retour au catalogue</a>
        </div>
    </section>

</body>
</html>

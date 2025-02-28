<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';

// Récupérer les livres mis en avant (ex : 4 derniers livres ajoutés)
$stmt = $pdo->query("SELECT * FROM books ORDER BY date_publication DESC LIMIT 4");
$books = $stmt->fetchAll();

function truncateTitle($title, $length = 30) {
    return (mb_strlen($title, 'UTF-8') > $length) ? mb_substr($title, 0, $length, 'UTF-8') . "..." : $title;
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - E-Livres</title>
    <link rel="stylesheet" href="../public/home.css">
</head>
<body>

    <!-- Bannière -->
    <section class="banner">
        <h1>Bienvenue sur E-Livres 📚</h1>
        <p>Découvrez une collection de livres passionnants et enrichissez votre bibliothèque !</p>
        <a href="catalog.php" class="btn">Voir tous les livres</a>
    </section>

    <!-- Section Livres Mis en Avant -->
    <section class="featured-books">
        <h2>Livres mis en avant 📖</h2>
        <div class="book-container">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>">
                    <h3><?= htmlspecialchars(truncateTitle($book['titre'])) ?></h3>
                    <p>de <?= htmlspecialchars($book['auteur']) ?></p>
                    <p class="price"><?= number_format($book['prix'], 2) ?> €</p>
                    <a href="product.php?id=<?= $book['id'] ?>" class="btn">Voir le livre</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

</body>
</html>

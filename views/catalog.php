<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';

// Gestion des filtres
$search_title = $_GET['search_title'] ?? '';
$search_author = $_GET['search_author'] ?? '';
$sort_price = $_GET['sort_price'] ?? '';

// Construction de la requête SQL avec filtres
$query = "SELECT * FROM books WHERE 1=1";
$params = [];

if (!empty($search_title)) {
    $query .= " AND titre LIKE ?";
    $params[] = "%$search_title%";
}

if (!empty($search_author)) {
    $query .= " AND auteur LIKE ?";
    $params[] = "%$search_author%";
}

if ($sort_price === 'asc') {
    $query .= " ORDER BY prix ASC";
} elseif ($sort_price === 'desc') {
    $query .= " ORDER BY prix DESC";
} else {
    $query .= " ORDER BY date_publication DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - E-Livres</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="../public/catalog.css">
</head>
<body>
    <section class="catalog">
        <h2 class="h2c">📚 Carousel des Livres</h2>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($books as $book): ?>
                    <div class="swiper-slide">
                        <div class="book-card">
                            <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>">
                            <h3><?= htmlspecialchars($book['titre']) ?></h3>
                            <p>de <?= htmlspecialchars($book['auteur']) ?></p>
                            <p class="price"><?= number_format($book['prix'], 2) ?> €</p>
                            <a href="product.php?id=<?= $book['id'] ?>" class="btn">Voir le livre</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Filtres pour les livres -->
    <h2>📖 Tous les livres</h2>
        <section class="filters">
            <form method="GET" class="filter-form">
                <input type="text" name="search_title" placeholder="🔎 Rechercher un livre..." value="<?= htmlspecialchars($search_title) ?>">
                <input type="text" name="search_author" placeholder="✍️ Rechercher un auteur..." value="<?= htmlspecialchars($search_author) ?>">
                <select name="sort_price">
                    <option value="">💰 Trier par prix</option>
                    <option value="asc" <?= $sort_price === 'asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="desc" <?= $sort_price === 'desc' ? 'selected' : '' ?>>Prix décroissant</option>
                </select>
                <button type="submit" class="btn">🔍 Filtrer</button>
            </form>
        </section>

    <!-- Section des livres en grille -->
    <section class="all-books">
        <div class="grid-container">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>">
                    <h3><?= htmlspecialchars($book['titre']) ?></h3>
                    <p>de <?= htmlspecialchars($book['auteur']) ?></p>
                    <p class="price"><?= number_format($book['prix'], 2) ?> €</p>
                    <a href="product.php?id=<?= $book['id'] ?>" class="btn">Voir le livre</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
</body>
</html>

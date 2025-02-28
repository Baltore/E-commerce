<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php';
include __DIR__ . '/../views/partials/navbar.php';

// Vérification : Seul un admin peut accéder à cette page
redirectIfNotAdmin();

// Initialisation des variables pour modification
$edit_mode = false;
$edit_book = null;

// 1️⃣ Ajouter un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $prix = (float) $_POST['prix'];
    $stock = (int) $_POST['stock'];

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image_name");
    } else {
        $image_name = "default.jpg";
    }

    $stmt = $pdo->prepare("INSERT INTO books (titre, auteur, description, prix, stock, image, date_publication) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$titre, $auteur, $description, $prix, $stock, $image_name]);
    header("Location: manage_books.php");
    exit();
}

// 2️⃣ Supprimer un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_book'])) {
    $book_id = $_POST['delete_book'];
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    header("Location: manage_books.php");
    exit();
}

// 3️⃣ Récupérer la liste des livres
$stmt = $pdo->query("SELECT * FROM books ORDER BY date_publication DESC");
$books = $stmt->fetchAll();

// 4️⃣ Récupérer un livre pour modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_book'])) {
    $edit_mode = true;
    $book_id = $_POST['edit_book'];
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$book_id]);
    $edit_book = $stmt->fetch();
}

// 5️⃣ Modifier un livre existant
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_book'])) {
    $book_id = $_POST['book_id'];
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $description = trim($_POST['description']);
    $prix = (float) $_POST['prix'];
    $stock = (int) $_POST['stock'];

    // Vérifier si une nouvelle image est téléchargée
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$image_name");
        $stmt = $pdo->prepare("UPDATE books SET titre=?, auteur=?, description=?, prix=?, stock=?, image=? WHERE id=?");
        $stmt->execute([$titre, $auteur, $description, $prix, $stock, $image_name, $book_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE books SET titre=?, auteur=?, description=?, prix=?, stock=? WHERE id=?");
        $stmt->execute([$titre, $auteur, $description, $prix, $stock, $book_id]);
    }

    header("Location: manage_books.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Livres</title>
    <link rel="stylesheet" href="../public/manageb.css">
</head>
<body>
    <section class="admin-books">
        
        <div class="form-container">
            <h2>📚 Gestion des Livres</h2>
            <h3><?= $edit_mode ? "✏️ Modifier un Livre" : "➕ Ajouter un Livre" ?></h3>

            <form method="POST" enctype="multipart/form-data">
                <?php if ($edit_mode): ?>
                    <input type="hidden" name="book_id" value="<?= $edit_book['id'] ?>">
                <?php endif; ?>

                <div class="input-group">
                    <label for="titre">Titre</label>
                    <input type="text" name="titre" required value="<?= $edit_mode ? htmlspecialchars($edit_book['titre']) : "" ?>">
                </div>

                <div class="input-group">
                    <label for="auteur">Auteur</label>
                    <input type="text" name="auteur" required value="<?= $edit_mode ? htmlspecialchars($edit_book['auteur']) : "" ?>">
                </div>

                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea name="description" required><?= $edit_mode ? htmlspecialchars($edit_book['description']) : "" ?></textarea>
                </div>

                <div class="input-group">
                    <label for="prix">Prix (€)</label>
                    <input type="number" name="prix" step="0.01" required value="<?= $edit_mode ? $edit_book['prix'] : "" ?>">
                </div>

                <div class="input-group">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" required value="<?= $edit_mode ? $edit_book['stock'] : "" ?>">
                </div>

                <div class="input-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>

                <button type="submit" name="<?= $edit_mode ? 'update_book' : 'add_book' ?>" class="btn">
                    <?= $edit_mode ? "💾 Sauvegarder" : "📖 Ajouter" ?>
                </button>
            </form>
        </div>

        <!-- Liste des livres -->
        <h3>📋 Liste des Livres</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>#<?= $book['id'] ?></td>
                        <td><img src="../uploads/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['titre']) ?>" class="book-img"></td>
                        <td><?= htmlspecialchars($book['titre']) ?></td>
                        <td><?= htmlspecialchars($book['auteur']) ?></td>
                        <td><?= number_format($book['prix'], 2) ?> €</td>
                        <td><?= $book['stock'] ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <button type="submit" name="edit_book" value="<?= $book['id'] ?>" class="btn edit-btn">✏️ Modifier</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <button type="submit" name="delete_book" value="<?= $book['id'] ?>" class="btn delete-btn">❌ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    </section>

</body>
</html>

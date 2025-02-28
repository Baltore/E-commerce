<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Session.php';
include __DIR__ . '/../views/partials/navbar.php';

// Vérification : Seul un admin peut accéder à cette page
redirectIfNotAdmin();

// 1️⃣ Modifier le rôle d’un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_role'])) {
    $user_id = $_POST['change_role'];
    $new_role = $_POST['new_role'];

    // Vérifier que l'admin ne tente pas de modifier son propre rôle
    if ($user_id == $_SESSION['user_id']) {
        $error = "Vous ne pouvez pas modifier votre propre rôle.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$new_role, $user_id]);
        header("Location: manage_users.php");
        exit();
    }
}

// 2️⃣ Supprimer un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['delete_user'];

    // Vérifier que l'admin ne tente pas de se supprimer lui-même
    if ($user_id == $_SESSION['user_id']) {
        $error = "Vous ne pouvez pas vous supprimer vous-même.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        header("Location: manage_users.php");
        exit();
    }
}

// 3️⃣ Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, nom, prenom, email, role FROM users ORDER BY nom");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../public/manageu.css">
</head>
<body>

    <section class="admin-users">
        <h2>👥 Gestion des Utilisateurs</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?> <?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <form method="POST" style="display:inline;">
                                    <select name="new_role">
                                        <option value="utilisateur" <?= $user['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="change_role" value="<?= $user['id'] ?>" class="btn">🔄 Modifier</button>
                                </form>
                            <?php else: ?>
                                <strong><?= ucfirst($user['role']) ?></strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <form method="POST" style="display:inline;">
                                    <button type="submit" name="delete_user" value="<?= $user['id'] ?>" class="btn delete-btn">❌ Supprimer</button>
                                </form>
                            <?php else: ?>
                                <em>Non supprimable</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

    </section>

</body>
</html>

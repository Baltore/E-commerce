<?php require_once __DIR__ . '/../../core/Session.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce - Livres</title>
    <link rel="stylesheet" href="../public/header.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="home">📚 E-Livres</a>
            </div>
            <ul class="nav-links">
                <li><a href="home">Accueil</a></li>
                <li><a href="catalog">📚 Livres</a></li>

                <?php if (isAuthenticated()): ?>
                    <li><a href="cart">🛒 Panier</a></li>
                    <li><a href="profil">👤 Profil</a></li>
                    
                    <?php if (isAdmin()): ?>
                        <li><a href="admin">⚙️ Admin</a></li>
                    <?php endif; ?>

                    <li><a href="logout" class="logout-btn">🚪 Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login">🔑 Connexion</a></li>
                    <li><a href="register" class="register-btn">📝 Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>

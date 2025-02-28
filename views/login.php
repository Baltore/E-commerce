<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../core/Validator.php';


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); 

    if (!Validator::validateRequired([$email, $password])) {
        $errors[] = "Tous les champs sont obligatoires.";
    }
    
    if (!Validator::validateEmail($email)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($errors)) {
        // Vérifier si l'utilisateur existe
        $stmt = $pdo->prepare("SELECT id, nom, prenom, mot_de_passe, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Création de la session utilisateur
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_role'] = $user['role'];

            if ($remember) {
                $token = bin2hex(random_bytes(32)); // Génère un token unique sécurisé
                setcookie('remember_me', $token, time() + (30 * 24 * 60 * 60), "/", "", true, true); // Cookie sécurisé HTTPOnly

                // Stocker le token dans la base de données
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->execute([$token, $user['id']]);
            }

            // Redirection en fonction du rôle
            header("Location: " . ($user['role'] === 'admin' ? '../admin/index.php' : 'home.php'));
            exit();
        } else {
            $errors[] = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../public/register.css">
</head>
<body>
    <div class="container">
        <form method="POST" class="form">
            <h2>Connexion</h2>

            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p>❌ <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div class="checkbox">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit">Se connecter</button>
            <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
        </form>
    </div>
</body>
</html>

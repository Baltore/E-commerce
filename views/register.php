<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/partials/header.php';
require_once __DIR__ . '/../core/Validator.php';


$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!Validator::validateRequired([$nom, $prenom, $email, $password, $password_confirm])) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (!Validator::validateEmail($email)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (!Validator::validatePassword($password)) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }


    if (empty($errors)) {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Cet email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insérer l'utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $email, $hashed_password])) {
                $success = true;
            } else {
                $errors[] = "Une erreur est survenue, veuillez réessayer.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../public/register.css">
</head>
<body>
    <div class="container">
        <form method="POST" class="form">
            <h2>Créer un compte</h2>
            
            <?php if ($success): ?>
                <p class="success">✅ Inscription réussie ! <a href="login.php">Connectez-vous ici</a>.</p>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $error): ?>
                        <p>❌ <?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" required>
            </div>

            <div class="input-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" name="password_confirm" required>
            </div>

            <button type="submit">S'inscrire</button>
            <p>Déjà un compte ? <a href="login.php">Se connecter</a></p>
        </form>
    </div>
</body>
</html>

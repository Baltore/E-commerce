<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isAuthenticated()) {
        header("Location: " . Router::base_url('login'));
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: " . Router::base_url('home'));
        exit();
    }
}

// 🔹 Vérification automatique du cookie "Se souvenir de moi"
if (!isAuthenticated() && isset($_COOKIE['remember_me'])) {
    $stmt = $pdo->prepare("SELECT id, nom, prenom, role FROM users WHERE remember_token = ?");
    $stmt->execute([$_COOKIE['remember_me']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_role'] = $user['role'];
    }
}
?>

<?php
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Session.php';

class Middleware {
    // Vérifie si l'utilisateur est connecté
    public static function requireAuth() {
        if (!isAuthenticated()) {
            header("Location: " . Router::base_url('login'));
            exit();
        }
    }

    // Vérifie si l'utilisateur est admin
    public static function requireAdmin() {
        if (!isAdmin()) {
            header("Location: " . Router::base_url('home'));
            exit();
        }
    }
}
?>

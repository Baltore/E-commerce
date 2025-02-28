<?php
class Router {
    private $routes = [
        'home' => 'home.php',
        'catalog' => 'catalog.php',
        'cart' => 'cart.php',
        'login' => 'login.php',
        'logout' => 'logout.php',
        'orders' => 'orders.php',
        'product' => 'product.php',
        'register' => 'register.php',
        'admin' => 'admin/index.php',
        'dashboard' => 'admin/dashboard.php',
        'manage-books' => 'admin/manage_books.php',
        'manage-orders' => 'admin/manage_orders.php',
        'manage-users' => 'admin/manage_users.php'
    ];

    public function route() {
        $request = trim($_GET['url'] ?? '', '/');

        if (array_key_exists($request, $this->routes)) {
            require_once __DIR__ . '/../views/' . $this->routes[$request];
        } else {
            require_once __DIR__ . '/../views/404.php'; // Page d'erreur 404
        }
    }

    public static function base_url($path = '') {
        return "/E-commerce/views/" . ltrim($path, '/');
    }
}
?>

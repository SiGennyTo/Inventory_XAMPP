<?php
// Auth - static session helpers. Include this at the top of any page/endpoint
// that needs a logged-in user.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function role() {
        return $_SESSION['role'] ?? null;
    }

    public static function isAdmin() {
        return self::role() === 'admin';
    }

    public static function login($user) {
        $_SESSION['user_id']  = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
    }

    public static function logout() {
        $_SESSION = [];
        session_destroy();
    }

    // For HTML pages (index.php, admin.php, manage_users.php): redirect if not allowed.
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header("Location: login.php");
            exit;
        }
    }

    public static function requireRole($role) {
        self::requireLogin();
        if (self::role() !== $role) {
            header("Location: index.php");
            exit;
        }
    }

    // For AJAX endpoints (api/*.php): return JSON + stop, instead of redirecting.
    public static function requireLoginApi() {
        if (!self::isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Not logged in."]);
            exit;
        }
    }

    public static function requireRoleApi($role) {
        self::requireLoginApi();
        if (self::role() !== $role) {
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "You don't have permission to do that."]);
            exit;
        }
    }
}
?>

<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/User.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Use POST."]);
    exit;
}

$username = trim($_POST["username"] ?? '');
$password = trim($_POST["password"] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Username and password are required."]);
    exit;
}

$userModel = new User($conn);
$user = $userModel->login($username, $password);

if ($user) {
    Auth::login($user);
    echo json_encode(["status" => "success", "message" => "Login successful.", "role" => $user['role']]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid username or password."]);
}

$conn->close();
?>

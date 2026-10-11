<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/User.php");

Auth::requireRoleApi('admin');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Use POST."]);
    exit;
}

$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';

$userModel = new User($conn);
$result = $userModel->createEmployee($username, $password);

echo json_encode($result);

$conn->close();
?>

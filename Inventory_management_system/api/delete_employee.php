<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/User.php");

Auth::requireRoleApi('admin');

$userModel = new User($conn);
$result = $userModel->deleteEmployee($_POST['id'] ?? 0);

echo json_encode($result);

$conn->close();
?>

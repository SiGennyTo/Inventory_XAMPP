<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/User.php");

Auth::requireRoleApi('admin');

$userModel = new User($conn);
echo json_encode($userModel->getAllEmployees());

$conn->close();
?>

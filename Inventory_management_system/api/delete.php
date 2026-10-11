<?php
// Turn off error display
error_reporting(0);
ini_set('display_errors', 0);

// Always return JSON
header('Content-Type: application/json');

// Include database + Product class
include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/Product.php");

// Only admins can delete products
Auth::requireRoleApi('admin');

$product = new Product($conn);
$result = $product->delete($_POST['id'] ?? 0);

echo json_encode($result);

$conn->close();
?>

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

// Only admins can update products
Auth::requireRoleApi('admin');

// Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method. Use POST."
    ]);
    exit;
}

$product = new Product($conn);
$result = $product->update($_POST);

echo json_encode($result);

$conn->close();
?>

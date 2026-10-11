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

// Any logged-in user (admin or employee) can view inventory records
Auth::requireLoginApi();

$product = new Product($conn);
echo json_encode($product->getAll());

$conn->close();
?>

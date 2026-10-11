<?php
// Turn off error display
error_reporting(0);
ini_set('display_errors', 0);

// Always return JSON
header('Content-Type: application/json');

// Include database + classes
include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/Product.php");
include_once(dirname(__DIR__) . "/classes/Supplier.php");

// Only admins can view the admin dashboard
Auth::requireRoleApi('admin');

$product = new Product($conn);
$supplier = new Supplier($conn);

// Total number of categories and suppliers in the system
$categoryTotal = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()["total"];
$supplierTotal = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()["total"];

echo json_encode([
    "status" => "success",
    "summary" => $product->getSummary(),
    "totalCategories" => $categoryTotal,
    "totalSuppliers" => $supplierTotal,
    "categoryBreakdown" => $product->getCategoryBreakdown(),
    "supplierStats" => $supplier->getAllWithStats(),
    "restockNeeded" => $product->getRestockNeeded()
]);

$conn->close();
?>

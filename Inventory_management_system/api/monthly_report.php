<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include_once(dirname(__DIR__) . "/config/db.php");
include_once(dirname(__DIR__) . "/config/auth.php");
include_once(dirname(__DIR__) . "/classes/Product.php");

// Any logged-in user (admin or employee) can view the monthly report
Auth::requireLoginApi();

// Feature: Monthly Supply Report
// input "YYYY-MM"
$monthInput = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$parts = explode('-', $monthInput);
$year = isset($parts[0]) ? intval($parts[0]) : intval(date('Y'));
$month = isset($parts[1]) ? intval($parts[1]) : intval(date('m'));

if ($month < 1 || $month > 12) {
    $month = intval(date('m'));
}

$product = new Product($conn);

echo json_encode([
    "status" => "success",
    "month" => $month,
    "year" => $year,
    "summary" => $product->getSummary(),
    "newProducts" => $product->getNewProducts($month, $year),
    "restockNeeded" => $product->getRestockNeeded()
]);

$conn->close();
?>

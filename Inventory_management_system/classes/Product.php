<?php
// Product class - handles all CRUD operations and report queries for products
class Product {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // READ - get all products with category & supplier info
    public function getAll() {
        $sql = "SELECT
                    p.product_id, p.product_name, p.category_id, c.category_name,
                    p.supplier_id, s.supplier_name, p.stock_qty, p.price,
                    p.created_at, p.low_stock_threshold
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                ORDER BY
                    CASE WHEN p.stock_qty <= p.low_stock_threshold THEN 1 ELSE 2 END,
                    p.product_id DESC";

        $result = $this->conn->query($sql);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // CREATE
    public function create($data) {
        $productName = trim($data["productName"] ?? '');
        $categoryName = trim($data["categoryName"] ?? '');
        $supplierName = trim($data["supplierName"] ?? '');
        $stockQty = intval($data["stockQty"] ?? 0);
        $price = floatval($data["price"] ?? 0);
        $lowStockThreshold = intval($data["lowStockThreshold"] ?? 10);

        if (empty($productName) || empty($categoryName) || empty($supplierName) || $stockQty < 0 || $price <= 0) {
            return ["status" => "error", "message" => "All fields are required and must be valid."];
        }

        $categoryId = $this->findOrCreateCategory($categoryName);
        $supplierId = $this->findOrCreateSupplier($supplierName);

        $stmt = $this->conn->prepare(
            "INSERT INTO products (product_name, category_id, supplier_id, stock_qty, price, low_stock_threshold)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("siiidi", $productName, $categoryId, $supplierId, $stockQty, $price, $lowStockThreshold);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Product added successfully."];
        }
        return ["status" => "error", "message" => "Database Error: " . $this->conn->error];
    }

    // UPDATE
    public function update($data) {
        $id = intval($data["id"] ?? 0);
        $productName = trim($data["productName"] ?? '');
        $categoryName = trim($data["categoryName"] ?? '');
        $supplierName = trim($data["supplierName"] ?? '');
        $stockQty = intval($data["stockQty"] ?? 0);
        $price = floatval($data["price"] ?? 0);
        $lowStockThreshold = intval($data["lowStockThreshold"] ?? 10);

        if (empty($id) || empty($productName) || empty($categoryName) || empty($supplierName) || $stockQty < 0 || $price <= 0) {
            return ["status" => "error", "message" => "All fields are required and must be valid."];
        }

        $categoryId = $this->findOrCreateCategory($categoryName);
        $supplierId = $this->findOrCreateSupplier($supplierName);

        $stmt = $this->conn->prepare(
            "UPDATE products
             SET product_name = ?, category_id = ?, supplier_id = ?, stock_qty = ?, price = ?, low_stock_threshold = ?
             WHERE product_id = ?"
        );
        $stmt->bind_param("siiidii", $productName, $categoryId, $supplierId, $stockQty, $price, $lowStockThreshold, $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Product updated successfully."];
        }
        return ["status" => "error", "message" => "Database Error: " . $this->conn->error];
    }

    // DELETE
    public function delete($id) {
        $id = intval($id);
        if (empty($id)) {
            return ["status" => "error", "message" => "Product ID is required."];
        }

        $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Product deleted successfully."];
        }
        return ["status" => "error", "message" => "Database Error: " . $this->conn->error];
    }

    // Find a category by name, creating it first if it doesn't exist yet
    private function findOrCreateCategory($categoryName) {
        $stmt = $this->conn->prepare("SELECT category_id FROM categories WHERE TRIM(LOWER(category_name)) = TRIM(LOWER(?)) LIMIT 1");
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return $row["category_id"];
        }

        $insert = $this->conn->prepare("INSERT INTO categories (category_name) VALUES (TRIM(?))");
        $insert->bind_param("s", $categoryName);
        $insert->execute();
        return $this->conn->insert_id;
    }

    // Find a supplier by name, creating it first if it doesn't exist yet
    private function findOrCreateSupplier($supplierName) {
        $stmt = $this->conn->prepare("SELECT supplier_id FROM suppliers WHERE TRIM(LOWER(supplier_name)) = TRIM(LOWER(?)) LIMIT 1");
        $stmt->bind_param("s", $supplierName);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return $row["supplier_id"];
        }

        $insert = $this->conn->prepare("INSERT INTO suppliers (supplier_name) VALUES (TRIM(?))");
        $insert->bind_param("s", $supplierName);
        $insert->execute();
        return $this->conn->insert_id;
    }

    // Overall inventory summary - used by the monthly report and the admin dashboard
    public function getSummary() {
        $sql = "SELECT COUNT(*) AS totalProducts,
                       COALESCE(SUM(stock_qty * price), 0) AS totalValue,
                       SUM(CASE WHEN stock_qty > 0 AND stock_qty <= low_stock_threshold THEN 1 ELSE 0 END) AS lowStockCount,
                       SUM(CASE WHEN stock_qty = 0 THEN 1 ELSE 0 END) AS outOfStockCount
                FROM products";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    // Products added during a given month/year
    public function getNewProducts($month, $year) {
        $stmt = $this->conn->prepare(
            "SELECT p.product_id, p.product_name, c.category_name, s.supplier_name,
                    p.stock_qty, p.price, p.created_at
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.category_id
             LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
             WHERE MONTH(p.created_at) = ? AND YEAR(p.created_at) = ?
             ORDER BY p.created_at DESC"
        );
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Products that currently need restocking
    public function getRestockNeeded() {
        $sql = "SELECT p.product_id, p.product_name, c.category_name, s.supplier_name,
                       p.stock_qty, p.low_stock_threshold,
                       GREATEST((p.low_stock_threshold * 2) - p.stock_qty, 0) AS suggested_reorder
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.category_id
                LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id
                WHERE p.stock_qty <= p.low_stock_threshold
                ORDER BY p.stock_qty ASC";
        $result = $this->conn->query($sql);

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    // Product counts/value grouped per category - used by the admin dashboard
    public function getCategoryBreakdown() {
        $sql = "SELECT c.category_name,
                       COUNT(p.product_id) AS productCount,
                       COALESCE(SUM(p.stock_qty), 0) AS totalStock,
                       COALESCE(SUM(p.stock_qty * p.price), 0) AS totalValue
                FROM categories c
                LEFT JOIN products p ON p.category_id = c.category_id
                GROUP BY c.category_id, c.category_name
                ORDER BY c.category_name ASC";
        $result = $this->conn->query($sql);

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}
?>

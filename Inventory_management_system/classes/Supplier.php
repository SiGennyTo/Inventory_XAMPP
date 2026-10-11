<?php
// Supplier class - handles supplier monitoring (Feature: monitor suppliers)
class Supplier {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // List of suppliers
    public function getAllWithStats() {
        $sql = "SELECT s.supplier_id, s.supplier_name,
                       COUNT(p.product_id) AS productCount,
                       COALESCE(SUM(p.stock_qty), 0) AS totalStock,
                       COALESCE(SUM(p.stock_qty * p.price), 0) AS totalValue,
                       SUM(CASE WHEN p.stock_qty <= p.low_stock_threshold THEN 1 ELSE 0 END) AS lowStockCount
                FROM suppliers s
                LEFT JOIN products p ON p.supplier_id = s.supplier_id
                GROUP BY s.supplier_id, s.supplier_name
                ORDER BY s.supplier_name ASC";
        $result = $this->conn->query($sql);

        $suppliers = [];
        while ($row = $result->fetch_assoc()) {
            $suppliers[] = $row;
        }
        return $suppliers;
    }
}
?>

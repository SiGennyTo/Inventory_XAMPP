<?php
include_once("config/auth.php");
Auth::requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Inventory Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@500;600;700&display=swap" rel="stylesheet">

    <style> @import url('style/admin.css'); </style>
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row">
        <div class="col">
            <div class="app-panel shadow-sm">

                <!-- Header -->
                <div class="card app-header">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h2 class="fw-bold mb-1">Admin Dashboard</h2>
                                <small>Monitoring overview for the whole system</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="manage_users.php" class="btn btn-outline-light">
                                    Manage Employees
                                </a>
                                <a href="index.php" class="btn btn-outline-light">
                                    Back to Inventory
                                </a>
                                <a href="logout.php" class="btn btn-outline-light">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <!-- Overview stats -->
                        <div class="row g-3 mb-4" id="overviewCards">
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Products</small>
                                    <h3 id="statTotalProducts">0</h3>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Categories</small>
                                    <h3 id="statTotalCategories">0</h3>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Suppliers</small>
                                    <h3 id="statTotalSuppliers">0</h3>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Inventory Value</small>
                                    <h3 id="statTotalValue">₱0</h3>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Low Stock</small>
                                    <h3 id="statLowStock" style="color:#D97706;">0</h3>
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <div class="stat-card">
                                    <small class="text-muted">Out of Stock</small>
                                    <h3 id="statOutOfStock" style="color:#DC3545;">0</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Category breakdown -->
                        <h5 class="mb-2 section-title">Products per Category</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Category</th>
                                        <th>Product Count</th>
                                        <th>Total Stock</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTable">
                                    <tr><td colspan="4" class="text-center text-muted py-3">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Supplier monitoring -->
                        <h5 class="mb-2 section-title">Supplier Monitoring</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-sm table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Products Supplied</th>
                                        <th>Total Stock</th>
                                        <th>Total Value</th>
                                        <th>Low Stock Items</th>
                                    </tr>
                                </thead>
                                <tbody id="supplierTable">
                                    <tr><td colspan="5" class="text-center text-muted py-3">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Restock alerts -->
                        <h5 class="mb-2 section-title">Needs Restocking</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Supplier</th>
                                        <th>Current Qty</th>
                                        <th>Suggested Reorder</th>
                                    </tr>
                                </thead>
                                <tbody id="restockTable">
                                    <tr><td colspan="5" class="text-center text-muted py-3">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="admin.js"></script>
</body>
</html>

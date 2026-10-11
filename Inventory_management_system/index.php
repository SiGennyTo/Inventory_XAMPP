<?php
include_once("config/auth.php");
Auth::requireLogin();
$isAdmin = Auth::isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@500;600;700&display=swap" rel="stylesheet">

    <style> @import url('style/index.css'); </style>
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
                                <h2 class="fw-bold mb-1">Inventory Management System</h2>
                                <small>Signed in as <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</small>
                            </div>
                            <div class="d-flex gap-2">
                                <?php if ($isAdmin): ?>
                                <button class="btn btn-light" id="addProductBtn" data-bs-toggle="modal" data-bs-target="#productModal">
                                    Add Product
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-outline-light" id="monthlyReportBtn" data-bs-toggle="modal" data-bs-target="#monthlyReportModal">
                                    Monthly Report
                                </button>
                                <?php if ($isAdmin): ?>
                                <a href="admin.php" class="btn btn-outline-light">
                                    Admin Dashboard
                                </a>
                                <a href="manage_users.php" class="btn btn-outline-light">
                                    Manage Employees
                                </a>
                                <?php endif; ?>
                                <a href="logout.php" class="btn btn-outline-light">
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Table -->
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            Inventory Records
                        </h5>
                        <div>
                            <span class="badge bg-secondary" id="totalProducts">
                                Total: 0
                            </span>
                            <span class="badge badge-status-low ms-2" id="lowStockTotal">
                                Low Stock: 0
                            </span>
                            <span class="badge badge-status-out ms-2" id="outOfStockTotal">
                                Out of Stock: 0
                            </span>
                        </div>
                    </div>
                    <div class="table-flush-wrapper">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle table-flush">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th>ID</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Supplier</th>
                                        <th>Stock Qty</th>
                                        <th>Threshold</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <?php if ($isAdmin): ?><th width="160">Action</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody id="productTable">
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            Loading products...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<?php if ($isAdmin): ?>
<!-- Add / Edit Product -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="productID">

                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" class="form-control" id="categoryName" placeholder="Enter category name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" class="form-control" id="supplierName" placeholder="Enter supplier name">
                </div>

                <div class="mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" id="stockQty" placeholder="Enter stock quantity">
                </div>

                <div class="mb-3">
                    <label class="form-label">Low Stock Threshold</label>
                    <input type="number" class="form-control" id="lowStockThreshold" placeholder="Alert when below this number" value="10">
                    <small class="text-muted">Product will show a warning when stock drops below this number.</small>
                </div>

                <div class="mb-2">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" placeholder="Enter price">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancelModalBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Product</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Monthly Report -->
<div class="modal fade" id="monthlyReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Monthly Supply Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <label class="form-label mb-0">Month:</label>
                    <select class="form-select" id="reportMonthSelect" style="max-width: 150px;">
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                    <select class="form-select" id="reportYearSelect" style="max-width: 110px;"></select>
                    <button class="btn btn-primary btn-sm" id="generateReportBtn">
                        Generate
                    </button>
                </div>
                <div id="reportContent">
                    <p class="text-muted text-center py-3">Select a month and click Generate.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Passed from PHP session so script.js knows whether to render Edit/Delete controls
    window.isAdmin = <?php echo $isAdmin ? 'true' : 'false'; ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>

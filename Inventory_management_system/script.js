// Global variables
let productName, categoryName, supplierName, stockQty, price, productID;
let lowStockThreshold;
let saveBtn, addProductBtn;
let productModal, productModalTitle;
let currentProducts = []; // cache of the last loaded products, used by Edit
const isAdmin = window.isAdmin === true; // set inline by index.php from the PHP session

document.addEventListener("DOMContentLoaded", function() {

    // Admin-only form elements - only present in the DOM when isAdmin is true
    productName = document.getElementById("productName");
    categoryName = document.getElementById("categoryName");
    supplierName = document.getElementById("supplierName");
    stockQty = document.getElementById("stockQty");
    price = document.getElementById("price");
    productID = document.getElementById("productID");
    lowStockThreshold = document.getElementById("lowStockThreshold");
    saveBtn = document.getElementById("saveBtn");
    addProductBtn = document.getElementById("addProductBtn");
    productModalTitle = document.getElementById("productModalTitle");

    if (isAdmin) {
        // Bts modal instances
        productModal = new bootstrap.Modal(document.getElementById("productModal"));

        //Add Product
        addProductBtn.addEventListener("click", function() {
            clearForm();
            productModalTitle.textContent = "Add Product";
            saveBtn.textContent = "Save Product";
        });

        // Save button add || Update
        saveBtn.addEventListener("click", function() {
            if (productID.value) {
                updateProduct();
            } else {
                saveProduct();
            }
        });

        // (Cancel, X, backdrop click)
        document.getElementById("productModal").addEventListener("hidden.bs.modal", clearForm);

        // Edit / Delete buttons
        document.getElementById("productTable").addEventListener("click", function(e) {
            const editBtn = e.target.closest(".btn-edit");
            const deleteBtn = e.target.closest(".btn-delete");

            if (editBtn) {
                editProductById(editBtn.dataset.id);
            } else if (deleteBtn) {
                deleteProduct(deleteBtn.dataset.id);
            }
        });
    }

    loadProducts();
    initMonthlyReport();
});


// LOAD PRODUCTS
function loadProducts() {
    fetch("api/read.php")
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            displayProducts(data);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("productTable").innerHTML = `
                <tr>
                    <td colspan="9" class="text-center text-danger py-4">
                        Error loading products. Check console for details.
                    </td>
                </tr>`;
        });
}


// DISPLAY PRODUCTS
function displayProducts(data) {
    currentProducts = data || [];

    let table = document.getElementById("productTable");
    table.innerHTML = "";
    const colCount = isAdmin ? 9 : 8; // no Action column for employees

    if (!data || data.length === 0) {
        table.innerHTML = `
        <tr>
            <td colspan="${colCount}" class="text-center text-muted py-4">No product records found.</td>
        </tr>`;
        document.getElementById("totalProducts").innerHTML = "Total: 0";
        document.getElementById("lowStockTotal").innerHTML = "Low Stock: 0";
        document.getElementById("outOfStockTotal").innerHTML = "Out of Stock: 0";
        document.title = "Inventory Management System";
        return;
    }

    let lowStockCount = 0;
    let outOfStockCount = 0;

    data.forEach(product => {
        const qty = parseInt(product.stock_qty) || 0;
        const threshold = parseInt(product.low_stock_threshold) || 10;

        let statusHtml = '';
        let rowClass = '';

        // Out of stock and low stock are separate, mutually exclusive statuses -
        // a product that is out of stock (qty 0) should NOT also be counted as low stock.
        if (qty === 0) {
            rowClass = 'critical-stock-row';
            statusHtml = `<span class="badge badge-status-out">Out of Stock</span>`;
            outOfStockCount++;
        } else if (qty <= threshold) {
            rowClass = 'low-stock-row';
            statusHtml = `<span class="badge badge-status-low">Low Stock (${qty})</span>`;
            lowStockCount++;
        } else {
            statusHtml = `<span class="badge badge-status-ok">In Stock</span>`;
        }

        const actionCell = isAdmin ? `
            <td>
                <button class="btn btn-edit btn-sm" data-id="${product.product_id}">
                    Edit
                </button>
                <button class="btn btn-delete btn-sm" data-id="${product.product_id}">
                    Delete
                </button>
            </td>` : '';

        table.innerHTML += `
        <tr class="${rowClass}">
            <td>${product.product_id}</td>
            <td><strong>${product.product_name || ''}</strong></td>
            <td>${product.category_name || 'N/A'}</td>
            <td>${product.supplier_name || 'N/A'}</td>
            <td class="text-center fw-bold ${qty <= threshold ? 'text-danger' : ''}">${qty}</td>
            <td><span class="badge badge-threshold threshold-badge">Min ${threshold}</span></td>
            <td class="text-center">${statusHtml}</td>
            <td>₱ ${Number(product.price || 0).toLocaleString()}</td>
            ${actionCell}
        </tr>`;
    });

    document.getElementById("totalProducts").innerHTML = "Total: " + data.length;
    document.getElementById("lowStockTotal").innerHTML = `Low Stock: ${lowStockCount}`;
    document.getElementById("outOfStockTotal").innerHTML = `Out of Stock: ${outOfStockCount}`;
    document.title = (lowStockCount + outOfStockCount) > 0
        ? `(${lowStockCount + outOfStockCount}) Low Stock - Inventory System`
        : "Inventory Management System";
}


// SAVE PRODUCT (Add) - admin only, buttons that trigger this don't exist for employees
function saveProduct() {
    const nameVal = productName.value.trim();
    const catVal = categoryName.value.trim();
    const supVal = supplierName.value.trim();
    const qtyVal = stockQty.value.trim();
    const priceVal = price.value.trim();
    const thresholdVal = lowStockThreshold.value.trim();

    if (!nameVal || !catVal || !supVal || !qtyVal || !priceVal) {
        alert("Please fill in all fields.");
        return;
    }

    if (parseInt(qtyVal) <= parseInt(thresholdVal)) {
        if (!confirm(`Warning: This product will have only ${qtyVal} units in stock.\nLow stock threshold: ${thresholdVal}\n\nContinue anyway?`)) {
            return;
        }
    }

    let formData = new FormData();
    formData.append("productName", nameVal);
    formData.append("categoryName", catVal);
    formData.append("supplierName", supVal);
    formData.append("stockQty", qtyVal);
    formData.append("price", priceVal);
    formData.append("lowStockThreshold", thresholdVal);

    fetch("api/create.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                productModal.hide();
                loadProducts();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving product. Check console.');
        });
}


// EDIT last loaded list by its ID
function editProductById(id) {
    const product = currentProducts.find(p => String(p.product_id) === String(id));
    if (!product) {
        alert("Could not find that product. Please refresh and try again.");
        return;
    }

    productID.value = product.product_id;
    productName.value = product.product_name || '';
    categoryName.value = product.category_name || '';
    supplierName.value = product.supplier_name || '';
    stockQty.value = product.stock_qty;
    lowStockThreshold.value = product.low_stock_threshold;
    price.value = product.price;

    productModalTitle.textContent = "Edit Product";
    saveBtn.textContent = "Update Product";

    productModal.show();
}


// UPDATE PRODUCT
function updateProduct() {
    const nameVal = productName.value.trim();
    const catVal = categoryName.value.trim();
    const supVal = supplierName.value.trim();
    const qtyVal = stockQty.value.trim();
    const priceVal = price.value.trim();
    const thresholdVal = lowStockThreshold.value.trim();

    if (!nameVal || !catVal || !supVal || !qtyVal || !priceVal) {
        alert("Please fill in all fields.");
        return;
    }

    if (parseInt(qtyVal) <= parseInt(thresholdVal)) {
        if (!confirm(`Warning: This product will have only ${qtyVal} units in stock.\nLow stock threshold: ${thresholdVal}\n\nContinue anyway?`)) {
            return;
        }
    }

    let formData = new FormData();
    formData.append("id", productID.value);
    formData.append("productName", nameVal);
    formData.append("categoryName", catVal);
    formData.append("supplierName", supVal);
    formData.append("stockQty", qtyVal);
    formData.append("price", priceVal);
    formData.append("lowStockThreshold", thresholdVal);

    fetch("api/update.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                productModal.hide();
                loadProducts();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating product. Check console.');
        });
}


// DELETE PRODUCT
function deleteProduct(id) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    let formData = new FormData();
    formData.append("id", id);

    fetch("api/delete.php", { method: "POST", body: formData })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") loadProducts();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting product. Check console.');
        });
}


function clearForm() {
    productName.value = "";
    categoryName.value = "";
    supplierName.value = "";
    stockQty.value = "";
    price.value = "";
    productID.value = "";
    lowStockThreshold.value = "10";
    productModalTitle.textContent = "Add Product";
    saveBtn.textContent = "Save Product";
}


// MONTHLY REPORT
function initMonthlyReport() {
    const reportMonthSelect = document.getElementById("reportMonthSelect");
    const reportYearSelect = document.getElementById("reportYearSelect");
    const generateReportBtn = document.getElementById("generateReportBtn");

    if (!reportMonthSelect || !reportYearSelect || !generateReportBtn) return;

    const now = new Date();
    const currentYear = now.getFullYear();

    // Build the year dropdown (current year down to 2 years back)
    let yearOptions = "";
    for (let y = currentYear; y >= currentYear - 2; y--) {
        yearOptions += `<option value="${y}">${y}</option>`;
    }
    reportYearSelect.innerHTML = yearOptions;

    // Default to the current month/year
    reportMonthSelect.value = String(now.getMonth() + 1).padStart(2, "0");
    reportYearSelect.value = String(currentYear);

    generateReportBtn.addEventListener("click", function() {
        const selectedMonth = reportYearSelect.value + "-" + reportMonthSelect.value;
        loadMonthlyReport(selectedMonth);
    });
}

function loadMonthlyReport(month) {
    const reportContent = document.getElementById("reportContent");
    reportContent.innerHTML = `<p class="text-muted text-center py-3">Loading report...</p>`;

    fetch("api/monthly_report.php?month=" + encodeURIComponent(month))
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                renderMonthlyReport(data);
            } else {
                reportContent.innerHTML = `<p class="text-danger text-center py-3">${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            reportContent.innerHTML = `<p class="text-danger text-center py-3">Error loading report. Check console.</p>`;
        });
}

function renderMonthlyReport(data) {
    const reportContent = document.getElementById("reportContent");
    const summary = data.summary;

    let html = `
    <div class="row text-center mb-4">
        <div class="col">
            <div class="card bg-light border-0 p-2">
                <small class="text-muted">Total Products</small>
                <h4>${summary.totalProducts}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-light border-0 p-2">
                <small class="text-muted">Inventory Value</small>
                <h4>₱ ${Number(summary.totalValue).toLocaleString()}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-light border-0 p-2">
                <small class="text-muted">Low Stock</small>
                <h4 style="color:#D97706;">${summary.lowStockCount}</h4>
            </div>
        </div>
        <div class="col">
            <div class="card bg-light border-0 p-2">
                <small class="text-muted">Out of Stock</small>
                <h4 style="color:#DC3545;">${summary.outOfStockCount}</h4>
            </div>
        </div>
    </div>

    <h6>New Products This Month</h6>`;

    if (data.newProducts.length === 0) {
        html += `<p class="text-muted small">No products added in this month.</p>`;
    } else {
        html += `<table class="table table-sm table-bordered"><thead><tr>
            <th>Product</th><th>Category</th><th>Supplier</th><th>Qty</th><th>Price</th>
        </tr></thead><tbody>`;
        data.newProducts.forEach(p => {
            html += `<tr>
                <td>${p.product_name}</td>
                <td>${p.category_name || 'N/A'}</td>
                <td>${p.supplier_name || 'N/A'}</td>
                <td>${p.stock_qty}</td>
                <td>₱ ${Number(p.price).toLocaleString()}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    }

    html += `<h6 class="mt-4">Needs Restocking</h6>`;

    if (data.restockNeeded.length === 0) {
        html += `<p class="text-muted small">No items currently need restocking.</p>`;
    } else {
        html += `<table class="table table-sm table-bordered"><thead><tr>
            <th>Product</th><th>Category</th><th>Supplier</th><th>Current Qty</th><th>Suggested Reorder</th>
        </tr></thead><tbody>`;
        data.restockNeeded.forEach(p => {
            html += `<tr style="background-color:${p.stock_qty == 0 ? '#FADBDE' : '#FDECD3'};">
                <td>${p.product_name}</td>
                <td>${p.category_name || 'N/A'}</td>
                <td>${p.supplier_name || 'N/A'}</td>
                <td>${p.stock_qty}</td>
                <td>${p.suggested_reorder}</td>
            </tr>`;
        });
        html += `</tbody></table>`;
    }

    reportContent.innerHTML = html;
}

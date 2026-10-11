// Admin dashboard script
document.addEventListener("DOMContentLoaded", function() {
    loadDashboard();
});

// LOAD DASHBOARD DATA
function loadDashboard() {
    fetch("api/dashboard.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                renderDashboard(data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function renderDashboard(data) {
    const summary = data.summary;

    document.getElementById("statTotalProducts").textContent = summary.totalProducts;
    document.getElementById("statTotalCategories").textContent = data.totalCategories;
    document.getElementById("statTotalSuppliers").textContent = data.totalSuppliers;
    document.getElementById("statTotalValue").textContent = "₱ " + Number(summary.totalValue).toLocaleString();
    document.getElementById("statLowStock").textContent = summary.lowStockCount;
    document.getElementById("statOutOfStock").textContent = summary.outOfStockCount;

    renderCategoryTable(data.categoryBreakdown);
    renderSupplierTable(data.supplierStats);
    renderRestockTable(data.restockNeeded);
}

function renderCategoryTable(categories) {
    const table = document.getElementById("categoryTable");
    table.innerHTML = "";

    if (!categories || categories.length === 0) {
        table.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-3">No categories found.</td></tr>`;
        return;
    }

    categories.forEach(cat => {
        table.innerHTML += `
        <tr>
            <td>${cat.category_name}</td>
            <td>${cat.productCount}</td>
            <td>${cat.totalStock}</td>
            <td>₱ ${Number(cat.totalValue).toLocaleString()}</td>
        </tr>`;
    });
}

function renderSupplierTable(suppliers) {
    const table = document.getElementById("supplierTable");
    table.innerHTML = "";

    if (!suppliers || suppliers.length === 0) {
        table.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-3">No suppliers found.</td></tr>`;
        return;
    }

    suppliers.forEach(sup => {
        const lowStockBadge = sup.lowStockCount > 0
            ? `<span class="badge badge-status-low">${sup.lowStockCount}</span>`
            : `<span class="text-muted">0</span>`;

        table.innerHTML += `
        <tr>
            <td><strong>${sup.supplier_name}</strong></td>
            <td>${sup.productCount}</td>
            <td>${sup.totalStock}</td>
            <td>₱ ${Number(sup.totalValue).toLocaleString()}</td>
            <td class="text-center">${lowStockBadge}</td>
        </tr>`;
    });
}

function renderRestockTable(products) {
    const table = document.getElementById("restockTable");
    table.innerHTML = "";

    if (!products || products.length === 0) {
        table.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">No items currently need restocking.</td></tr>`;
        return;
    }

    products.forEach(p => {
        const rowColor = p.stock_qty == 0 ? '#FADBDE' : '#FDECD3';
        table.innerHTML += `
        <tr style="background-color:${rowColor};">
            <td>${p.product_name}</td>
            <td>${p.category_name || 'N/A'}</td>
            <td>${p.supplier_name || 'N/A'}</td>
            <td>${p.stock_qty}</td>
            <td>${p.suggested_reorder}</td>
        </tr>`;
    });
}

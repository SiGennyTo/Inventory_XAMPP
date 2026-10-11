<?php
include_once("config/auth.php");
Auth::requireRole('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - Inventory Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --navy: #1B2A44; --paper: #F4F4F2; }
        body { background: var(--paper); }
        h2, h5, .modal-title { font-family: 'Source Serif 4', Georgia, serif; }
        .app-header { background: var(--navy); border-radius: 6px; }
        .app-header .card-body { padding-top: 2.25rem; padding-bottom: 2.25rem; }
        .app-panel { border: 1px solid rgba(0,0,0,.125); border-radius: 6px; overflow: hidden; }
        .app-panel .app-header { border-radius: 0; border: none; }
        .app-panel .card { border-radius: 0; border: none; box-shadow: none; }
        .btn-primary { background-color: var(--navy); border-color: var(--navy); }
        .btn-primary:hover, .btn-primary:focus { background-color: #142033; border-color: #142033; }
        .btn-outline-light:hover { color: var(--navy); }
        .table-primary, .table-primary > th, .table-primary > td {
            background-color: var(--navy) !important;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row">
        <div class="col">
            <div class="app-panel shadow-sm">

                <div class="card app-header">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h2 class="fw-bold mb-1">Manage Employees</h2>
                                <small>Create and remove employee accounts</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light" id="addEmployeeBtn" data-bs-toggle="modal" data-bs-target="#employeeModal">
                                    Add Employee
                                </button>
                                <a href="admin.php" class="btn btn-outline-light">Admin Dashboard</a>
                                <a href="index.php" class="btn btn-outline-light">Back to Inventory</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Username</th>
                                        <th>Created</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeTable">
                                    <tr><td colspan="3" class="text-center text-muted py-4">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" id="newUsername" placeholder="Enter username">
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="newPassword" placeholder="At least 6 characters">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEmployeeBtn">Create Account</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const employeeModal = new bootstrap.Modal(document.getElementById("employeeModal"));
    const newUsername = document.getElementById("newUsername");
    const newPassword = document.getElementById("newPassword");

    function loadEmployees() {
        fetch("api/get_employees.php")
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById("employeeTable");
                table.innerHTML = "";

                if (!data || data.length === 0) {
                    table.innerHTML = `<tr><td colspan="3" class="text-center text-muted py-4">No employee accounts yet.</td></tr>`;
                    return;
                }

                data.forEach(emp => {
                    table.innerHTML += `
                    <tr>
                        <td>${emp.username}</td>
                        <td>${emp.created_at}</td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteEmployee(${emp.user_id})">Delete</button>
                        </td>
                    </tr>`;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    document.getElementById("saveEmployeeBtn").addEventListener("click", function() {
        const userVal = newUsername.value.trim();
        const passVal = newPassword.value.trim();

        if (!userVal || !passVal) {
            alert("Please fill in both fields.");
            return;
        }

        let formData = new FormData();
        formData.append("username", userVal);
        formData.append("password", passVal);

        fetch("api/add_employee.php", { method: "POST", body: formData })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    employeeModal.hide();
                    newUsername.value = "";
                    newPassword.value = "";
                    loadEmployees();
                }
            })
            .catch(error => console.error('Error:', error));
    });

    window.deleteEmployee = function(id) {
        if (!confirm("Delete this employee account?")) return;

        let formData = new FormData();
        formData.append("id", id);

        fetch("api/delete_employee.php", { method: "POST", body: formData })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") loadEmployees();
            })
            .catch(error => console.error('Error:', error));
    };

    loadEmployees();
});
</script>
</body>
</html>

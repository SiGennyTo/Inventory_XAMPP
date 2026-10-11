<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@500;600;700&display=swap" rel="stylesheet">

    <style> @import url('style/login.css'); </style>

</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="login-card shadow-sm bg-white">
                <div class="login-header text-white">
                    <h2 class="fw-bold mb-1">Inventory System</h2>
                    <small>Sign in to continue</small>
                </div>
                <div class="p-4">
                    <div id="loginError" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Enter username">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                    </div>

                    <div class="d-grid">
                        <button class="btn btn-primary" id="loginBtn">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const username = document.getElementById("username");
    const password = document.getElementById("password");
    const loginBtn = document.getElementById("loginBtn");
    const loginError = document.getElementById("loginError");

    function attemptLogin() {
        const userVal = username.value.trim();
        const passVal = password.value.trim();

        if (!userVal || !passVal) {
            loginError.textContent = "Please enter both username and password.";
            loginError.classList.remove("d-none");
            return;
        }

        let formData = new FormData();
        formData.append("username", userVal);
        formData.append("password", passVal);

        fetch("api/login.php", { method: "POST", body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    window.location.href = "index.php";
                } else {
                    loginError.textContent = data.message;
                    loginError.classList.remove("d-none");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loginError.textContent = "Login failed. Please try again.";
                loginError.classList.remove("d-none");
            });
    }

    loginBtn.addEventListener("click", attemptLogin);
    password.addEventListener("keypress", function(e) {
        if (e.key === "Enter") attemptLogin();
    });
});</script>

</body>
</html>

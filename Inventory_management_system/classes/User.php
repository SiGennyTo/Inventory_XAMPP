<?php
// User class - handles authentication and employee account management
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Verify username/password. Returns the user row (without the password) on success, or false.
    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }

    // Create a new employee account. Admin accounts are not created through this method.
    public function createEmployee($username, $password) {
        $username = trim($username);

        if (empty($username) || empty($password)) {
            return ["status" => "error", "message" => "Username and password are required."];
        }
        if (strlen($password) < 6) {
            return ["status" => "error", "message" => "Password must be at least 6 characters."];
        }
        if ($this->usernameExists($username)) {
            return ["status" => "error", "message" => "That username is already taken."];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'employee')");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Employee account created successfully."];
        }
        return ["status" => "error", "message" => "Database Error: " . $this->conn->error];
    }

    public function usernameExists($username) {
        $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() !== null;
    }

    // List employee accounts (not admins) for the Manage Employees page
    public function getAllEmployees() {
        $sql = "SELECT user_id, username, created_at FROM users WHERE role = 'employee' ORDER BY created_at DESC";
        $result = $this->conn->query($sql);

        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
        return $employees;
    }

    // Delete an employee account (admins cannot be deleted through this)
    public function deleteEmployee($id) {
        $id = intval($id);
        if (empty($id)) {
            return ["status" => "error", "message" => "User ID is required."];
        }

        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'employee'");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return ["status" => "success", "message" => "Employee account deleted."];
        }
        return ["status" => "error", "message" => "Database Error: " . $this->conn->error];
    }
}
?>

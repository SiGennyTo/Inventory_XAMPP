<?php
// Turn off error display for API responses
error_reporting(0);
ini_set('display_errors', 0);

// Database connection (OOP) - wraps mysqli in a simple class
class Database {
    private $host = "localhost";
    private $port = "3307";
    private $user = "root";
    private $password = "";
    private $database = "inventory_db";

    // Opens and returns a mysqli connection
    public function connect() {
        $conn = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);

        if ($conn->connect_error) {
            echo json_encode([
                "status" => "error",
                "message" => "Database Connection Failed: " . $conn->connect_error
            ]);
            exit;
        }

        $conn->set_charset("utf8");
        return $conn;
    }
}

// Create a ready-to-use connection for the API files to include
$db = new Database();
$conn = $db->connect();
?>

<?php
// Database connection settings
$servername = "localhost"; // Host database
$usernameDB = "root";  // Username database
$passwordDB = "root";  // Password default untuk MAMP
$dbname = "db_soundverse"; // Nama database

// Create the connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
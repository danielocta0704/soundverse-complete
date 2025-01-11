<?php
require 'db_soundverse.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $role = $_POST['role'];
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $username, $email, $password, $role);
        if ($stmt->execute()) {
            echo "<script>alert('User added successfully!'); window.location.href='manage_users.php';</script>";
        } else {
            echo "<script>alert('Failed to add user: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for the form */
        .container {
            background-color: #282828;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #1DB954;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #b3b3b3;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #1e1e1e;
            color: #ffffff;
        }

        input::placeholder {
            color: #555;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            color: #121212;
            background-color: #1DB954;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #14803c;
        }

        .back-button {
            display: block;
            text-align: center;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            background-color: #404040;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add User</h1>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>

            <label for="password">Password:</label>
            <input type="text" id="password" name="password" placeholder="Enter password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Add User</button>
        </form>
        <a href="manage_users.php" class="back-button">Back to Manage Users</a>
    </div>
</body>
</html>
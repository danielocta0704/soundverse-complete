<?php
require 'db_soundverse.php'; // Koneksi ke database

// Ambil data pengguna berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE `user_id` = ?"; // Ganti 'id' menjadi 'user_id' sesuai dengan kolom yang ada
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Update query dengan kondisi jika password diubah
    if (!empty($password)) {
        $sql = "UPDATE users SET username = ?, email = ?, role = ?, password = ? WHERE `user_id` = ?"; // Ganti 'id' menjadi 'user_id'
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $username, $email, $role, $password, $id);  // Menyimpan password sebagai teks biasa
    } else {
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE `user_id` = ?"; // Ganti 'id' menjadi 'user_id'
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $role, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Failed to update user!'); window.location.href='edit_user.php?id=$id';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #1DB954;
            margin-bottom: 30px;
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #282828;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        label {
            display: block;
            font-size: 16px;
            margin: 8px 0;
            color: #b3b3b3;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #1e1e1e;
            color: #ffffff;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #1DB954;
            outline: none;
        }

        .form-actions {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 12px 20px;
            background-color: #1DB954;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #14803c;
        }

        a.cancel-btn {
            padding: 12px 20px;
            background-color: #d32f2f;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        a.cancel-btn:hover {
            background-color: #9a2424;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
                margin: 20px;
            }

            h1 {
                font-size: 24px;
            }

            label,
            input[type="text"],
            input[type="email"],
            input[type="password"],
            select {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit User</h1>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
            </select>

            <label for="password">Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password" placeholder="Enter new password">

            <div class="form-actions">
                <button type="submit" class="submit-btn">Update User</button>
                <a href="manage_users.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
require 'db_soundverse.php'; // Koneksi ke database

// Ambil ID pengguna dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus pengguna berdasarkan ID
    $sql = "DELETE FROM users WHERE `user_id` = ?"; // Ganti 'id user' menjadi 'user_id' sesuai dengan kolom yang ada
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Failed to delete user!'); window.location.href='manage_users.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>

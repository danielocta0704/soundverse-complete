<?php
session_start();
require 'db_soundverse.php'; // Pastikan koneksi database sudah benar


// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Query untuk mencari user berdasarkan username
    $sql = "SELECT user_id, username, role, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah ada user dengan username tersebut
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Cek apakah password cocok
        if ($password === $user['password']) {
            // Set session untuk login
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header('Location: admin_panel.php');
            } else {
                header('Location: home.php');
            }
            exit; // Pastikan tidak ada kode yang dieksekusi setelah redirect
        } else {
            echo "<script>alert('Password mismatch!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found with username: $username'); window.location.href='login.php';</script>";
    }
}
?>
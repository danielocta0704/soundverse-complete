<?php
session_start(); // Memulai sesi

// Hancurkan semua data sesi
session_unset();  // Menghapus semua variabel sesi
session_destroy(); // Menghancurkan sesi

// Redirect ke halaman login setelah logout
header('Location: index.php'); 
exit;
?>
<?php
require 'db_soundverse.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $album_id = $_GET['id'];

    // Query untuk menghapus album
    $sql = "DELETE FROM albums WHERE album_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "<script>alert('Album deleted successfully!'); window.location.href='manage_albums.php';</script>";
        } else {
            echo "<script>alert('Failed to delete album: " . $stmt->error . "'); window.location.href='manage_albums.php';</script>";
        }
    } else {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
<?php
require 'db_soundverse.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

  
    $sql = "DELETE FROM songs WHERE song_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Song deleted successfully!'); window.location.href='manage_songs.php';</script>";
    } else {
        echo "<script>alert('Failed to delete song!'); window.location.href='manage_songs.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
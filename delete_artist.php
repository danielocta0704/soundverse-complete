<?php
require 'db_soundverse.php';
session_start();

if (isset($_GET['id'])) {
    $artist_id = $_GET['id'];

    $sql = "DELETE FROM artists WHERE artist_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Artist deleted successfully!";
        } else {
            $_SESSION['message'] = "Failed to delete artist: " . $stmt->error;
        }
    } else {
        $_SESSION['message'] = "Database error: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: manage_artists.php");
exit;
?>
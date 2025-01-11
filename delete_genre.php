<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Include the database connection file
require 'db_soundverse.php';

// Check if genre ID is passed in the URL
if (isset($_GET['genre_id']) && is_numeric($_GET['genre_id'])) {
    $genre_id = intval($_GET['genre_id']);

    // Prepare and execute the query to delete the genre
    $sql = "DELETE FROM genres WHERE genre_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $genre_id);

    if ($stmt->execute()) {
        // Redirect to genres list page with a success message
        header('Location: genres.php?message=Genre deleted successfully');
        exit;
    } else {
        // Redirect to genres list page with an error message
        header('Location: genres.php?message=Failed to delete genre');
        exit;
    }
} else {
    // Redirect to genres list page if genre ID is missing or invalid
    header('Location: genres.php?message=Invalid genre ID');
    exit;
}

// Close the database connection
$conn->close();
?>
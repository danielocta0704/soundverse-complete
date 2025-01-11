<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
require 'db_soundverse.php';

// Statistics
$usersCount = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$songsCount = $conn->query("SELECT COUNT(*) AS count FROM songs")->fetch_assoc()['count'];
$artistsCount = $conn->query("SELECT COUNT(*) AS count FROM artists")->fetch_assoc()['count'];
$albumsCount = $conn->query("SELECT COUNT(*) AS count FROM albums")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="manage.css">
    <link rel="stylesheet" href="admin-panel.css">
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="manage_songs.php">Manage Songs</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_artists.php">Manage Artists</a>
        <a href="manage_genres.php">Manage Genres</a>
        <a href="manage_albums.php">Manage Albums</a>
        <a href="home.php">Go To Home</a>
        <a href="logout.php">Log out</a>
    </div>

    <div class="content">
        <h1>Welcome to the Admin Panel</h1>
        <p>Here are the current statistics of your platform:</p>
        <div class="statistics-inline">
            <div class="stat-item"><strong>Total Users:</strong> <?php echo $usersCount; ?></div>
            <div class="stat-item"><strong>Total Songs:</strong> <?php echo $songsCount; ?></div>
            <div class="stat-item"><strong>Total Artists:</strong> <?php echo $artistsCount; ?></div>
            <div class="stat-item"><strong>Total Albums:</strong> <?php echo $albumsCount; ?></div>
        </div>
    </div>
</body>
</html>

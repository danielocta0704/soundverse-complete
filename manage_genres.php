<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
require 'db_soundverse.php';

// Ambil data genres
$sql = "SELECT * FROM genres";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Genres</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
    <!-- Sidebar -->
   <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="manage_songs.php">Manage Songs</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_artists.php">Manage Artists</a>
        <a href="manage_genres.php">Manage Genres</a>
        <a href="manage_albums.php">Manage Albums</a>
        <a href="admin_panel.php">Back To Admin Panel</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Manage Genres</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Genre Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['genre_name']; ?></td>
                    <td>
                        <a href="delete_genre.php?id=<?php echo $row['genre_id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this genre?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add New Genre -->
        <a href="add_genre.php" class="btn add-btn">Add New Genre</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

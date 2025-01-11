<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Koneksi ke database
require 'db_soundverse.php';

// Ambil data albums
$sql = "SELECT albums.album_id, albums.album_title, artist_name AS artist_name
        FROM albums
        JOIN artists ON albums.artist_id = artists.artist_id";  // Perbaiki join dengan 'albums.artist_id' dan 'artists.artist_id'
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Albums</title>
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
        <a href="admin_panel.php">Back To Admin Panel
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Manage Albums</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Album Title</th>
                    <th>Artist</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['album_title']; ?></td>
                    <td><?php echo $row['artist_name']; ?></td>
                    <td>
                        <a href="delete_album.php?id=<?php echo $row['album_id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this album?')">Delete</a> <!-- Ganti 'id' dengan 'album_id' -->
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add New Album -->
        <a href="add_album.php" class="btn add-btn">Add New Album</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

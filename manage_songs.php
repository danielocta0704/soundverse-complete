<?php
require 'db_soundverse.php';  // Connect to the database

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connection successful.<br>"; // Debugging connection
}

// Check if there's a search query
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['search']);
}

// Fetch songs with optional search filter
$sql = "SELECT songs.song_id, songs.song_title AS song_name, artists.artist_name AS artist, albums.album_title AS album_name, genres.genre_name AS genre, songs.spotify_link
        FROM songs
        JOIN artists ON songs.artist_id = artists.artist_id
        JOIN albums ON songs.album_id = albums.album_id
        JOIN genres ON songs.genre_id = genres.genre_id
        WHERE songs.song_title LIKE '%$searchQuery%' 
        OR artists.artist_name LIKE '%$searchQuery%' 
        OR albums.album_title LIKE '%$searchQuery%' 
        OR genres.genre_name LIKE '%$searchQuery%'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query execution failed: " . $stmt->error);
}

// Check if any songs are found
if ($result->num_rows > 0) {
    echo "Data found: " . $result->num_rows . " songs.<br>";
} else {
    echo "No songs found.<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Songs</title>
    <link rel="stylesheet" type="text/css" href="manage.css">
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
        <h1>Manage Songs</h1>

        <!-- Search Bar -->
        <form method="GET" action="manage_songs.php">
            <input type="text" name="search" placeholder="Search by song, artist, album, or genre" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="btn search-btn">Search</button>
        </form>

        <!-- Song Table -->
        <table class="song-table">
            <thead>
                <tr>
                    <th>Song Name</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Genre</th>
                    <th>Spotify Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['song_name']; ?></td> 
                        <td><?php echo $row['artist']; ?></td>
                        <td><?php echo $row['album_name']; ?></td> 
                        <td><?php echo $row['genre']; ?></td>
                        <td>
                            <?php if (!empty($row['spotify_link'])): ?>
                                <iframe style="border-radius:12px" src="<?php echo $row['spotify_link']; ?>" width="100%" height="352" frameborder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                            <?php else: ?>
                                <p>No Spotify link available</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_song.php?id=<?php echo $row['song_id']; ?>" class="btn edit-btn">Edit</a>
                            <a href="delete_song.php?id=<?php echo $row['song_id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this song?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Song Button -->
        <a href="add_song.php" class="btn add-btn">Add New Song</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

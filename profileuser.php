<?php
session_start();

// Get the user_id from the query string
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if (!$user_id) {
    echo "User not found.";
    exit;
}

// Koneksi database melalui db_soundverse.php
require 'db_soundverse.php';

// Query to get user details
$sql = "SELECT username, profile_picture, bio FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_picture, $bio);
$stmt->fetch();
$stmt->close();

// Query to get rated songs by this user and their rating
$sqlSongs = "SELECT s.song_title, a.album_title, ar.artist_name, g.genre_name, s.spotify_link, r.rating 
FROM songs s 
LEFT JOIN albums a ON s.album_id = a.album_id 
LEFT JOIN artists ar ON s.artist_id = ar.artist_id 
LEFT JOIN genres g ON s.genre_id = g.genre_id 
LEFT JOIN ratings r ON s.song_id = r.song_id 
WHERE r.user_id = ?";

$stmtSongs = $conn->prepare($sqlSongs);
$stmtSongs->bind_param("i", $user_id);
$stmtSongs->execute();
$resultSongs = $stmtSongs->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($username ?? 'Default Username'); ?>'s Profile</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000000;
            padding: 15px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            color: white;
        }
        .navbar {
            position: absolute;
            right: 20px;
            top: 15px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
            text-align: center;
            color: #fff;
        }
        .profile-section img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }
        .profile-section p {
            font-size: 18px;
            color: #b3b3b3;
        }
        .song-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .song-card {
            background-color: #333;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.3s;
        }
        .song-card:hover {
            transform: scale(1.05);
            background-color: #444;
        }
        .song-card h3 {
            margin-bottom: 10px;
            color: #1db954;
        }
        .song-card iframe {
            border-radius: 10px;
            width: 100%;
            max-width: 300px;
            height: 80px;
        }
        .song-rating {
            margin-top: 10px;
            color: #ffcc00;
            font-weight: bold;
            font-size: 16px;
        }
        footer {
            background-color: #121212;
            color: #b3b3b3;
            text-align: center;
            padding: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <div class="navbar">
        <a href="home.php">Back to Home</a>
    </div>
    <h1><?php echo htmlspecialchars($username ?? 'Default Username'); ?>'s Profile</h1>
</header>
    
<div class="profile-section">
    <?php
    if ($profile_picture) {
        echo '<img src="' . htmlspecialchars($profile_picture) . '" alt="Profile Picture">';
    } else {
        echo '<img src="https://via.placeholder.com/150" alt="Profile Picture">';
    }
    ?>
    <p><?php echo htmlspecialchars($bio ?? 'No bio available'); ?></p>
</div>

<h2 style="text-align: center;">Rated Songs</h2>
<div class="song-grid">
    <?php if ($resultSongs->num_rows > 0) {
        while ($row = $resultSongs->fetch_assoc()) {
            echo '<div class="song-card">';
            echo '<h3>' . htmlspecialchars($row['song_title']) . '</h3>';
            echo '<p>Artist: ' . htmlspecialchars($row['artist_name']) . '</p>';
            echo '<p>Album: ' . htmlspecialchars($row['album_title']) . '</p>';
            echo '<p>Genre: ' . htmlspecialchars($row['genre_name']) . '</p>';
            
            // Spotify link embedding if available
            if (!empty($row['spotify_link'])) {
                echo '<iframe src="' . htmlspecialchars($row['spotify_link']) . '" frameborder="0" allow="encrypted-media"></iframe>';
            } else {
                echo '<p>Spotify link not available</p>';
            }

            // Display user's rating with the name
            echo '<p class="song-rating">' . htmlspecialchars($username) . '\'s Rating: ' . htmlspecialchars($row['rating']) . ' &#9733;</p>';
            echo '</div>';
        }
    } else {
        echo '<p style="text-align: center;">No rated songs available.</p>';
    } ?>
</div>

<footer>
    <p>&copy; 2025 SoundVerse | All Rights Reserved</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>
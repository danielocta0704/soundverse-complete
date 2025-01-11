<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['user'];
$user_id = $_SESSION['user_id'];
require 'db_soundverse.php'; // This line includes the db connection

// Database connection using the connection from db_soundverse.php
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the user's profile picture
$sql = "SELECT profile_picture FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profilePicture);
$stmt->fetch();
$stmt->close();

// Handle the search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch songs based on the search
$sqlSongs = "SELECT s.song_id AS song_id, s.song_title, s.spotify_link, a.album_title, ar.artist_name, g.genre_name, COALESCE(AVG(r.rating), 0) AS average_rating 
FROM songs s 
LEFT JOIN albums a ON s.album_id = a.album_id 
LEFT JOIN artists ar ON s.artist_id = ar.artist_id 
LEFT JOIN genres g ON s.genre_id = g.genre_id 
LEFT JOIN ratings r ON s.song_id = r.song_id 
WHERE s.song_title LIKE ? OR ar.artist_name LIKE ? OR a.album_title LIKE ? OR g.genre_name LIKE ? 
GROUP BY s.song_id, s.song_title, s.spotify_link, a.album_title, ar.artist_name, g.genre_name 
ORDER BY s.song_title";

$stmt = $conn->prepare($sqlSongs);
$searchTerm = "%" . $searchQuery . "%";
$stmt->bind_param('ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$resultSongs = $stmt->get_result();

// Query to fetch users based on the search
$sqlUsers = "SELECT u.user_id, u.username, u.profile_picture FROM users u WHERE u.username LIKE ?";
$stmtUsers = $conn->prepare($sqlUsers);
$searchTermUsers = "%" . $searchQuery . "%";
$stmtUsers->bind_param('s', $searchTermUsers);
$stmtUsers->execute();
$resultUsers = $stmtUsers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SoundVerse</title>
    <style>
        /* Inline CSS styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #1e1e1e;
            color: white;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #111;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
        }
        .logo {
            color: #1db954;
            font-size: 24px;
            font-weight: bold;
        }
        .navbar {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        .navbar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 0 15px;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #444;
        }
        .profile {
            display: flex;
            align-items: center;
        }
        .profile img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .search-container {
            text-align: center;
            margin-top: 80px;
        }
        .search-container form {
            display: inline-block;
            margin: 0;
        }
        .search-container input {
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 300px;
            margin-right: 10px;
            background-color: #333;
            color: white;
        }
        .search-container button {
            padding: 10px 20px;
            border: none;
            background-color: #1db954;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .search-container button:hover {
            background-color: #1ed760;
        }
        .song-grid, .user-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }
        .card, .user-card {
            background-color: #333;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.2s, background-color 0.3s;
        }
        .card:hover, .user-card:hover {
            transform: scale(1.05);
            background-color: #444;
        }
        .card img, .user-card img {
            border-radius: 5px;
            width: 100%;
            height: auto;
        }
        .user-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-bottom: 10px;
        }
        h3 {
            color: #1db954;
        }
        iframe {
            width: 100%;
            height: 150px;
            border: none;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">SoundVerse</div>
    <div class="navbar">
        <div class="profile">
            <?php
            if ($profilePicture) {
                echo '<img src="' . htmlspecialchars($profilePicture) . '" alt="Profile Picture" width="40" height="40">';
            } else {
                echo '<img src="https://via.placeholder.com/40" alt="Profile Picture">';
            }
            ?>
            <a href="mystats.php" style="color: white; text-decoration: none;">
                <span><?php echo htmlspecialchars($username); ?></span>
            </a>
        </div>
    </div>
</header>
<main>
    <div class="search-container">
        <form method="GET" action="home.php">
            <input type="text" name="search" placeholder="Search by song, artist, album, genre, or user" value="<?php echo htmlspecialchars($searchQuery); ?>" />
            <button type="submit">Search</button>
        </form>
    </div>
    <h2 style="text-align: center;">Song Results</h2>
    <div class="song-grid">
        <?php if ($resultSongs->num_rows > 0) {
            while ($row = $resultSongs->fetch_assoc()) {
                echo '<div class="card">';
                echo '<a href="rate.php?song_id=' . $row['song_id'] . '" style="color: white; text-decoration: none;">';
                echo '<h3>' . htmlspecialchars($row['song_title']) . '</h3>';
                echo '<p>Artist: ' . htmlspecialchars($row['artist_name']) . '</p>';
                echo '<p>Album: ' . htmlspecialchars($row['album_title']) . '</p>';
                echo '<p>Genre: ' . htmlspecialchars($row['genre_name']) . '</p>';
                echo '<p>Average Rating: ' . number_format($row['average_rating'], 1) . ' &#9733;</p>';
                if (!empty($row['spotify_link'])) {
                    echo '<iframe src="' . htmlspecialchars($row['spotify_link']) . '" frameborder="0"></iframe>';
                }
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No results found.</p>';
        } ?>
    </div>

    <h2 style="text-align: center;">User Results</h2>
    <div class="user-grid">
        <?php if ($resultUsers->num_rows > 0) {
            while ($row = $resultUsers->fetch_assoc()) {
                echo '<div class="user-card">';
                echo '<a href="profileuser.php?user_id=' . $row['user_id'] . '" style="color: white; text-decoration: none;">';
                $profileImage = !empty($row['profile_picture']) ? $row['profile_picture'] : 'https://via.placeholder.com/100';
                echo '<img src="' . htmlspecialchars($profileImage) . '" alt="Profile Picture">';
                echo '<h3>' . htmlspecialchars($row['username']) . '</h3>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No users found.</p>';
        } ?>
    </div>
</main>
<footer style="text-align: center; padding: 10px 0; background-color: #111; color: white;">
    <p>SoundVerse &copy; 2025</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>
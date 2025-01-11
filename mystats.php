<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Ambil nama pengguna dan user_id dari sesi
$username = $_SESSION['user'];
$user_id = $_SESSION['user_id'];

// Include the database connection file
require 'db_soundverse.php'; // This will include the db_soundverse.php file

// Query untuk mengambil foto profil, bio, dan total lagu yang telah dirating
$sqlProfile = "SELECT profile_picture, bio 
               FROM users 
               WHERE user_id = ?";
$stmtProfile = $conn->prepare($sqlProfile);
$stmtProfile->bind_param('i', $user_id);
$stmtProfile->execute();
$stmtProfile->bind_result($profilePicture, $bio);
$stmtProfile->fetch();
$stmtProfile->close();

// Query untuk mengambil total lagu yang telah dirating oleh user
$sqlTotalRatings = "SELECT COUNT(*) AS total_ratings 
                    FROM ratings 
                    WHERE username = ?";
$stmtTotalRatings = $conn->prepare($sqlTotalRatings);
$stmtTotalRatings->bind_param('s', $username);
$stmtTotalRatings->execute();
$stmtTotalRatings->bind_result($totalRatings);
$stmtTotalRatings->fetch();
$stmtTotalRatings->close();

// Query untuk mengambil lagu yang telah dirating oleh user
$sqlRatings = "SELECT s.song_title, ar.artist_name, a.album_title, 
                      g.genre_name, r.rating, s.spotify_link
               FROM ratings r
               INNER JOIN songs s ON r.song_id = s.song_id
               LEFT JOIN artists ar ON s.artist_id = ar.artist_id
               LEFT JOIN albums a ON s.album_id = a.album_id
               LEFT JOIN genres g ON s.genre_id = g.genre_id
               WHERE r.username = '$username'";

$resultRatings = $conn->query($sqlRatings);

if (!$resultRatings) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - SoundVerse</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000000; /* Changed to black */
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: white; /* Keep text white for visibility */
            font-size: 14px;
        }
        nav ul li a:hover {
            color: #1db954; /* Optional: Green color on hover for a nice effect */
        }
        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
            text-align: center;
            color: #fff;
        }
        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }
        .profile-container h2 {
            margin: 0;
            color: #1db954; /* Matching color for the username */
        }
        .profile-container p {
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
            color: #1db954; /* Highlight song title with green color */
        }
        .song-card iframe {
            border-radius: 10px;
            width: 100%;
            max-width: 300px;
            height: 80px;
        }
        .song-rating {
            margin-top: 10px;
            color: #ffcc00; /* Gold for the rating */
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
    <div class="logo">SoundVerse</div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="logout.php">Logout</a></li>
            <li><a href="settings.php">Settings</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="profile-container">
        <!-- Profile Picture -->
        <?php
        // Add check to avoid passing null to htmlspecialchars
        if (!empty($profilePicture)) {
            echo '<img src="' . htmlspecialchars($profilePicture) . '" alt="Profile Picture">';
        } else {
            echo '<img src="https://via.placeholder.com/150" alt="Profile Picture">';
        }
        ?>
        <h2><?php echo htmlspecialchars($username ?? 'Username Not Available'); ?></h2>
        <p><?php echo htmlspecialchars($bio ?? 'No bio available'); ?></p>
        <p><strong>Total Lagu yang Anda Rating:</strong> <?php echo $totalRatings; ?></p>
    </div>

    <h2 style="text-align: center;">Lagu yang Telah Anda Rating</h2>
    <div class="song-grid">
        <?php
        if ($resultRatings->num_rows > 0) {
            while ($row = $resultRatings->fetch_assoc()) {
                echo '<div class="song-card">';
                echo '<h3>' . htmlspecialchars($row['song_title']) . '</h3>';
                echo '<p>Artist: ' . htmlspecialchars($row['artist_name']) . '</p>';
                echo '<p>Album: ' . htmlspecialchars($row['album_title']) . '</p>';
                echo '<p>Genre: ' . htmlspecialchars($row['genre_name']) . '</p>';
                if (!empty($row['spotify_link'])) {
                    echo '<iframe src="' . htmlspecialchars($row['spotify_link']) . '" frameborder="0" allow="encrypted-media"></iframe>';
                } else {
                    echo '<p>Spotify link not available</p>';
                }
                echo '<p class="song-rating">Your Rating: ' . htmlspecialchars($row['rating']) . ' &#9733;</p>';
                echo '</div>';
            }
        } else {
            echo '<p style="text-align: center;">Anda belum memberikan rating pada lagu apa pun.</p>';
        }
        ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 SoundVerse | All Rights Reserved</p>
</footer>

</body>
</html>

<?php
// Menutup koneksi database
$conn->close();
?>
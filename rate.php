<?php
session_start();

$username = $_SESSION['user'];
$user_id = $_SESSION['user_id']; // Fetch user ID from session

// Database connection
require 'db_soundverse.php';

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get song_id from URL
$song_id = isset($_GET['song_id']) ? intval($_GET['song_id']) : 0;

// Fetch song details
$sql = "SELECT s.song_id, s.song_title, ar.artist_name, a.album_title, s.spotify_link
        FROM songs s
        LEFT JOIN artists ar ON s.artist_id = ar.artist_id
        LEFT JOIN albums a ON s.album_id = a.album_id
        WHERE s.song_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $song_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Song not found!";
    exit;
}

$song = $result->fetch_assoc();

// Process rating submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 0;

    if ($rating < 1 || $rating > 5) {
        echo "<script>alert('Rating must be between 1 and 5.'); window.location.href='rate.php?song_id=$song_id';</script>";
        exit;
    }

    // Insert or update rating
    $sql = "INSERT INTO ratings (user_id, song_id, rating, username) 
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE rating = VALUES(rating)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iids", $user_id, $song_id, $rating, $username);
    
    if ($stmt->execute()) {
        echo "<script>alert('Rating submitted successfully!'); window.location.href='home.php';</script>";
    } else {
        echo "<script>alert('Failed to submit rating. Please try again.'); window.location.href='rate.php?song_id=$song_id';</script>";
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Song - <?php echo htmlspecialchars($song['song_title']); ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .rate-container {
            background-color: #333;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            color: #1db954;
            margin-bottom: 10px;
        }

        p {
            color: #b3b3b3;
        }

        .spotify-link {
            margin: 15px 0;
            font-size: 16px;
            color: #1db954;
            text-decoration: none;
            font-weight: bold;
        }

        .spotify-link:hover {
            text-decoration: underline;
        }

        .stars {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            flex-direction: row-reverse; /* Right-to-left order */
        }

        input[type="radio"] {
            display: none; /* Hide radio buttons */
        }

        label {
            font-size: 40px;
            color: #444;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
            margin: 0 5px;
        }

        /* Highlight stars from right to left */
        label:hover,
        label:hover ~ label,
        input[type="radio"]:checked ~ label {
            color: #1db954;
        }

        button {
            background-color: #1db954;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #18a347;
        }

        .spotify-embed {
            margin-top: 15px;
        }

        iframe {
            border-radius: 10px;
            width: 100%;
            max-width: 300px;
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="rate-container">
        <h1>Rate Song</h1>
        <h2><?php echo htmlspecialchars($song['song_title']); ?></h2>
        <p>Artist: <?php echo htmlspecialchars($song['artist_name']); ?></p>
        <p>Album: <?php echo htmlspecialchars($song['album_title']); ?></p>
        
        <!-- Spotify Link and Embed Player -->
        <?php if (!empty($song['spotify_link'])): ?>
            <!-- If the spotify_link contains an embed-friendly URL (i.e., it can be directly embedded as iframe) -->
            <div class="spotify-embed">
                <iframe src="<?php echo htmlspecialchars($song['spotify_link']); ?>" frameborder="0" allow="encrypted-media"></iframe>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="stars">
                <!-- Stars from right to left -->
                <input type="radio" id="star5" name="rating" value="5">
                <label for="star5">&#9733;</label>

                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">&#9733;</label>

                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>

                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">&#9733;</label>

                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">&#9733;</label>
            </div>
            <button type="submit">Submit Rating</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
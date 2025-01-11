<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Ganti bagian koneksi database dengan file eksternal
require 'db_soundverse.php';

// Proses penambahan lagu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $songTitle = $_POST['song_title'];
    $albumId = $_POST['album_id'];
    $artistId = $_POST['artist_id'];
    $genreId = $_POST['genre_id'];
    $spotifyLink = $_POST['spotify_link'];

    $sql = "INSERT INTO songs (song_title, album_id, artist_id, genre_id, spotify_link) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiss", $songTitle, $albumId, $artistId, $genreId, $spotifyLink);

    if ($stmt->execute()) {
        echo "Lagu berhasil ditambahkan!";
    } else {
        echo "Terjadi kesalahan saat menambahkan lagu: " . $stmt->error;
    }
}

// Mengambil data untuk dropdown
$albums = $conn->query("SELECT album_id, album_title FROM albums");
$artists = $conn->query("SELECT artist_id, artist_name FROM artists");
$genres = $conn->query("SELECT genre_id, genre_name FROM genres");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container for the form */
        .container {
            background-color: #282828;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: #1DB954;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #b3b3b3;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #1e1e1e;
            color: #ffffff;
        }

        input::placeholder {
            color: #555;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            color: #121212;
            background-color: #1DB954;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #14803c;
        }

        .back-button {
            display: block;
            text-align: center;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            background-color: #404040;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #333;
        }

        .form-container {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Song</h2>
        <form method="POST" class="form-container">
            <label for="song_title">Song Title:</label>
            <input type="text" id="song_title" name="song_title" placeholder="Enter song title" required>

            <label for="album_id">Album:</label>
            <select name="album_id" id="album_id" required>
                <?php while ($row = $albums->fetch_assoc()): ?>
                    <option value="<?php echo $row['album_id']; ?>"><?php echo $row['album_title']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="artist_id">Artist:</label>
            <select name="artist_id" id="artist_id" required>
                <?php while ($row = $artists->fetch_assoc()): ?>
                    <option value="<?php echo $row['artist_id']; ?>"><?php echo $row['artist_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="genre_id">Genre:</label>
            <select name="genre_id" id="genre_id" required>
                <?php while ($row = $genres->fetch_assoc()): ?>
                    <option value="<?php echo $row['genre_id']; ?>"><?php echo $row['genre_name']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="spotify_link">Spotify Link:</label>
            <input type="text" name="spotify_link" id="spotify_link" placeholder="Enter Spotify link" required>

            <button type="submit">Add Song</button>
        </form>
        <a href="manage_songs.php" class="back-button">Back to Manage Songs</a>
    </div>
</body>
</html>
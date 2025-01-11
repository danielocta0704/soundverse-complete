<?php
require 'db_soundverse.php'; // Koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data lagu berdasarkan ID
    $sql = "SELECT * FROM songs WHERE song_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $song = $result->fetch_assoc();

    if (!$song) {
        echo "<script>alert('Song not found!'); window.location.href='manage_songs.php';</script>";
        exit;
    }
}

// Fetch artists for dropdown
$artists_sql = "SELECT artist_id, artist_name FROM artists";
$artists_result = $conn->query($artists_sql);

// Fetch albums for dropdown
$albums_sql = "SELECT album_id, album_title FROM albums";
$albums_result = $conn->query($albums_sql);

// Fetch genres for dropdown
$genres_sql = "SELECT genre_id, genre_name FROM genres";
$genres_result = $conn->query($genres_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $song_title = htmlspecialchars(trim($_POST['song_title']));
    $artist_id = $_POST['artist_id'];
    $album_id = $_POST['album_id'];
    $genre_id = $_POST['genre_id'];
    $spotify_link = !empty($_POST['spotify_link']) ? htmlspecialchars(trim($_POST['spotify_link'])) : null;

    // Validasi input
    if (empty($song_title) || empty($artist_id) || empty($album_id) || empty($genre_id)) {
        echo "<script>alert('All fields except Spotify link are required!'); window.location.href='edit_song.php?id=$id';</script>";
        exit;
    }

    // Update data lagu
    if (!empty($spotify_link)) {
        $sql = "UPDATE songs SET song_title = ?, artist_id = ?, album_id = ?, genre_id = ?, spotify_link = ? WHERE song_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siissi", $song_title, $artist_id, $album_id, $genre_id, $spotify_link, $id);
    } else {
        $sql = "UPDATE songs SET song_title = ?, artist_id = ?, album_id = ?, genre_id = ? WHERE song_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siisi", $song_title, $artist_id, $album_id, $genre_id, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Song updated successfully!'); window.location.href='manage_songs.php';</script>";
    } else {
        echo "Error updating song: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Song</title>
    <style>
        /* Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #1DB954;
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #282828;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-size: 16px;
            margin: 8px 0;
            color: #b3b3b3;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #1e1e1e;
            color: #fff;
        }

        input:focus,
        select:focus {
            border-color: #1DB954;
            outline: none;
        }

        button {
            padding: 12px 20px;
            background-color: #1DB954;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none;
        }

        button:hover {
            background-color: #14803c;
        }

        .cancel-btn {
            padding: 12px 20px;
            background-color: #d32f2f;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background-color: #9a2424;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
                margin: 20px;
            }

            h1 {
                font-size: 24px;
            }

            label,
            input,
            select {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Song</h1>
        <form method="POST">
            <label for="song_title">Song Title:</label>
            <input type="text" id="song_title" name="song_title" value="<?php echo $song['song_title']; ?>" required>

            <label for="artist_id">Artist:</label>
            <select id="artist_id" name="artist_id" required>
                <?php while ($artist = $artists_result->fetch_assoc()) { ?>
                    <option value="<?php echo $artist['artist_id']; ?>" <?php echo ($song['artist_id'] == $artist['artist_id']) ? 'selected' : ''; ?>>
                        <?php echo $artist['artist_name']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="album_id">Album:</label>
            <select id="album_id" name="album_id" required>
                <?php while ($album = $albums_result->fetch_assoc()) { ?>
                    <option value="<?php echo $album['album_id']; ?>" <?php echo ($song['album_id'] == $album['album_id']) ? 'selected' : ''; ?>>
                        <?php echo $album['album_title']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="genre_id">Genre:</label>
            <select id="genre_id" name="genre_id" required>
                <?php while ($genre = $genres_result->fetch_assoc()) { ?>
                    <option value="<?php echo $genre['genre_id']; ?>" <?php echo ($song['genre_id'] == $genre['genre_id']) ? 'selected' : ''; ?>>
                        <?php echo $genre['genre_name']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="spotify_link">Spotify Link (optional):</label>
            <input type="url" id="spotify_link" name="spotify_link" value="<?php echo $song['spotify_link']; ?>">

            <div style="text-align: center;">
                <button type="submit">Update Song</button>
                <a href="manage_songs.php" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

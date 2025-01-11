<?php
require 'db_soundverse.php';  // Menghubungkan ke database

// Mendapatkan data artis untuk dropdown
$sql_artists = "SELECT artist_id, artist_name FROM artists"; // Ganti 'name' dengan 'artist_name'
$result_artists = $conn->query($sql_artists);

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $album_title = $_POST['album_title'];
    $artist_id = $_POST['artist_id'];

    // Validasi input
    if (!empty($album_title) && !empty($artist_id)) {
        // Query untuk menambahkan album baru
        $sql = "INSERT INTO albums (album_title, artist_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("si", $album_title, $artist_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Redirect to a different page (album list) after success
            header("Location: manage_albums.php");
            exit;
        } else {
            echo "<script>alert('Gagal menambahkan album.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Semua data harus diisi!');</script>";
    }
}

// Menutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Album</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
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

        h1 {
            text-align: center;
            color: #1DB954;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #b3b3b3;
        }

        input, select {
            width: 100%;
            padding: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Album</h1>
        
        <form action="add_album.php" method="POST">
            <div class="form-group">
                <label for="album_title">Album Title:</label>
                <input type="text" id="album_title" name="album_title" placeholder="Enter album title" required>
            </div>

            <div class="form-group">
                <label for="artist_id">Select Artist:</label>
                <select id="artist_id" name="artist_id" required>
                    <option value="">-- Select Artist --</option>
                    <?php
                    // Menampilkan artis yang ada
                    if ($result_artists->num_rows > 0) {
                        while ($row = $result_artists->fetch_assoc()) {
                            echo "<option value='" . $row['artist_id'] . "'>" . $row['artist_name'] . "</option>"; // Ganti 'name' dengan 'artist_name'
                        }
                    } else {
                        echo "<option value=''>No artists available</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit">Add Album</button>
        </form>

        <a href="manage_albums.php" class="back-button">Back to Manage Albums</a>
    </div>
</body>
</html>

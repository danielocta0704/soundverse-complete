<?php
require 'db_soundverse.php';  // Menghubungkan ke database

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $artist_name = $_POST['artist_name'];

    // Validasi input
    if (!empty($artist_name)) {
        // Query untuk menambahkan artis baru
        $sql = "INSERT INTO artists (artist_name) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("s", $artist_name);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Artist added successfully!'); window.location.href='manage_artists.php';</script>";
        } else {
            echo "<script>alert('Failed to add artist: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Artist name cannot be empty!');</script>";
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
    <title>Add Artist</title>
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

        input {
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
        <h1>Add New Artist</h1>
        
        <form action="add_artist.php" method="POST">
            <label for="artist_name">Artist Name:</label>
            <input type="text" id="artist_name" name="artist_name" placeholder="Enter artist name" required>

            <button type="submit">Add Artist</button>
        </form>

        <a href="manage_artists.php" class="back-button">Back to Manage Artists</a>
    </div>
</body>
</html>

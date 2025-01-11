<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'db_soundverse.php';  // Menghubungkan ke database

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $genre_name = $_POST['genre_name'];

    // Validasi input
    if (!empty($genre_name)) {
        // Query untuk menambahkan genre baru
        $sql = "INSERT INTO genres (genre_name) VALUES (?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("s", $genre_name);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Genre berhasil ditambahkan!'); window.location.href='manage_genres.php';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan genre.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Nama genre tidak boleh kosong!');</script>";
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
    <title>Add Genre</title>
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
        <h1>Add New Genre</h1>
        
        <form action="add_genre.php" method="POST">
            <div class="form-group">
                <label for="genre_name">Genre Name:</label>
                <input type="text" id="genre_name" name="genre_name" placeholder="Enter genre name" required>
            </div>

            <button type="submit">Add Genre</button>
        </form>

        <a href="manage_genres.php" class="back-button">Back to Manage Genres</a>
    </div>
</body>
</html>

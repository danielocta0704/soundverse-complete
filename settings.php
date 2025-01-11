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

// Koneksi ke database
require 'db_soundverse.php';  // Include database connection file

// Ambil informasi pengguna dari database
$sqlProfile = "SELECT username, profile_picture, bio FROM users WHERE user_id = ?";
$stmtProfile = $conn->prepare($sqlProfile);
$stmtProfile->bind_param('i', $user_id);
$stmtProfile->execute();
$stmtProfile->bind_result($currentUsername, $profilePicture, $bio);
$stmtProfile->fetch();
$stmtProfile->close();

// Proses pengubahan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newBio = $_POST['bio'];
    $newProfilePicture = $_FILES['profile_picture']['name'];

    // Jika ada gambar yang diupload
    if ($_FILES['profile_picture']['error'] == 0) {
        // Tentukan path untuk menyimpan gambar
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($newProfilePicture);
        
        // Cek jika gambar berhasil di-upload
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
            // Update data foto profil, username, dan bio
            $sqlUpdate = "UPDATE users SET username = ?, profile_picture = ?, bio = ? WHERE user_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param('sssi', $newUsername, $targetFile, $newBio, $user_id);
            $stmtUpdate->execute();
            $stmtUpdate->close();
            header('Location: mystats.php');  // Arahkan ke mystats.php
            exit;
        } else {
            echo "Terjadi kesalahan saat meng-upload gambar.";
        }
    } else {
        // Jika tidak ada gambar yang di-upload, hanya update username dan bio
        $sqlUpdate = "UPDATE users SET username = ?, bio = ? WHERE user_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ssi', $newUsername, $newBio, $user_id);
        $stmtUpdate->execute();
        $stmtUpdate->close();
        header('Location: mystats.php');  // Arahkan ke mystats.php
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SoundVerse</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        header .navbar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }
        .settings-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
            color: #fff;
        }
        .settings-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }
        .settings-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .settings-form input, .settings-form textarea {
            background-color: #333;
            border: 1px solid #444;
            padding: 10px;
            margin: 10px;
            color: #fff;
            border-radius: 5px;
            width: 300px;
        }
        .settings-form input[type="file"] {
            padding: 5px;
        }
        .settings-form button {
            background-color: #1db954;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .settings-form button:hover {
            background-color: #17a34a;
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
    <div class="navbar">
        <a href="home.php">Back to Home</a>
    </div>
</header>

<main>
    <div class="settings-container">
        <!-- Menampilkan foto profil -->
        <?php
        if (!empty($profilePicture)) {
            echo '<img src="' . htmlspecialchars($profilePicture) . '" alt="Profile Picture">';
        } else {
            echo '<img src="https://via.placeholder.com/150" alt="Profile Picture">';
        }
        ?>

        <!-- Form untuk mengubah username, bio, dan foto profil -->
        <h2>Change Your Profile</h2>
        <form class="settings-form" method="POST" enctype="multipart/form-data">
            <!-- Username -->
            <label for="username">Change Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($currentUsername); ?>" required>

            <!-- Bio -->
            <label for="bio">Change Bio</label>
            <textarea id="bio" name="bio" rows="4" required><?php echo htmlspecialchars($bio); ?></textarea>

            <!-- Foto Profil -->
            <label for="profile_picture">Change Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture">

            <!-- Tombol untuk menyimpan perubahan -->
            <button type="submit">Save Changes</button>
        </form>
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
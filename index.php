<?php
session_start();

// Redirect to the dashboard or main page if the user is already logged in
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundVerse - Login or Register</title>
    <link rel="stylesheet" href="landing.css">
</head>
<body>

    <main>
        <div class="logo">SoundVerse</div>
        <p>Feel the Music</p>
        <p>Listen to millions of songs and rate them.</p>
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn">Register</a>
    </main>
    
    <footer>
        <p>&copy; 2024 SoundVerse | All Rights Reserved</p>
    </footer>

</body>
</html>

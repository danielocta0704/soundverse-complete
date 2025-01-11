<?php
session_start();

// Cek apakah sesi sudah aktif (artinya pengguna sudah login)
if (isset($_SESSION['user'])) {
    // Jika sudah login, redirect ke halaman home
    header('Location: home.php');
    exit;
}

// Variabel untuk pesan kesalahan
$error_message = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SoundVerse</title>
    <style>
        /* Gaya CSS untuk form login */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #121212;
            color: #fff;
        }
        h1 {
            text-align: center;
            color: #1db954;
            margin-top: 20px;
        }
        form {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #222;
            color: #fff;
        }
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #1db954;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            font-size: 16px;
        }
        button[type="submit"]:hover {
            background-color: #18a347;
        }
        p {
            text-align: center;
            color: #fff;
        }
        a {
            color: #1db954;
            text-decoration: none;
        }
        a:hover {
            color: #18a347;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Login to SoundVerse</h1>
    <!-- Menampilkan pesan error jika ada -->
    <?php if ($error_message): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form method="POST" action="login_process.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
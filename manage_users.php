<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'db_soundverse.php';

$sql = "SELECT user_id, username, email FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="manage.css">
</head>
<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <a href="manage_songs.php">Manage Songs</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="manage_artists.php">Manage Artists</a>
        <a href="manage_genres.php">Manage Genres</a>
        <a href="manage_albums.php">Manage Albums</a>
        <a href="admin_panel.php">Back To Admin Panel
    </div>
    <div class="content">
        <h1>Manage Users</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                            <a href="edit_user.php?id=<?php echo $row['user_id']; ?>" class="btn edit-btn">Edit</a>
                            <a href="delete_user.php?id=<?php echo $row['user_id']; ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this song?')">Delete</a>
                        </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_user.php" class="btn add-btn">Add New User</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
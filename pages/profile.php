<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>User Profile</title>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="profile-card">
            <img src="../uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
            <h3><?php echo $user['full_name']; ?></h3>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Age:</strong> <?php echo $user['age']; ?></p>
            <p><strong>Qualifications:</strong> <?php echo $user['qualifications']; ?></p>
            <p><strong>Experiences:</strong> <?php echo $user['experiences']; ?></p>
            <p><strong>Permanent Address:</strong> <?php echo $user['permanent_address']; ?></p>
            <p><strong>Current Address:</strong> <?php echo $user['current_address']; ?></p>
        </div>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

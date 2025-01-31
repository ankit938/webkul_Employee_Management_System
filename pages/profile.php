<?php
session_start();
include('../config/database.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user details
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>User Profile</title>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="profile-card">
            <div class="profile-img-container">
                <img src="../uploads/<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                <a href="edit_profile.php" class="edit-icon"><i class="fas fa-pencil-alt"></i></a>
            </div>

            <h3><?php echo htmlspecialchars($user['full_name']); ?></h3><br>
            <p><strong>Date of Birth:</strong> 
                <?php echo isset($user['date_of_birth']) && !empty($user['date_of_birth']) ? htmlspecialchars($user['date_of_birth']) : 'Not provided'; ?>
            </p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
            <p><strong>Qualifications:</strong> <?php echo htmlspecialchars($user['qualifications']); ?></p>
            <p><strong>Experiences:</strong> <?php echo htmlspecialchars($user['experiences']); ?></p>
            <p><strong>Permanent Address:</strong> <?php echo htmlspecialchars($user['permanent_address']); ?></p>
            <p><strong>Current Address:</strong> <?php echo htmlspecialchars($user['current_address']); ?></p>
        </div>
        <button id="logout">
    <a href="logout.php" style="color:white; text-decoration:none; margin:1px ">Logout</a>
</button>

    </div>
</body>
</html>

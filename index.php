<?php
// Redirect to login page if the user is not logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit();
}

// Display homepage content
echo "<h1>Welcome to the Home Page</h1>";
echo "<p><a href='pages/profile.php'>Go to Profile</a></p>";
echo "<p><a href='pages/logout.php'>Logout</a></p>";
?>

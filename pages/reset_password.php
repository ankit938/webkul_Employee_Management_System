<?php
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["token"])) {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Verify token and update password
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password reset successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Invalid or expired token.";
    }
} else if (isset($_GET["token"])) {
    $token = $_GET["token"];
?>
    <form method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="password" required placeholder="Enter new password">
        <button type="submit">Reset Password</button>
    </form>
<?php
} else {
    echo "Invalid request.";
}
?>

<?php
session_start(); 

require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $token = bin2hex(random_bytes(32)); 
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        require '../assets/script/password_reset_mailer.php';
        sendResetEmail($email, $token);
        $_SESSION['message'] = "Password reset link sent to your email.";
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = "Email not found.";
        $_SESSION['message_type'] = 'error';
    }
    header("Location: forgot_password.php?submitted=true");
    exit();
}
?>

<head>
    <link rel="stylesheet" href="../assets/css/forgot_password.css">
</head>

<!-- The form for resetting password -->
<form method="post">
    <input type="email" name="email" required placeholder="Enter your email">
    <button type="submit">Reset Password</button>
</form>
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification <?php echo $_SESSION['message_type'] == 'error' ? 'error' : ''; ?> show">
        <?php echo $_SESSION['message']; ?>
    </div>
    <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

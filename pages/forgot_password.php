<?php
session_start(); // Start the session

require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $token = bin2hex(random_bytes(32)); // Generate a secure token

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Store token in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send reset email
        require '../assets/script/password_reset_mailer.php';
        sendResetEmail($email, $token);

        // Set success message in session
        $_SESSION['message'] = "Password reset link sent to your email.";
        $_SESSION['message_type'] = 'success'; // Success message (green)
    } else {
        // Set error message in session
        $_SESSION['message'] = "Email not found.";
        $_SESSION['message_type'] = 'error'; // Error message (red)
    }

    // Redirect to the same page to avoid form resubmission on refresh
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

<!-- Display the notification message -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification <?php echo $_SESSION['message_type'] == 'error' ? 'error' : ''; ?> show">
        <?php echo $_SESSION['message']; ?>
    </div>
    <?php 
        // Clear the session message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>

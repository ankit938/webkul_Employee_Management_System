<?php include('../config/database.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Signup</title>
    <style>
        select, input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <h2>Signup</h2>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" required>
            <input type="number" name="age" placeholder="Age" required>

            <div id="qualifications">
                <label>Qualifications</label>
                <input type="text" name="qualifications[]" placeholder="Qualification">
            </div>
            <button type="button" onclick="addQualification()">Add More</button>

            <div id="experiences">
                <label>Experiences</label>
                <input type="text" name="experiences[]" placeholder="Experience">
            </div>
            <button type="button" onclick="addExperience()">Add More</button>

            <label>Permanent Address</label>
            <input type="text" name="permanent_address_line1" placeholder="Address Line 1" required>
            <input type="text" name="permanent_address_line2" placeholder="Address Line 2">
            <input type="text" name="permanent_city" placeholder="City" required>
            <select name="permanent_state" required>
                <option value="">Select State</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="Bihar">Bihar</option>
                <option value="Delhi">Delhi</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Other">Other</option>
            </select><br>

            <label>Current Address</label>
            <input type="text" name="current_address_line1" placeholder="Address Line 1" required>
            <input type="text" name="current_address_line2" placeholder="Address Line 2">
            <input type="text" name="current_city" placeholder="City" required>
            <select name="current_state" required>
                <option value="">Select State</option>
                <option value="Uttar Pradesh">Uttar Pradesh</option>
                <option value="Bihar">Bihar</option>
                <option value="Delhi">Delhi</option>
                <option value="Maharashtra">Maharashtra</option>
                <option value="Other">Other</option>
            </select><br>

            <label>Upload Profile Picture</label>
            <input type="file" name="profile_picture" required>
            
            <div style="margin-top: 20px;"></div> <!-- Added gap -->

            <button type="submit" name="signup">Signup</button>
            
<p>Have an account? <a href="login.php" style="display: inline; text-align:center;">Log In Here</a></p>
        </form>
    </div>

    <script>
        function addQualification() {
            const div = document.createElement('div');
            div.innerHTML = '<input type="text" name="qualifications[]" placeholder="Qualification">';
            document.getElementById('qualifications').appendChild(div);
        }

        function addExperience() {
            const div = document.createElement('div');
            div.innerHTML = '<input type="text" name="experiences[]" placeholder="Experience">';
            document.getElementById('experiences').appendChild(div);
        }
    </script>
    <script src="../assets/script/password.js"></script>

</body>
</html>

<?php
if (isset($_POST['signup'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $date_of_birth = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $qualifications = implode(',', $_POST['qualifications']);
    $experiences = implode(',', $_POST['experiences']);
    $permanent_address = $_POST['permanent_address_line1'] . ', ' . $_POST['permanent_city'] . ', ' . $_POST['permanent_state'];
    $current_address = $_POST['current_address_line1'] . ', ' . $_POST['current_city'] . ', ' . $_POST['current_state'];
    $profile_picture = $_FILES['profile_picture']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($profile_picture);

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } elseif (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (full_name, email, password, date_of_birth, age, qualifications, experiences, permanent_address, current_address, profile_picture)
                  VALUES ('$full_name', '$email', '$hashed_password', '$date_of_birth', '$age', '$qualifications', '$experiences', '$permanent_address', '$current_address', '$profile_picture')";

        if ($conn->query($query) === TRUE) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload profile picture.";
    }
}
?>

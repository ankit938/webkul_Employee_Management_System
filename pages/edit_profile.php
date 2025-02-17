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

// Handle profile update
if (isset($_POST['update_profile'])) {
    $age = $_POST['age'];
    $qualifications = implode(',', $_POST['qualifications']);
    $experiences = implode(',', $_POST['experiences']);
    $permanent_address = $_POST['permanent_address_line1'] . ', ' . $_POST['permanent_city'] . ', ' . $_POST['permanent_state'];
    $current_address = $_POST['current_address_line1'] . ', ' . $_POST['current_city'] . ', ' . $_POST['current_state'];
    $date_of_birth = $_POST['date_of_birth']; // Get the updated date of birth
    
    if (!empty($_FILES['profile_picture']['name'])) {
        // Handle profile picture upload
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($profile_picture);
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $update_query = "UPDATE users SET age='$age', qualifications='$qualifications', experiences='$experiences', permanent_address='$permanent_address', current_address='$current_address', date_of_birth='$date_of_birth', profile_picture='$profile_picture' WHERE id='$user_id'";
        } else {
            echo "Failed to upload profile picture.";
            exit;
        }
    } else {
        $update_query = "UPDATE users SET age='$age', qualifications='$qualifications', experiences='$experiences', permanent_address='$permanent_address', current_address='$current_address', date_of_birth='$date_of_birth' WHERE id='$user_id'";
    }

    if ($conn->query($update_query) === TRUE) {
        echo "<h2  style='color:green; padding:5px;'>Profile updated successfully!</h2>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// **Fix for Undefined Array Key Warning**
$permanent_address_parts = explode(',', $user['permanent_address']);
$permanent_state = isset($permanent_address_parts[2]) ? trim($permanent_address_parts[2]) : '';

$current_address_parts = explode(',', $user['current_address']);
$current_state = isset($current_address_parts[2]) ? trim($current_address_parts[2]) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Edit Profile</title>
    <style>
        #date_of_birth{
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="profile-card">
                <h3><?php echo $user['full_name']; ?></h3>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p><br>

                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" name="date_of_birth" id="date_of_birth" 
                       value="<?php echo $user['date_of_birth']; ?>" required>

                <label for="age">Age:</label>
                <input type="number" name="age" id="age" value="<?php echo $user['age']; ?>" required>

                <label>Qualifications:</label>
                <div id="qualifications">
                    <?php 
                    $qualifications = explode(',', $user['qualifications']);
                    foreach ($qualifications as $qualification) {
                        echo '<input type="text" name="qualifications[]" value="' . $qualification . '" required>';
                    }
                    ?>
                </div>
                <button type="button" onclick="addQualification()">Add More</button>

                <label>Experiences:</label>
                <div id="experiences">
                    <?php 
                    $experiences = explode(',', $user['experiences']);
                    foreach ($experiences as $experience) {
                        echo '<input type="text" name="experiences[]" value="' . $experience . '" required>';
                    }
                    ?>
                </div>
                <button type="button" onclick="addExperience()">Add More</button>

                <label>Permanent Address:</label>
                <input type="text" name="permanent_address_line1" value="<?php echo $permanent_address_parts[0] ?? ''; ?>" required>
                <input type="text" name="permanent_address_line2" value="<?php echo $permanent_address_parts[1] ?? ''; ?>">
                <input type="text" name="permanent_city" value="<?php echo $permanent_address_parts[2] ?? ''; ?>" required>
                <select name="permanent_state" required>
                    <option value="Uttar Pradesh" <?php echo ($permanent_state == 'Uttar Pradesh') ? 'selected' : ''; ?>>Uttar Pradesh</option>
                    <option value="Bihar" <?php echo ($permanent_state == 'Bihar') ? 'selected' : ''; ?>>Bihar</option>
                    <option value="Delhi" <?php echo ($permanent_state == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                    <option value="Maharashtra" <?php echo ($permanent_state == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                    <option value="Other" <?php echo ($permanent_state == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>

                <label>Current Address:</label>
                <input type="text" name="current_address_line1" value="<?php echo $current_address_parts[0] ?? ''; ?>" required>
                <input type="text" name="current_address_line2" value="<?php echo $current_address_parts[1] ?? ''; ?>">
                <input type="text" name="current_city" value="<?php echo $current_address_parts[2] ?? ''; ?>" required>
                <select name="current_state" required>
                    <option value="Uttar Pradesh" <?php echo ($current_state == 'Uttar Pradesh') ? 'selected' : ''; ?>>Uttar Pradesh</option>
                    <option value="Bihar" <?php echo ($current_state == 'Bihar') ? 'selected' : ''; ?>>Bihar</option>
                    <option value="Delhi" <?php echo ($current_state == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                    <option value="Maharashtra" <?php echo ($current_state == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                    <option value="Other" <?php echo ($current_state == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>

                <label>Upload New Profile Picture (optional):</label>
                <input type="file" name="profile_picture">

                <button type="submit" name="update_profile">Update Profile</button>
            </div>
        </form>
    </div>

    <script>
        function addQualification() {
            const div = document.createElement('div');
            div.innerHTML = '<input type="text" name="qualifications[]" placeholder="Qualification" required>';
            document.getElementById('qualifications').appendChild(div);
        }

        function addExperience() {
            const div = document.createElement('div');
            div.innerHTML = '<input type="text" name="experiences[]" placeholder="Experience" required>';
            document.getElementById('experiences').appendChild(div);
        }
    </script>
</body>
</html>

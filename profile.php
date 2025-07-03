<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
include "header.php";
include "db.php";
echo "Session username: " . $_SESSION['username'] . "<br>";
// Optional: Redirect logged-in user to dashboard

$username = $_SESSION['username'];

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $newEmail = mysqli_real_escape_string($conn, $_POST['email']);

    // Update query
    $update = "UPDATE user SET username='$newUsername', email='$newEmail' WHERE LOWER(username) = LOWER('$username')";
    if (mysqli_query($conn, $update)) {
        $_SESSION['username'] = $newUsername; // update session
        $username = $newUsername; // update local variable
        $message = "Profile updated successfully.";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}

?>
<?php
// Fetch updated data
$query = "SELECT * FROM user WHERE LOWER(username) = LOWER('$username')";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>My Profile</title>
        <style>
            .profile-box {
                margin: auto;
                max-width: 400px;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
                font-family: Arial;
            }
            input[type="text"], input[type="email"] {
                width: 100%;
                padding: 8px;
                margin: 8px 0;
                box-sizing: border-box;
            }
            input[type="submit"] {
                padding: 10px 15px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .message {
                color: green;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="profile-box">
            <h2>Edit Profile</h2>
            <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
            <form method="POST">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <input type="submit" value="Update Profile">
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "User not found in database.";
}

include "footer.php";
?>

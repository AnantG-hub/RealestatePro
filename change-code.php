<?php
include "db.php";
session_start();

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newCode = $_POST['new_code'];
    mysqli_query($conn, "UPDATE settings SET setting_value = '$newCode' WHERE setting_key = 'delete_code'");
    echo "<script>alert('✅ Delete Code Updated!'); window.location.href='dashboard.php';</script>";
}
?>

<form method="POST">
    <label>New Delete Code:</label>
    <input type="text" name="new_code" required>
    <button type="submit">Update</button>
</form>
<?php
include "db.php";
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newCode = mysqli_real_escape_string($conn, $_POST['new_code']);
    mysqli_query($conn, "UPDATE settings SET setting_value = '$newCode' WHERE setting_key = 'delete_code'");
    echo "<script>alert('✅ Delete Code Updated!'); window.location.href='dashboard.php';</script>";
}
?>

<form method="POST" style="margin:50px;">
    <label>New Delete Code:</label>
    <input type="text" name="new_code" required>
    <button type="submit">Update</button>
</form>

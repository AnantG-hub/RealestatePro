<?php
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$delete_code = bin2hex(random_bytes(4)); // 8-digit random code
?>

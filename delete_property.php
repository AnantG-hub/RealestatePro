<?php
include "db.php";

if (isset($_GET['id']) && isset($_GET['code'])) {
    $id = $_GET['id'];
    $code = $_GET['code'];

    // Fetch code from DB
    $codeResult = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'delete_code'");
    $row = mysqli_fetch_assoc($codeResult);
    $actualCode = $row['setting_value'];

    if ($code === $actualCode) {
        // Delete image
        $imgQuery = mysqli_query($conn, "SELECT image FROM properties WHERE id = $id");
        $imgData = mysqli_fetch_assoc($imgQuery);
        $imgPath = "uploads/" . $imgData['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }

        // Delete property record
        mysqli_query($conn, "DELETE FROM properties WHERE id = $id");

        header("Location: feature.php?deleted=1");
        exit();
    } else {
        echo "<script>alert('❌ Wrong Delete Code'); window.location.href='feature.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('❗Missing ID or Code'); window.location.href='feature.php';</script>";
    exit();
}

?>

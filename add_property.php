<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

include "header.php";
include "db.php";

// Upload Directory
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $location = $_POST['map_link'] ?? '';
    $price = $_POST['price'] ?? '';
    $area = $_POST['area'] ?? '';
    $beds = $_POST['beds'] ?? '';
    $baths = $_POST['baths'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $video_link = $_POST['video_link'] ?? '';
    $gallery_link = $_POST['gallery_link'] ?? '';
    $map_link = $_POST['map_link'] ?? '';

    $plan_image = '';
    if (!empty($_FILES['plan_image']['name'])) {
        $plan_image = basename($_FILES['plan_image']['name']);
        move_uploaded_file($_FILES['plan_image']['tmp_name'], $uploadDir . $plan_image);
    }

    $main_image = '';
    if (!empty($_FILES['image']['name'])) {
        $main_image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $main_image);
    }

    $internal_images = [];
    for ($i = 1; $i <= 3; $i++) {
        $field = 'internal_image' . $i;
        if (!empty($_FILES[$field]['name'])) {
            $filename = basename($_FILES[$field]['name']);
            move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $filename);
            $internal_images[] = $filename;
        }
    }
    $internal_images_str = implode(',', $internal_images);

    $gallery_images_arr = [];
    if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['gallery_images']['name'][$key]);
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($tmp_name, $targetPath)) {
                $gallery_images_arr[] = $filename;
            }
        }
    }
    $gallery_images_str = implode(',', $gallery_images_arr);

    $query = "INSERT INTO properties 
        (title, location, price, area, beds, baths, type, image, plan_image, internal_images, gallery_images, description, video_link, gallery_link, map_link, created_at) 
        VALUES 
        ('$title', '$location', '$price', '$area', '$beds', '$baths', '$type', '$main_image', '$plan_image', '$internal_images_str', '$gallery_images_str', '$description', '$video_link', '$gallery_link', '$map_link', NOW())";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Property added successfully!'); window.location='feature.php';</script>";
    } else {
        die("Error inserting data: " . mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>

<!-- HTML FORM BELOW -->
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="nav2.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 700px;
    margin: 40px auto;
    background: #fff;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
    color: #333;
}
form label {
    display: block;
    margin: 15px 0 5px;
    font-weight: 600;
}
form input, form select, form textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    background: #fdfdfd;
}
form input[type="file"] {
    padding: 8px;
}
form button {
    margin-top: 20px;
    background-color: #007bff;
    color: white;
    padding: 12px;
    border: none;
    width: 100%;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}
form button:hover {
    background-color: #0056b3;
}
.custom-slider {
    display: flex;
    overflow-x: auto;
    gap: 12px;
    scroll-behavior: smooth;
    padding: 10px 0;
}
.slide-img {
    width: 180px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.slide-img:hover {
    transform: scale(1.05);
}
form textarea {
    resize: vertical;
}
</style>

<div class="container">
    <h2>Add New Property</h2>
   <form method="POST" enctype="multipart/form-data">
    <label>Property Title</label>
    <input type="text" name="title" required>

    <label>location</label>
    <input type="text" name="map_link" placeholder="https://maps.google.com/..." required>

    <label>Price (in ₹)</label>
    <input type="number" name="price" required>

    <label>Area (sq ft)</label>
    <input type="text" name="area" required>

    <label>Bedrooms</label>
    <input type="number" name="beds" required>

    <label>Bathrooms</label>
    <input type="number" name="baths" required>

    <label>Type</label>
    <select name="type" required>
        <option value="for rent">For Rent</option>
        <option value="for sell">For Sell</option>
    </select>

    <label>Description</label>
    <textarea name="description" rows="4" required></textarea>

    <label>Main Image</label>
    <input type="file" name="image" accept="image/*" required>

    <label>Plan Image</label>
    <input type="file" name="plan_image" accept="image/*" required>

    <label>Internal Image 1</label>
    <input type="file" name="internal_image1" accept="image/*">

    <label>Internal Image 2</label>
    <input type="file" name="internal_image2" accept="image/*">

    <label>Internal Image 3</label>
    <input type="file" name="internal_image3" accept="image/*">

    <label>Video Link</label>
    <input type="text" name="video_link" placeholder="https://youtube.com/..." required>

    <label>Gallery Images (multiple)</label>
    <input type="file" name="gallery_images[]" multiple accept="image/*">

    <button type="submit">Add Property</button>
</form>
</div>
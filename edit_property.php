<?php
session_start();
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

include "header.php";
include "db.php";

if (!isset($_GET['id']) || !isset($_GET['code'])) {
    echo "<script>alert('❗Missing Edit Code'); window.location.href='feature.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$input_code = trim($_GET['code']);

// Database से actual code check करो
$codeResult = mysqli_query($conn, "SELECT setting_value FROM settings WHERE setting_key = 'edit_code'");
$codeRow = mysqli_fetch_assoc($codeResult);
$actual_code = $codeRow['setting_value'];

if ($input_code !== $actual_code) {
    echo "<script>alert('❌ Invalid Edit Code'); window.location.href='feature.php';</script>";
    exit();
}
// Fetch property details

$id = $_GET['id'];
$query = "SELECT * FROM properties WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Property not found.");
}

$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $location = $_POST['location'] ?? '';
    $price = $_POST['price'] ?? '';
    $area = $_POST['area'] ?? '';
    $beds = $_POST['beds'] ?? '';
    $baths = $_POST['baths'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    $video_link = $_POST['video_link'] ?? '';

    $uploadDir = "uploads/";
    // Internal Images Upload
$internal_images_array = [];
if (!empty($_FILES['internal_images']['name'][0])) {
    foreach ($_FILES['internal_images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['internal_images']['name'][$key]);
        $target_path = $uploadDir . $file_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $internal_images_array[] = $file_name;
        }
    }
}
$internal_images = implode(',', $internal_images_array);
if (empty($internal_images)) {
    $internal_images = $row['internal_images']; // fallback to existing
}

// Gallery Images Upload
$gallery_images_array = [];
if (!empty($_FILES['gallery_images']['name'][0])) {
    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['gallery_images']['name'][$key]);
        $target_path = $uploadDir . $file_name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $gallery_images_array[] = $file_name;
        }
    }
}
$gallery_images = implode(',', $gallery_images_array);
if (empty($gallery_images)) {
    $gallery_images = $row['gallery_images']; // fallback to existing
}


    $image = $row['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $image);
    }

    $plan_image = $row['plan_image'];
    if (!empty($_FILES['plan_image']['name'])) {
        $plan_image = basename($_FILES['plan_image']['name']);
        move_uploaded_file($_FILES['plan_image']['tmp_name'], $uploadDir . $plan_image);
    }
$query = "UPDATE properties SET
            title = '$title',
            location = '$location',
            price = '$price',
            area = '$area',
            beds = '$beds',
            baths = '$baths',
            type = '$type',
            image = '$image',
            plan_image = '$plan_image',
            description = '$description',
            video_link = '$video_link',
            internal_images = '$internal_images',
            gallery_images = '$gallery_images'
          WHERE id = $id";


    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Property updated successfully!'); window.location='feature.php';</script>";
    } else {
        echo "<script>alert('Error updating property.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Property</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 700px;
            margin-top: 40px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Edit Property</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $row['title']; ?>" required>

        <label>Location</label>
        <input type="text" name="location" class="form-control" value="<?php echo $row['location']; ?>" required>

        <label>Price</label>
        <input type="number" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>

        <label>Area</label>
        <input type="text" name="area" class="form-control" value="<?php echo $row['area']; ?>" required>

        <label>Beds</label>
        <input type="number" name="beds" class="form-control" value="<?php echo $row['beds']; ?>" required>

        <label>Baths</label>
        <input type="number" name="baths" class="form-control" value="<?php echo $row['baths']; ?>" required>

        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="for rent" <?php if ($row['type'] == 'for rent') echo 'selected'; ?>>For Rent</option>
            <option value="for sell" <?php if ($row['type'] == 'for sell') echo 'selected'; ?>>For Sell</option>
        </select>

        <label>Description</label>
        <textarea name="description" class="form-control" required><?php echo $row['description']; ?></textarea>

        <label>Video Link</label>
        <input type="text" name="video_link" class="form-control" value="<?php echo $row['video_link']; ?>">

        <label>Main Image</label>
        <input type="file" name="image" class="form-control">

        <label>Plan Image</label>
        <input type="file" name="plan_image" class="form-control">

        <label>Internal Images</label>
<input type="file" name="internal_images[]" class="form-control" multiple>

<label>Gallery Images</label>
<input type="file" name="gallery_images[]" class="form-control" multiple>


        <button type="submit" class="btn btn-primary mt-3">Update Property</button>
    </form>
</div>
</body>
</html>

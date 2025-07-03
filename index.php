<?php 
session_start(); 
include "db.php";

// Check if user is not logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Optional: Redirect logged-in user to dashboard
// header("Location: feature.php"); exit();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        h1 {
            font-family: 'Courier New', Courier, monospace;
            font-size: 4.6em;
            text-align: center;
            color: red;
            font-variant: small-caps;
            margin-top: -30px;
        }
        .home {
            margin: 20px 0;
            padding: 20px;
            background-color:seashell;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .center {
            max-width: 800px;
            margin: auto;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        .box {
            margin-bottom: 15px;
        }

        .box p {
            margin: 0 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: cornsilk;
        }

        .content {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            text-align: center;
            background-color: aqua;
        }

        .logo img {
            width: 150px;
        }
    </style>
</head>
<body>

<div class="logo">
    <img src="logoo.png" alt="Logo"><h1>Welcome To Real Estate World</h1>
</div>



<div class="content">
    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success">
            <h3><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></h3>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['username'])) : ?>
        <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong> |
        <a href="profile.php">My Profile</a> |
        <a href="index.php?logout='1'">Logout</a></p>
    <?php endif ?>
</div>

<div class="home">
    <section class="center">
        <form action="feature.php" method="get">
            <h3>Find Your Perfect Home</h3>

            <div class="box">
                <p>Enter Location <span>*</span></p>
                <input type="text" name="location" required placeholder="Enter city name">
            </div>

            <div class="box">
                <p>Property Type <span>*</span></p>
                <select name="type" required>
                    <option value="flat">Flat</option>
                    <option value="house">House</option>
                    <option value="shop">Shop</option>
                </select>
            </div>

            <div class="box">
                <p>Offer Type <span>*</span></p>
                <select name="offer" required>
                    <option value="for sell">For Sell</option>
                    <option value="for rent">For Rent</option>
                </select>
            </div>

            <div class="box">
                <p>Min Price <span>*</span></p>
                <input type="number" name="min_price" required placeholder="3000">
            </div>

            <div class="box">
                <p>Max Price <span>*</span></p>
                <input type="number" name="max_price" required placeholder="1000000">
            </div>

            <input type="submit" value="Search Property" class="btn">
        </form>
    </section>
</div>

</body>
</html>

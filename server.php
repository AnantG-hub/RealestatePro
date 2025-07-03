<?php
session_start();
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost','root','','project');
// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
 $username = strtolower(mysqli_real_escape_string($db, $_POST['username']));
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  $user_check_query = "SELECT * FROM user WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO user (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: login.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = strtolower(mysqli_real_escape_string($db, $_POST['username']));
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
    array_push($errors, "Username is required");
  }
  if (empty($password)) {
    array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
    $password = md5($password); // hashed password
    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $results = mysqli_query($db, $query);

    if (mysqli_num_rows($results) == 1) {
      $row = mysqli_fetch_assoc($results); // ✅ Fetch the result row

      $_SESSION['username'] = $row['username']; // Set session
    $_SESSION['role'] = $user['role']; // database से fetch करके // 👈 ADD THIS LINE
      $_SESSION['email'] = $row['email'];       // Set session
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
      exit();
    } else {
      array_push($errors, "Wrong username/password combination");
    }
  }
}
// Logout user
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['username']);
  header("location: login.php");
  exit();
}
?>
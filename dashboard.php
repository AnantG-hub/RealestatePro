<?php
include "db.php";
include "header.php";

$user = $_SESSION['username'];

// Get user ID
$userQuery = "SELECT id FROM users WHERE username='$user'";
$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    $userData = mysqli_fetch_assoc($userResult);
    $user_id = $userData['id'];

    // Get properties by user
    $query = "SELECT * FROM properties WHERE user_id='$user_id'";
    $result = mysqli_query($conn, $query);

    $propertyCount = mysqli_num_rows($result);
} else {
    $propertyCount = 0;
}
?>

<!-- ✅ Theme Stylesheet -->
<link id="themeStylesheet" rel="stylesheet" href="css/light-theme.css">

<div class="container mt-5">
  <h2 class="mb-4">Dashboard</h2>


  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card shadow">
        <div class="card-body text-center">
          <h5 class="card-title">Total Properties</h5>
          <p class="fs-2"><?= $propertyCount ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Theme Toggle Script -->
<script>
  function toggleTheme() {
    const themeLink = document.getElementById('themeStylesheet');
    const currentTheme = themeLink.getAttribute('href');

    if (currentTheme.includes('light-theme.css')) {
      themeLink.setAttribute('href', 'css/dark-theme.css');
      localStorage.setItem('theme', 'dark');
    } else {
      themeLink.setAttribute('href', 'css/light-theme.css');
      localStorage.setItem('theme', 'light');
    }
  }

  // ✅ Load theme on startup
  window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.getElementById('themeStylesheet').setAttribute('href', 'css/' + savedTheme + '-theme.css');
  });
</script>

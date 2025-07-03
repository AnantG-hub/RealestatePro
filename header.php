<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RealEstatePro</title>

  <!-- ✅ Add theme stylesheet with full relative path -->
  <link id="themeStylesheet" rel="stylesheet" href="css/light-theme.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="nav2.css">

  <style>
    .navbar {
      background: linear-gradient(90deg, #2c3e50, #3498db);
    }
    .navbar-brand {
      font-weight: bold;
      color: white !important;
    }
    .nav-link {
      color: white !important;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .nav-link:hover {
      color: #ffd700 !important;
      transform: scale(1.05);
    }
    .dropdown-menu {
      background-color: #f8f9fa;
    }
    .dropdown-item:hover {
      background-color: #e9ecef;
      color: #007bff;
    }
  </style>
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><i class="fas fa-building me-2"></i>RealEstateManagemant</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i>Home</a></li>
        <li class="nav-item"><a class="nav-link" href="feature.php"><i class="fas fa-bookmark me-1"></i>Feature</a></li>
        <li class="nav-item"><a class="nav-link" href="#Agent"><i class="fas fa-users me-1"></i>Agents</a></li>
        <li class="nav-item"><a class="nav-link" href="#Services"><i class="fas fa-cogs me-1"></i>Services</a></li>
        <li class="nav-item"><a class="nav-link" href="#reviews"><i class="fas fa-address-card me-1"></i>Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact"><i class="fas fa-envelope me-1"></i>Contact</a></li>

        <!-- Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
            <i class="fas fa-user me-1"></i>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-badge me-1"></i>My Profile</a></li>
            <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-chart-line me-1"></i>Dashboard</a></li>
            <!-- ✅ FIXED: Proper list item for theme toggle -->
           <li><a class="dropdown-item" href="#" onclick="toggleTheme()">
  <i class="fas fa-moon me-1"></i> Toggle Theme
</a></li>
    
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="?logout=1"><i class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar End -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

  window.onload = () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.getElementById('themeStylesheet').setAttribute('href', 'css/' + savedTheme + '-theme.css');
  };
</script>
</body>
</html>

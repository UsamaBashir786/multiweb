<?php session_start();
include '../config/db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f9;
      overflow-x: hidden;
    }

    /* Sidebar Styles */
    .sidebar {
      background-color: #212529;
      min-height: 100vh;
      padding: 0;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      transition: all 0.3s;
      z-index: 1030;
    }

    .sidebar-header {
      padding: 15px 20px;
      border-bottom: 1px solid #2c3034;
    }

    .sidebar-header h3 {
      color: #fff;
      margin: 0;
      font-size: 1.3rem;
    }

    .sidebar-nav {
      padding: 0;
      list-style: none;
      margin: 0;
    }

    .sidebar-nav .nav-item {
      margin: 0;
    }

    .sidebar-nav .nav-link {
      color: #ced4da;
      padding: 0.8rem 1.25rem;
      display: flex;
      align-items: center;
      transition: 0.3s;
      border-left: 3px solid transparent;
    }

    .sidebar-nav .nav-link:hover,
    .sidebar-nav .nav-link.active {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.05);
      border-left: 3px solid #6c757d;
    }

    .sidebar-nav .nav-link i,
    .sidebar-nav .nav-link svg {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar-nav .nav-link .dropdown-toggle {
      margin-left: auto;
    }

    .sidebar-nav .dropdown-menu {
      padding: 0;
      background-color: #2c3034;
      border: none;
      border-radius: 0;
      margin-top: 0;
      box-shadow: none;
      position: static !important;
      transform: none !important;
      width: 100%;
    }

    .sidebar-nav .dropdown-item {
      color: #ced4da;
      padding: 0.8rem 1.25rem 0.8rem 2.6rem;
      transition: 0.3s;
      font-size: 0.95rem;
      border-left: 3px solid transparent;
    }

    .sidebar-nav .dropdown-item:hover {
      color: #fff;
      background-color: #383e45;
      border-left: 3px solid #6c757d;
    }

    .logout-link {
      color: #dc3545 !important;
      position: absolute;
      bottom: 15px;
      width: 100%;
      padding: 0.8rem 1.25rem;
      display: flex;
      align-items: center;
    }

    .logout-link:hover {
      color: #fff !important;
      background-color: rgba(220, 53, 69, 0.1);
    }

    /* Main Content Styles */
    .main-content {
      margin-left: 250px;
      padding: 20px;
      transition: all 0.3s;
    }

    .welcome-section {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
      background-color: #fff;
      border-radius: 5px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .stat-card i {
      font-size: 2rem;
      margin-bottom: 15px;
      opacity: 0.7;
    }

    .stat-card h3 {
      font-size: 1.75rem;
      margin-bottom: 5px;
      font-weight: 600;
    }

    /* Mobile Toggle Button */
    .sidebar-toggle {
      display: none;
      background-color: #212529;
      color: #fff;
      border: none;
      border-radius: 3px;
      padding: 7px 10px;
      position: fixed;
      top: 15px;
      left: 15px;
      z-index: 1031;
    }

    /* Overlay for mobile */
    .sidebar-overlay {
      display: none;
      position: fixed;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1029;
      top: 0;
      left: 0;
    }

    /* Responsive styles */
    @media (max-width: 992px) {
      .sidebar {
        margin-left: -250px;
      }

      .sidebar.show {
        margin-left: 0;
      }

      .main-content {
        margin-left: 0;
        padding-top: 60px;
      }

      .sidebar-toggle {
        display: block;
      }

      .sidebar-overlay.show {
        display: block;
      }
    }
  </style>
</head>

<body>
  <!-- Mobile Sidebar Toggle -->
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list"></i>
  </button>

  <!-- Sidebar Overlay -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h3>Admin Panel</h3>
    </div>

    <ul class="sidebar-nav">
      <li class="nav-item">
        <a href="index.php" class="nav-link active">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-bs-toggle="dropdown">
          <i class="bi bi-tag"></i>
          <span>Categories</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="add-category.php">Add Story Category</a>
          <!-- <a class="dropdown-item" href="add_article_category.php">Add Article Category</a> -->
        </div>
      </li>

      <li class="nav-item dropdown">
        <a href="#" class="nav-link " data-bs-toggle="dropdown">
          <i class="bi bi-book"></i>
          <span>Stories</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="add_story.php">Add Story</a>
        </div>
      </li>

      <li class="nav-item">
        <a href="users.php" class="nav-link">
          <i class="bi bi-people"></i>
          <span>Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="settings.php" class="nav-link">
          <i class="bi bi-gear"></i>
          <span>Settings</span>
        </a>
      </li>
    </ul>

    <a href="logout.php" class="nav-link logout-link">
      <i class="bi bi-box-arrow-right"></i>
      <span>Logout</span>
    </a>
  </nav>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col">
        <h2 class="mb-0">Dashboard</h2>
      </div>
    </div>

    <div class="welcome-section">
      <h4>Welcome back, Admin</h4>
      <p class="text-muted mb-0">Here's what's happening with your system today.</p>
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="stat-card">
          <i class="bi bi-book text-primary"></i>
          <h3>254</h3>
          <p class="text-muted mb-0">Total Books</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <i class="bi bi-people text-success"></i>
          <h3>1,120</h3>
          <p class="text-muted mb-0">Total Users</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <i class="bi bi-cart3 text-warning"></i>
          <h3>56</h3>
          <p class="text-muted mb-0">New Orders</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <i class="bi bi-currency-dollar text-danger"></i>
          <h3>$3,254</h3>
          <p class="text-muted mb-0">Total Revenue</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar Toggle for Mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('show');
      document.getElementById('sidebarOverlay').classList.toggle('show');
    });

    // Close sidebar when clicking on overlay
    document.getElementById('sidebarOverlay').addEventListener('click', function() {
      document.getElementById('sidebar').classList.remove('show');
      this.classList.remove('show');
    });

    // Handle mobile responsiveness
    function checkWidth() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebarOverlay');

      if (window.innerWidth <= 992) {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
      }
    }

    // Check width on load and resize
    window.addEventListener('resize', checkWidth);
    window.addEventListener('load', checkWidth);

    // Highlight current page link
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');

    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (href && currentPath.includes(href) && href !== '#') {
        link.classList.add('active');
      }
    });

    // Ensure dropdowns stay open when dropdown items are active
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
      const href = item.getAttribute('href');
      if (href && currentPath.includes(href)) {
        // Find parent dropdown and keep it open
        const dropdownParent = item.closest('.dropdown');
        if (dropdownParent) {
          const dropdownMenu = dropdownParent.querySelector('.dropdown-menu');
          const dropdownToggle = dropdownParent.querySelector('.dropdown-toggle');

          if (dropdownMenu) dropdownMenu.classList.add('show');
          if (dropdownToggle) dropdownToggle.classList.add('active');

          // Highlight the dropdown item
          item.classList.add('active');
        }
      }
    });
  </script>
</body>

</html>
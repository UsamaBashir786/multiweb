<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .sidebar {
      background-color: #212529;
      min-height: 100vh;
      padding: 20px 0;
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      transition: all 0.3s;
      z-index: 1000;
    }

    .sidebar-collapsed {
      width: 70px;
    }

    .sidebar-header {
      padding: 0 20px 20px;
      border-bottom: 1px solid #2c3034;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .sidebar-collapsed .sidebar-header h3 {
      display: none;
    }

    .toggle-btn {
      background: none;
      border: none;
      color: #ced4da;
      cursor: pointer;
      font-size: 1.2rem;
    }

    .toggle-btn:hover {
      color: #fff;
    }

    .sidebar-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .sidebar-menu li {
      margin-bottom: 5px;
    }

    .sidebar-menu a,
    .sidebar-menu .dropdown-toggle {
      color: #ced4da;
      display: block;
      padding: 10px 20px;
      text-decoration: none;
      transition: all 0.3s;
      background: none;
      border: none;
      text-align: left;
      width: 100%;
      position: relative;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .sidebar-menu a i,
    .sidebar-menu .dropdown-toggle i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar-collapsed .sidebar-menu a span,
    .sidebar-collapsed .sidebar-menu .dropdown-toggle span {
      display: none;
    }

    .sidebar-menu a:hover,
    .sidebar-menu .dropdown-toggle:hover {
      background-color: #2c3034;
      color: #fff;
    }

    .sidebar-menu a.active {
      background-color: #0d6efd;
      color: #fff;
    }

    .sidebar-menu .dropdown-toggle {
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .sidebar-menu .dropdown-toggle i.dropdown-icon {
      transition: transform 0.3s;
    }

    .sidebar-menu .dropdown-toggle.active i.dropdown-icon {
      transform: rotate(180deg);
    }

    .sidebar-menu .custom-dropdown-menu {
      background-color: #2c3034;
      border: none;
      display: none;
      padding: 0;
      margin: 0;
      width: 100%;
      list-style: none;
    }

    .sidebar-menu .custom-dropdown-menu.show {
      display: block;
    }

    .sidebar-menu .custom-dropdown-menu a {
      color: #ced4da;
      padding: 10px 20px 10px 50px;
      border-top: 1px solid #1a1e21;
    }

    .sidebar-collapsed .sidebar-menu .custom-dropdown-menu {
      position: absolute;
      left: 70px;
      top: 0;
      width: 200px;
      z-index: 1001;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-collapsed .sidebar-menu .custom-dropdown-menu a {
      padding-left: 20px;
    }

    .sidebar-menu .custom-dropdown-menu a:hover {
      background-color: #1a1e21;
      color: #fff;
    }

    .main-content {
      margin-left: 260px;
      padding: 20px;
      transition: all 0.3s;
    }

    .main-content-expanded {
      margin-left: 70px;
    }

    .logout-container {
      position: absolute;
      bottom: 20px;
      left: 0;
      width: 100%;
      padding: 0 20px;
    }

    .logout-btn {
      color: #dc3545;
      width: 100%;
      padding: 10px 20px;
      text-align: left;
      border: none;
      background: none;
      display: flex;
      align-items: center;
      transition: all 0.3s;
    }

    .logout-btn i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .sidebar-collapsed .logout-btn span {
      display: none;
    }

    .logout-btn:hover {
      color: #fff;
      background-color: #dc3545;
    }

    /* Add tooltip for collapsed sidebar */
    .sidebar-collapsed .menu-tooltip {
      position: relative;
    }

    .sidebar-collapsed .menu-tooltip:hover::after {
      content: attr(data-title);
      position: absolute;
      left: 70px;
      top: 50%;
      transform: translateY(-50%);
      background-color: #000;
      color: #fff;
      padding: 5px 10px;
      border-radius: 4px;
      white-space: nowrap;
      z-index: 1002;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .sidebar {
        width: 70px;
      }

      .sidebar-header h3 {
        display: none;
      }

      .sidebar-menu a span,
      .sidebar-menu .dropdown-toggle span,
      .logout-btn span {
        display: none;
      }

      .main-content {
        margin-left: 70px;
      }

      .sidebar-menu .custom-dropdown-menu {
        position: absolute;
        left: 70px;
        top: 0;
        width: 200px;
        z-index: 1001;
      }

      .sidebar-menu .custom-dropdown-menu a {
        padding-left: 20px;
      }

      .sidebar.sidebar-expanded {
        width: 260px;
      }

      .sidebar.sidebar-expanded .sidebar-header h3 {
        display: block;
      }

      .sidebar.sidebar-expanded .sidebar-menu a span,
      .sidebar.sidebar-expanded .sidebar-menu .dropdown-toggle span,
      .sidebar.sidebar-expanded .logout-btn span {
        display: inline;
      }

      .sidebar.sidebar-expanded .sidebar-menu .custom-dropdown-menu {
        position: static;
        width: 100%;
      }

      .sidebar.sidebar-expanded .sidebar-menu .custom-dropdown-menu a {
        padding-left: 50px;
      }
    }
  </style>
</head>

<body>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h3 class="text-white m-0">Admin Panel</h3>
      <button class="toggle-btn" id="sidebarToggle">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <ul class="sidebar-menu">
      <li>
        <a href="#dashboard" class="active menu-tooltip" data-title="Dashboard">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="dropdown">
        <div class="dropdown-toggle menu-tooltip" data-title="Categories">
          <div>
            <i class="fas fa-folder"></i>
            <span>Categories</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>
        <ul class="custom-dropdown-menu">
          <li>
            <a href="#addStoryCategory" class="dropdown-item">
              <i class="fas fa-plus"></i>
              <span>Add Story Category</span>
            </a>
          </li>
          <li>
            <a href="#addArticleCategory" class="dropdown-item">
              <i class="fas fa-plus"></i>
              <span>Add Article Category</span>
            </a>
          </li>
          <li>
            <a href="#viewCategories" class="dropdown-item">
              <i class="fas fa-list"></i>
              <span>View All Categories</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="dropdown">
        <div class="dropdown-toggle menu-tooltip" data-title="Stories">
          <div>
            <i class="fas fa-book"></i>
            <span>Stories</span>
          </div>
          <i class="fas fa-chevron-down dropdown-icon"></i>
        </div>
        <ul class="custom-dropdown-menu">
          <li>
            <a href="#addStory" class="dropdown-item">
              <i class="fas fa-plus"></i>
              <span>Add Story</span>
            </a>
          </li>
          <li>
            <a href="#viewStories" class="dropdown-item">
              <i class="fas fa-list"></i>
              <span>View All Stories</span>
            </a>
          </li>
          <li>
            <a href="#featuredStories" class="dropdown-item">
              <i class="fas fa-star"></i>
              <span>Featured Stories</span>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="#users" class="menu-tooltip" data-title="Users">
          <i class="fas fa-users"></i>
          <span>Users</span>
        </a>
      </li>
      <li>
        <a href="#comments" class="menu-tooltip" data-title="Comments">
          <i class="fas fa-comments"></i>
          <span>Comments</span>
        </a>
      </li>
      <li>
        <a href="#statistics" class="menu-tooltip" data-title="Statistics">
          <i class="fas fa-chart-bar"></i>
          <span>Statistics</span>
        </a>
      </li>
      <li>
        <a href="#settings" class="menu-tooltip" data-title="Settings">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a>
      </li>
    </ul>

    <div class="logout-container">
      <a href="logout.php" class="logout-btn menu-tooltip" data-title="Logout">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>

  <div class="main-content" id="mainContent">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>Dashboard Overview</h4>
            </div>
            <div class="card-body">
              <p>Welcome to the Admin Panel. Select an option from the sidebar to get started.</p>
              <div class="row mt-4">
                <div class="col-md-3">
                  <div class="card bg-primary text-white">
                    <div class="card-body">
                      <h5>Total Stories</h5>
                      <h2>124</h2>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card bg-success text-white">
                    <div class="card-body">
                      <h5>Total Users</h5>
                      <h2>1,458</h2>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card bg-warning text-dark">
                    <div class="card-body">
                      <h5>Comments</h5>
                      <h2>3,721</h2>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card bg-info text-white">
                    <div class="card-body">
                      <h5>Categories</h5>
                      <h2>17</h2>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('sidebar-collapsed');
      mainContent.classList.toggle('main-content-expanded');
    });

    // Handle sidebar menu clicks
    const menuLinks = document.querySelectorAll('.sidebar-menu > li > a:not(.dropdown-item)');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    // Dropdown functionality
    dropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        e.stopPropagation();

        // Toggle dropdown menu
        const dropdownMenu = this.nextElementSibling;
        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');

        // If sidebar is collapsed and this isn't already active
        if (isCollapsed && !this.classList.contains('active')) {
          // Close all dropdowns first
          dropdownToggles.forEach(otherToggle => {
            const otherDropdownMenu = otherToggle.nextElementSibling;
            otherDropdownMenu.classList.remove('show');
            otherToggle.classList.remove('active');
          });

          // Then open this one
          dropdownMenu.classList.add('show');
          this.classList.add('active');
        } else {
          // Normal toggle behavior
          dropdownMenu.classList.toggle('show');
          this.classList.toggle('active');

          // Close other dropdowns in normal mode
          if (!isCollapsed) {
            dropdownToggles.forEach(otherToggle => {
              if (otherToggle !== this) {
                const otherDropdownMenu = otherToggle.nextElementSibling;
                otherDropdownMenu.classList.remove('show');
                otherToggle.classList.remove('active');
              }
            });
          }
        }
      });
    });

    // Handle sidebar menu links
    menuLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        // Remove active class from all links
        menuLinks.forEach(item => {
          item.classList.remove('active');
        });

        // Add active class to clicked link
        this.classList.add('active');
      });
    });

    // Handle dropdown items click
    const dropdownItems = document.querySelectorAll('.custom-dropdown-menu a');

    dropdownItems.forEach(item => {
      item.addEventListener('click', function(e) {
        // Remove active class from all menu links
        menuLinks.forEach(link => {
          link.classList.remove('active');
        });

        // Close dropdown when item is clicked
        const dropdown = this.closest('.dropdown');
        if (dropdown) {
          const dropdownToggle = dropdown.querySelector('.dropdown-toggle');

          // Don't close dropdowns in collapsed mode to improve UX
          if (!sidebar.classList.contains('sidebar-collapsed')) {
            const dropdownMenu = this.closest('.custom-dropdown-menu');
            dropdownMenu.classList.remove('show');
            dropdownToggle.classList.remove('active');
          }
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.dropdown') && !sidebar.classList.contains('sidebar-collapsed')) {
        const dropdownMenus = document.querySelectorAll('.custom-dropdown-menu');
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        dropdownMenus.forEach(menu => {
          menu.classList.remove('show');
        });

        dropdownToggles.forEach(toggle => {
          toggle.classList.remove('active');
        });
      }
    });

    // Handle hover dropdown for collapsed sidebar
    if (window.matchMedia('(min-width: 769px)').matches) {
      const dropdownItems = document.querySelectorAll('.dropdown');

      dropdownItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          if (sidebar.classList.contains('sidebar-collapsed')) {
            const dropdownToggle = this.querySelector('.dropdown-toggle');
            const dropdownMenu = this.querySelector('.custom-dropdown-menu');

            // Position the dropdown menu relative to the toggle
            const toggleRect = dropdownToggle.getBoundingClientRect();
            dropdownMenu.style.top = toggleRect.top + 'px';

            // Show the dropdown
            dropdownMenu.classList.add('show');
            dropdownToggle.classList.add('active');
          }
        });

        item.addEventListener('mouseleave', function() {
          if (sidebar.classList.contains('sidebar-collapsed')) {
            const dropdownToggle = this.querySelector('.dropdown-toggle');
            const dropdownMenu = this.querySelector('.custom-dropdown-menu');

            // Hide the dropdown
            dropdownMenu.classList.remove('show');
            dropdownToggle.classList.remove('active');
          }
        });
      });
    }

    // Responsive behavior for mobile
    function checkWindowSize() {
      if (window.innerWidth <= 768) {
        sidebar.classList.add('sidebar-collapsed');
        mainContent.classList.add('main-content-expanded');
      }
    }

    // Initial check
    checkWindowSize();

    // Check on resize
    window.addEventListener('resize', checkWindowSize);
  </script>

</body>

</html>
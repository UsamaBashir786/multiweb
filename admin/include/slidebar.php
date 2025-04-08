
<style>
  body,
  html {
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    height: 100%;
  }

  .sidebar {
    background-color: #212529;
    height: 100vh;
    padding: 20px 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    transition: all 0.3s;
    z-index: 1000;
    overflow-y: auto;
  }

  .sidebar.collapsed {
    margin-left: -250px;
  }

  .sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid #2c3034;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .sidebar-menu {
    list-style: none;
    padding: 0;
    margin-bottom: 80px;
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
  }

  .sidebar-menu a:hover,
  .sidebar-menu .dropdown-toggle:hover {
    background-color: #2c3034;
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

  .sidebar-menu .custom-dropdown-menu {
    background-color: #2c3034;
    border: none;
    display: none;
    padding: 0;
    margin: 0;
    width: 100%;
  }

  .sidebar-menu .custom-dropdown-menu.show {
    display: block;
  }

  .sidebar-menu .custom-dropdown-menu a {
    color: #ced4da;
    padding: 10px 20px 10px 40px;
    border-top: 1px solid #1a1e21;
  }

  .sidebar-menu .custom-dropdown-menu a:hover {
    background-color: #1a1e21;
    color: #fff;
  }

  .main-content {
    padding: 20px;
    margin-left: 250px;
    transition: all 0.3s;
  }

  .main-content.expanded {
    margin-left: 0;
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
    position: fixed;
    bottom: 20px;
    left: 0;
  }

  .logout-btn:hover {
    color: #fff;
    background-color: #dc3545;
  }

  /* Close button styles */
  .sidebar-toggle {
    background: none;
    border: none;
    color: #ced4da;
    cursor: pointer;
    padding: 0;
    display: block;
  }

  .sidebar-toggle:hover {
    color: #fff;
  }

  .mobile-toggle {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 999;
    background: #212529;
    color: #ced4da;
    border: none;
    border-radius: 4px;
    padding: 10px;
    display: none;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .mobile-toggle:hover {
    color: #fff;
  }
.targeting-btn{
  display: none;
}
  /* Responsive styles */
  @media (max-width: 768px) {
    .targeting-btn{
      display: block;
    }
    .sidebar {
      margin-left: -250px;
    }

    .sidebar.active {
      margin-left: 0;
    }

    .main-content {
      margin-left: 0;
    }

    .mobile-toggle {
      display: block;
    }

    .content-overlay {
      display: none;
      position: fixed;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      z-index: 999;
      top: 0;
      left: 0;
    }

    .content-overlay.active {
      display: block;
    }
  }
</style>
<button class="mobile-toggle" id="sidebarMobileToggle" >
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="3" y1="12" x2="21" y2="12"></line>
    <line x1="3" y1="6" x2="21" y2="6"></line>
    <line x1="3" y1="18" x2="21" y2="18"></line>
  </svg>
</button>
<div class="content-overlay" id="contentOverlay"></div>
<div class="col-md-2 sidebar" id="sidebar">
  <div class="sidebar-header">
    <h3 class="text-white m-0">Admin Panel</h3>
    <button class="targeting-btn sidebar-toggle" id="sidebarToggle" class="d-block d-lg-none d-md-none d-sm-block d-xs-block">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
  </div>

  <ul class="sidebar-menu">
    <li>
      <a href="#dashboard" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
          <rect x="3" y="3" width="7" height="7"></rect>
          <rect x="14" y="3" width="7" height="7"></rect>
          <rect x="14" y="14" width="7" height="7"></rect>
          <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        Dashboard
      </a>
    </li>
    <li class="dropdown">
      <div class="dropdown-toggle">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
            <polyline points="9 11 12 14 22 4"></polyline>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
          </svg>
          Categories
        </div>
        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-icon">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg> -->
      </div>
      <div class="custom-dropdown-menu">
        <a href="#addStoryCategory" class="dropdown-item">Add Story Category</a>
        <a href="#addArticleCategory" class="dropdown-item">Add Article Category</a>
      </div>
    </li>
    <li class="dropdown">
      <div class="dropdown-toggle">
        <div>
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
          </svg>
          Stories
        </div>
        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dropdown-icon">
          <polyline points="6 9 12 15 18 9"></polyline>
        </svg> -->
      </div>
      <div class="custom-dropdown-menu">
        <a href="#addStoryCategory" class="dropdown-item">Add Story</a>
        <!-- <a href="#addArticleCategory" class="dropdown-item">Add Article Category</a> -->
      </div>
    </li>
    <li>
      <a href="#users">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
          <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
          <circle cx="12" cy="7" r="4"></circle>
        </svg>
        Users
      </a>
    </li>
    <li>
      <a href="#settings">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
          <circle cx="12" cy="12" r="3"></circle>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
        </svg>
        Settings
      </a>
    </li>
  </ul>

  <a href="logout.php" class="logout-btn">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;">
      <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
      <polyline points="16 17 21 12 16 7"></polyline>
      <line x1="21" y1="12" x2="9" y2="12"></line>
    </svg>
    Logout
  </a>
</div>

<script>
  // Get DOM elements
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarMobileToggle = document.getElementById('sidebarMobileToggle');
  const contentOverlay = document.getElementById('contentOverlay');
  const menuLinks = document.querySelectorAll('.sidebar-menu > li > a:not(.dropdown-item)');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

  // Toggle sidebar on all screen sizes
  sidebarToggle.addEventListener('click', function() {
    // On desktop
    if (window.innerWidth > 768) {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    }
    // On mobile
    else {
      sidebar.classList.remove('active');
      contentOverlay.classList.remove('active');
    }
  });

  // Toggle sidebar on mobile
  sidebarMobileToggle.addEventListener('click', function() {
    sidebar.classList.add('active');
    contentOverlay.classList.add('active');
  });

  // Close sidebar when clicking on the overlay (mobile)
  contentOverlay.addEventListener('click', function() {
    sidebar.classList.remove('active');
    contentOverlay.classList.remove('active');
  });

  // Dropdown functionality
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      // Toggle dropdown menu
      const dropdownMenu = this.nextElementSibling;
      dropdownMenu.classList.toggle('show');

      // Toggle active class on dropdown toggle
      this.classList.toggle('active');

      // Rotate dropdown icon
      const dropdownIcon = this.querySelector('.dropdown-icon');
      if (this.classList.contains('active')) {
        dropdownIcon.style.transform = 'rotate(180deg)';
      } else {
        dropdownIcon.style.transform = 'rotate(0)';
      }

      // Close other dropdowns
      dropdownToggles.forEach(otherToggle => {
        if (otherToggle !== this) {
          const otherDropdownMenu = otherToggle.nextElementSibling;
          otherDropdownMenu.classList.remove('show');
          otherToggle.classList.remove('active');
          const otherDropdownIcon = otherToggle.querySelector('.dropdown-icon');
          otherDropdownIcon.style.transform = 'rotate(0)';
        }
      });
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

      // Close sidebar on mobile when clicking a link
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('active');
        contentOverlay.classList.remove('active');
      }
    });
  });

  // Handle dropdown items click
  const dropdownItems = document.querySelectorAll('.custom-dropdown-menu a');

  dropdownItems.forEach(item => {
    item.addEventListener('click', function() {
      // Remove active class from all menu links
      menuLinks.forEach(link => {
        link.classList.remove('active');
      });

      // Close sidebar on mobile when clicking a dropdown item
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('active');
        contentOverlay.classList.remove('active');
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      const dropdownMenus = document.querySelectorAll('.custom-dropdown-menu');
      const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

      dropdownMenus.forEach(menu => {
        menu.classList.remove('show');
      });

      dropdownToggles.forEach(toggle => {
        toggle.classList.remove('active');
        const dropdownIcon = toggle.querySelector('.dropdown-icon');
        dropdownIcon.style.transform = 'rotate(0)';
      });
    }
  });

  // Handle window resize
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      contentOverlay.classList.remove('active');
      if (!sidebar.classList.contains('collapsed')) {
        sidebar.classList.remove('active');
      }
    } else {
      sidebar.classList.remove('collapsed');
      mainContent.classList.remove('expanded');
      if (!sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
      }
    }
  });
</script>
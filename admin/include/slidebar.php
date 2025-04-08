<style>
    .sidebar {
        background-color: #212529;
        min-height: 100vh;
        padding: 20px 0;
        position: fixed;
        top: 0;
        left: 0;
    }

    .sidebar-header {
        padding: 0 20px 20px;
        border-bottom: 1px solid #2c3034;
        margin-bottom: 20px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
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
        position: absolute;
        bottom: 20px;
        left: 0;
    }

    .logout-btn:hover {
        color: #fff;
        background-color: #dc3545;
    }
</style>

<div class="col-md-2 sidebar fixed-top">
    <div class="sidebar-header">
        <h3 class="text-white m-0">Admin Panel</h3>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="#dashboard" class="active">
                Dashboard
            </a>
        </li>
        <li class="dropdown">
            <div class="dropdown-toggle">
                Categories
            </div>
            <div class="custom-dropdown-menu">
                <a href="#addStoryCategory" class="dropdown-item">Add Story Category</a>
                <a href="#addArticleCategory" class="dropdown-item">Add Article Category</a>
            </div>
        </li>
        <li>
            <a href="#users">
                Users
            </a>
        </li>
        <li>
            <a href="#reports">
                Reports
            </a>
        </li>
        <li>
            <a href="#settings">
                Settings
            </a>
        </li>
    </ul>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>
</div>

<script>
    // Handle sidebar menu clicks
    const menuLinks = document.querySelectorAll('.sidebar-menu > li > a:not(.dropdown-item)');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    // Dropdown functionality
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            // Toggle dropdown menu
            const dropdownMenu = this.nextElementSibling;
            dropdownMenu.classList.toggle('show');

            // Rotate dropdown icon
            const dropdownIcon = this.querySelector('.dropdown-icon');
            this.classList.toggle('active');

            // Close other dropdowns
            dropdownToggles.forEach(otherToggle => {
                if (otherToggle !== this) {
                    const otherDropdownMenu = otherToggle.nextElementSibling;
                    otherDropdownMenu.classList.remove('show');
                    otherToggle.classList.remove('active');
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
            });
        }
    });
</script>
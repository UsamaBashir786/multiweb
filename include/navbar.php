<div class="container-fluid bg-light p-0">
  <div class="row gx-0 d-none d-lg-flex">
    <div class="col-lg-7 px-5 text-start">
      <div class="h-100 d-inline-flex align-items-center py-3 me-4">
        <i class="fas fa-map-marker-alt text-primary me-2"></i>
        <small>Noor Shah, District Sahiwal. </small>
      </div>
      <div class="h-100 d-inline-flex align-items-center py-3">
        <!-- <i class="far fa-clock text-primary me-2"></i> -->
        <small></small>
      </div>
    </div>
    <div class="col-lg-5 px-5 text-end">
      <div class="h-100 d-inline-flex align-items-center py-3 me-4">
        <i class="fas fa-phone-alt text-primary me-2"></i>
        <small>03003752466</small>
      </div>
      <div class="h-100 d-inline-flex align-items-center">
        <a href="login.php" class="btn btn-sm btn-outline-primary me-2"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
        <a href="register.php" class="btn btn-sm btn-primary"><i class="fas fa-user-plus me-1"></i> Register</a>
      </div>
    </div>
  </div>
</div>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
  <div class="container-fluid">
    <a href="index.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
      <h2 class="m-0 text-primary">Lion Of Web</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <div class="navbar-nav ms-auto p-4 p-lg-0">
        <a href="index.php" class="nav-item nav-link active">Home</a>

        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">English News</a>
          <div class="dropdown-menu fade-up m-0">
            <a href="#" class="dropdown-item">Latest News</a>
            <a href="#" class="dropdown-item">Breaking News</a>
            <a href="#" class="dropdown-item">Featured Stories</a>
          </div>
        </div>

        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Urdu News</a>
          <div class="dropdown-menu fade-up m-0">
            <a href="#" class="dropdown-item">تازہ ترین خبریں</a>
            <a href="#" class="dropdown-item">اہم خبریں</a>
            <a href="#" class="dropdown-item">خصوصی رپورٹس</a>
          </div>
        </div>

        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Categories</a>
          <div class="dropdown-menu fade-up m-0">
            <h6 class="dropdown-header">Business & Finance</h6>
            <a href="#" class="dropdown-item">Business</a>
            <a href="#" class="dropdown-item">Cryptocurrency</a>
            <a href="#" class="dropdown-item">Economy</a>
            <a href="#" class="dropdown-item">Personal Finance</a>
            <a href="#" class="dropdown-item">Jobs</a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">View All Categories</a>
          </div>
        </div>

        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Lifestyle</a>
          <div class="dropdown-menu fade-up m-0">
            <a href="#" class="dropdown-item">Health</a>
            <a href="#" class="dropdown-item">Women</a>
            <a href="#" class="dropdown-item">Food</a>
            <a href="#" class="dropdown-item">Recipes</a>
            <a href="#" class="dropdown-item">Travel</a>
          </div>
        </div>

        <div class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Tech & More</a>
          <div class="dropdown-menu fade-up m-0">
            <a href="#" class="dropdown-item">Mobile</a>
            <a href="#" class="dropdown-item">Technology</a>
            <a href="#" class="dropdown-item">Education</a>
            <a href="#" class="dropdown-item">Sports</a>
            <a href="#" class="dropdown-item">Autos</a>
          </div>
        </div>

        <a href="#" class="nav-item nav-link">Currency</a>
        <a href="#" class="nav-item nav-link">Recipes</a>
        <a href="contact.php" class="nav-item nav-link">Contact</a>
      </div>

      <!-- Mobile-only login/register buttons -->
      <div class="d-lg-none justify-content-center mt-3 mb-2">
        <a href="login.php" class="btn btn-outline-primary me-2" style="height: 100%;width: 100%;"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
        <a href="register.php" class="btn btn-primary mt-2" style="height: 100%;margin:0px;"><i class="fas fa-user-plus me-1"></i> Register</a>
      </div>
    </div>
  </div>
</nav>

<style>
  /* Additional styles for the login and register buttons */
  .btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
  }

  .btn-primary {
    background-color: #007bff;
    border-color: #007bff;
  }

  @media (max-width: 991.98px) {

    /* Improve the mobile menu style */
    .navbar-collapse {
      max-height: 80vh;
      overflow-y: auto;
    }

    /* Style the mobile buttons */
    .d-lg-none.d-flex .btn {
      padding: 8px 15px;
      font-size: 14px;
    }
  }

  /* Common styles for buttons */
  .btn {
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
</style>
<style>
  /* Fix for horizontal scrollbar */
  body {
    overflow-x: hidden;
    width: 100%;
  }

  /* Container fluid adjustments */
  .container-fluid {
    padding-left: 15px;
    padding-right: 15px;
    max-width: 100%;
  }

  .bg-light {
    background-color: #f0f0f0 !important;
  }

  .btn {
    font-weight: 500;
    transition: .5s;
  }

  .btn.btn-primary {
    color: #FFFFFF;
  }

  .btn-sm-square {
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    font-weight: normal;
  }

  .navbar .dropdown-toggle::after {
    border: none;
    content: "\f107";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    vertical-align: middle;
    margin-left: 8px;
  }

  .navbar-light .navbar-nav .nav-link {
    margin-right: 15px;
    /* Reduced from 30px */
    padding: 25px 0;
    color: #000;
    font-size: 15px;
    font-weight: 500;
    text-transform: uppercase;
    outline: none;
  }

  .navbar-light .navbar-nav .nav-link:hover,
  .navbar-light .navbar-nav .nav-link.active {
    color: blue;
  }

  @media (max-width: 991.98px) {
    .navbar-light .navbar-nav .nav-link {
      margin-right: 0;
      padding: 10px 0;
    }

    .navbar-light .navbar-nav {
      border-top: 1px solid #EEEEEE;
    }

    /* Mobile dropdown fixes */
    .dropdown-menu {
      border: none;
      padding: 5px 0;
      margin: 0 0 10px 15px;
      background-color: #f8f9fa;
    }

    /* Subscribe button for mobile */
    .navbar-collapse {
      position: relative;
    }

    .navbar-collapse::after {
      content: "";
      display: block;
      clear: both;
    }

    .navbar-collapse .btn-primary {
      display: block;
      margin: 10px 15px;
      padding: 10px;
      text-align: center;
    }
  }

  .navbar-light .navbar-brand,
  .navbar-light a.btn {
    height: 75px;
  }

  .navbar-light .navbar-nav .nav-link {
    color: black;
    font-weight: 500;
  }

  .navbar-light.sticky-top {
    top: -100px;
    transition: .5s;
    width: 100%;
  }

  /* Desktop dropdown behavior */
  @media (min-width: 992px) {
    .navbar .nav-item .dropdown-menu {
      display: block;
      border: none;
      margin-top: 0;
      top: 150%;
      opacity: 0;
      visibility: hidden;
      transition: .5s;
      min-width: 200px;
      max-width: 100%;
    }

    .navbar .nav-item:hover .dropdown-menu {
      top: 100%;
      visibility: visible;
      transition: .5s;
      opacity: 1;
    }

    /* Fix for dropdowns that might go off-screen */
    .navbar .nav-item:nth-last-child(-n+3) .dropdown-menu {
      right: 0;
      left: auto;
    }
  }

  /* Fixes for sticky navigation */
  .sticky-nav {
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1030;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
</style>

<script>
  $(document).ready(function() {
    // Handle sticky navigation
    $(window).scroll(function() {
      if ($(this).scrollTop() > 250) {
        $('.sticky-top').addClass('sticky-nav').css('top', '0px');
      } else {
        $('.sticky-top').removeClass('sticky-nav').css('top', '-100px');
      }
    });

    // Fix for mobile menu scrolling
    $('.navbar-toggler').on('click', function() {
      if ($(window).width() < 992) {
        if ($('.navbar-collapse').hasClass('show')) {
          $('body').css('overflow', 'auto');
        } else {
          $('body').css('overflow', 'hidden');
        }
      }
    });
  });
</script>
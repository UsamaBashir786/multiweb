<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'include/css-links.php' ?>

</head>

<body>

  <!-- Include your navbar here -->
  <?php include 'include/navbar.php' ?>

  <!-- Under Development Section -->
  <section class="under-development-section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <div class="under-dev-content p-5">
            <div class="icon-container mb-4">
              <i class="fas fa-tools fa-5x text-primary"></i>
            </div>
            <h1 class="mb-4">Page Under Development</h1>
            <p class="lead mb-4">We're working hard to bring you an amazing experience. This page is currently under construction and will be available soon.</p>
            <div class="progress mb-4" style="height: 25px;">
              <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75% Complete</div>
            </div>
            <p class="mb-5">We appreciate your patience as we improve our website. Please check back soon!</p>
            <a href="index.php" class="btn btn-primary btn-lg px-4 py-2">
              <i class="fas fa-home me-2"></i> Return to Homepage
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Coming Section -->
  <section class="features-coming py-5 bg-light">
    <div class="container">
      <div class="row mb-4">
        <div class="col-12 text-center">
          <h2>Features Coming Soon</h2>
          <p class="text-muted">Here's what we're working on for this section</p>
        </div>
      </div>

      <div class="row g-4">
        <!-- Feature 1 -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
              <div class="feature-icon mb-3">
                <i class="fas fa-search fa-2x text-primary"></i>
              </div>
              <h4>Advanced Search</h4>
              <p class="text-muted">Search through categories with powerful filtering options</p>
            </div>
          </div>
        </div>

        <!-- Feature 2 -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
              <div class="feature-icon mb-3">
                <i class="fas fa-user-circle fa-2x text-primary"></i>
              </div>
              <h4>User Profiles</h4>
              <p class="text-muted">Create a profile to save your preferences and favorite content</p>
            </div>
          </div>
        </div>

        <!-- Feature 3 -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-4">
              <div class="feature-icon mb-3">
                <i class="fas fa-bell fa-2x text-primary"></i>
              </div>
              <h4>Notifications</h4>
              <p class="text-muted">Get alerts for the latest news and updates in your areas of interest</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Us CTA -->
  <section class="contact-cta py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h3 class="mb-4">Have suggestions for this page?</h3>
          <p class="mb-4">We'd love to hear your ideas about what features you'd like to see on this page.</p>
          <a href="contact.php" class="btn btn-outline-primary px-4 py-2">
            <i class="fas fa-envelope me-2"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Include your footer here -->
  <?php include 'include/footer.php' ?>

  <!-- Scroll to Top Button -->
  <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <?php include 'include/js-links.php' ?>


  <style>
    .under-development-section {
      min-height: 60vh;
      display: flex;
      align-items: center;
      padding: 80px 0;
    }

    .under-dev-content {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .icon-container {
      width: 120px;
      height: 120px;
      background-color: rgba(0, 123, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .feature-icon {
      width: 70px;
      height: 70px;
      background-color: rgba(0, 123, 255, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .contact-cta {
      background-color: #f8f9fa;
    }

    /* Scroll to Top Button */
    .scroll-to-top-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #007bff;
      color: white;
      border: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .scroll-to-top-btn.visible {
      opacity: 1;
      visibility: visible;
    }

    .scroll-to-top-btn:hover {
      background-color: #0069d9;
      transform: translateY(-3px);
    }

    .scroll-to-top-btn:active {
      transform: translateY(0);
    }

    @media (max-width: 576px) {
      .scroll-to-top-btn {
        width: 40px;
        height: 40px;
        bottom: 20px;
        right: 20px;
      }

      .under-dev-content {
        padding: 30px 15px !important;
      }

      .icon-container {
        width: 90px;
        height: 90px;
      }
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const scrollToTopBtn = document.getElementById('scrollToTopBtn');

      // Show/hide the button based on scroll position
      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          scrollToTopBtn.classList.add('visible');
        } else {
          scrollToTopBtn.classList.remove('visible');
        }
      });

      // Smooth scroll to top when clicked
      scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    });
  </script>

</body>

</html>
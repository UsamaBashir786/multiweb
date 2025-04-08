<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'include/css-links.php' ?>
</head>

<body>

  <!-- Include your navbar here -->
  <?php include 'include/navbar.php' ?>

  <!-- Page Header -->
  <div class="container-fluid page-header py-5 mb-5">
    <div class="container py-5">
      <h1 class="display-4 text-white fw-bold">Contact Us</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Contact</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Contact Info Section -->
  <div class="container py-5">
    <div class="row g-5">
      <div class="col-lg-7 col-md-6">
        <h2 class="mb-4">Get In Touch With Us</h2>
        <p class="mb-4">Have questions, feedback, or suggestions? We'd love to hear from you. Fill out the form below and our team will get back to you as soon as possible.</p>

        <!-- Contact Form -->
        <form id="contactForm">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating">
                <input type="text" class="form-control" id="name" placeholder="Your Name">
                <label for="name">Your Name</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input type="email" class="form-control" id="email" placeholder="Your Email">
                <label for="email">Your Email</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating">
                <input type="text" class="form-control" id="subject" placeholder="Subject">
                <label for="subject">Subject</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 150px"></textarea>
                <label for="message">Message</label>
              </div>
            </div>
            <div class="col-12">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="termsCheck">
                <label class="form-check-label" for="termsCheck">
                  I agree to the <a href="#">terms and conditions</a> and <a href="#">privacy policy</a>
                </label>
              </div>
            </div>
            <div class="col-12">
              <button class="btn btn-primary py-3 px-5" type="submit">
                <i class="fas fa-paper-plane me-2"></i>Send Message
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-lg-5 col-md-6">
        <div class="bg-light rounded p-4 p-sm-5 h-100">
          <h3 class="mb-4">Contact Information</h3>

          <div class="d-flex align-items-center mb-4">
            <div class="contact-icon me-3">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
              <h5 class="mb-1">Our Office</h5>
              <p class="mb-0">Noor Shah, District Sahiwal.</p>
            </div>
          </div>

          <div class="d-flex align-items-center mb-4">
            <div class="contact-icon me-3">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div>
              <h5 class="mb-1">Phone Number</h5>
              <p class="mb-0">03003752466</p>
            </div>
          </div>

          <div class="d-flex align-items-center mb-4">
            <div class="contact-icon me-3">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <h5 class="mb-1">Email Address</h5>
              <p class="mb-0">liaqat.ali011@gmail.com</p>
            </div>
          </div>
          <!-- 
          <div class="d-flex align-items-center mb-4">
            <div class="contact-icon me-3">
              <i class="fas fa-clock"></i>
            </div>
            <div>
              <h5 class="mb-1">Working Hours</h5>
              <p class="mb-0">Mon - Sat: 09:00 AM - 07:00 PM</p>
              <p class="mb-0">Sunday: Closed</p>
            </div>
          </div> -->

          <!-- <div class="mt-5">
            <h5 class="mb-3">Follow Us</h5>
            <div class="d-flex pt-2">
              <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-facebook-f"></i></a>
              <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-twitter"></i></a>
              <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-linkedin-in"></i></a>
              <a class="btn btn-square btn-primary me-2" href=""><i class="fab fa-instagram"></i></a>
              <a class="btn btn-square btn-primary" href=""><i class="fab fa-youtube"></i></a>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>

  <!-- Map Section -->
  <!-- <div class="container-fluid py-5 mb-5 px-0">
    <div class="container mb-4">
      <h2 class="text-center mb-1">Our Location</h2>
      <p class="text-center mb-4">Find us on the map</p>
    </div>
    <div class="map-container">
      <iframe class="w-100" style="height: 450px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d448181.1637446522!2d76.8130632!3d28.647279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd5b347eb62d%3A0x37205b715389640!2sDelhi!5e0!3m2!1sen!2sin!4v1681508345234!5m2!1sen!2sin" frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
    </div>
  </div> -->

  <!-- FAQ Section -->
  <div class="container py-5">
    <div class="text-center mx-auto mb-5" style="max-width: 600px;">
      <h2 class="mb-3">Frequently Asked Questions</h2>
      <p>Find answers to commonly asked questions about our services</p>
    </div>

    <div class="row g-5">
      <div class="col-lg-6">
        <div class="accordion" id="accordionFAQ1">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                How do I subscribe to your newsletter?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionFAQ1">
              <div class="accordion-body">
                You can subscribe to our newsletter by clicking the "Subscribe Now" button in the header of our website. Fill in your email address and follow the confirmation instructions sent to your inbox.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                What topics do you cover in your news?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFAQ1">
              <div class="accordion-body">
                We cover a wide range of topics including Business, Technology, Health, Food, Travel, Sports, Politics, Education, and more. You can explore specific categories through our navigation menu.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                How often is your content updated?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFAQ1">
              <div class="accordion-body">
                We update our content daily. Breaking news is published as events unfold, while feature articles and in-depth pieces are added throughout the week.
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="accordion" id="accordionFAQ2">
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                How can I submit a story or news tip?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionFAQ2">
              <div class="accordion-body">
                You can submit stories or news tips through our contact form on this page. Please include "News Tip" in the subject line and provide as much detail as possible.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                Do you offer content in languages other than English?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionFAQ2">
              <div class="accordion-body">
                Yes, we offer content in Urdu as well. You can access Urdu news through the "Urdu News" section in our navigation menu.
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                How can I advertise on your website?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionFAQ2">
              <div class="accordion-body">
                For advertising inquiries, please contact our advertising team at ads@lionofweb.com or fill out the contact form with "Advertising" in the subject line.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Include your footer here -->
  <?php include 'include/footer.php' ?>

  <!-- Scroll to Top Button -->
  <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <?php include 'include/js-links.php' ?>


  <style>
    /* Page Header Styling */
    .page-header {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/img/header-bg.jpg') center center no-repeat;
      background-size: cover;
    }

    .breadcrumb-item a {
      color: #ffffff;
    }

    .breadcrumb-item.active {
      color: rgba(255, 255, 255, 0.7);
    }

    /* Contact Form Styling */
    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
      color: #0d6efd;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Contact Info Box Styling */
    .contact-icon {
      width: 50px;
      height: 50px;
      background-color: #0d6efd;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 20px;
    }

    /* Social Media Buttons */
    .btn-square {
      width: 36px;
      height: 36px;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: normal;
    }

    /* Map Container */
    .map-container {
      overflow: hidden;
      padding-bottom: 0;
      position: relative;
      height: 450px;
    }

    /* FAQ Styling */
    .accordion-button:not(.collapsed) {
      color: #0d6efd;
      background-color: rgba(13, 110, 253, 0.1);
    }

    .accordion-button:focus {
      border-color: rgba(13, 110, 253, 0.25);
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Scroll to Top Button */
    .scroll-to-top-btn {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: #0d6efd;
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
      background-color: #0b5ed7;
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

      .contact-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
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

      // Contact form validation
      const contactForm = document.getElementById('contactForm');

      if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
          e.preventDefault();

          // Basic validation
          const name = document.getElementById('name').value;
          const email = document.getElementById('email').value;
          const subject = document.getElementById('subject').value;
          const message = document.getElementById('message').value;
          const termsCheck = document.getElementById('termsCheck').checked;

          if (!name || !email || !subject || !message) {
            alert('Please fill in all required fields');
            return;
          }

          if (!termsCheck) {
            alert('Please agree to the terms and conditions');
            return;
          }

          // You would typically send the form data to your server here
          // For now, just show a success message
          alert('Thank you! Your message has been sent successfully.');
          contactForm.reset();
        });
      }
    });
  </script>

</body>

</html>
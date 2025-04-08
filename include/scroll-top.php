<!-- Scroll to Top Button -->
<button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
  <i class="fas fa-arrow-up"></i>
</button>

<style>
  .scroll-to-top-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #9B5DE5;
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
    background-color: #8a4dd0;
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
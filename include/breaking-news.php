<!--
================== 
Breaking News 
===================
-->
<section class="my-5 breaking-news-ticker py-3" style="background-color: #9B5DE5;">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <span class="badge bg-danger px-3 py-2 fs-6 fw-bold">BREAKING</span>
          </div>
          <div class="ticker-wrapper overflow-hidden" style="width: 100%;">
            <div class="ticker-content d-flex" style="animation: ticker-scroll 30s linear infinite; white-space: nowrap;">
              <div class="ticker-item me-5">
                <a href="#" class="text-white text-decoration-none">
                  <i class="fas fa-bolt me-2"></i>
                  Latest Technology Breakthrough Announced as Companies Race to Innovate
                </a>
              </div>
              <div class="ticker-item me-5">
                <a href="#" class="text-white text-decoration-none">
                  <i class="fas fa-chart-line me-2"></i>
                  Stock Market Hits New Record High as Economy Shows Signs of Growth
                </a>
              </div>
              <div class="ticker-item me-5">
                <a href="#" class="text-white text-decoration-none">
                  <i class="fas fa-heartbeat me-2"></i>
                  New Health Study Reveals Surprising Benefits of Regular Exercise
                </a>
              </div>
              <div class="ticker-item me-5">
                <a href="#" class="text-white text-decoration-none">
                  <i class="fas fa-globe me-2"></i>
                  International Summit Addresses Climate Change Concerns
                </a>
              </div>
              <div class="ticker-item me-5">
                <a href="#" class="text-white text-decoration-none">
                  <i class="fas fa-graduation-cap me-2"></i>
                  Education Reform Bill Passes with Bipartisan Support
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  @keyframes ticker-scroll {
    0% {
      transform: translateX(100%);
    }

    100% {
      transform: translateX(-100%);
    }
  }

  .ticker-wrapper {
    position: relative;
    overflow: hidden;
  }

  .ticker-content {
    position: relative;
    width: 100%;
    overflow: visible;
  }

  .ticker-item {
    font-weight: 500;
    font-size: 1.1rem;
  }
</style>
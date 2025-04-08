<!-- Categories Grid Section -->
<section class="categories-grid py-5">
  <div class="container">
    <div class="row mb-4">
      <div class="col-12 text-center">
        <h2 class="fw-bold">Popular Categories</h2>
        <p class="text-muted">Find the information you're looking for in our diverse categories</p>
      </div>
    </div>

    <div class="row g-4">
      <!-- Category Box 1 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-chart-line fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Business</h4>
            <p class="text-muted mb-0">Latest updates on business trends and market analysis</p>
          </div>
        </a>
      </div>

      <!-- Category Box 2 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-microchip fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Technology</h4>
            <p class="text-muted mb-0">Tech innovations and digital transformation news</p>
          </div>
        </a>
      </div>

      <!-- Category Box 3 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-heartbeat fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Health</h4>
            <p class="text-muted mb-0">Health tips, medical breakthroughs, and wellness advice</p>
          </div>
        </a>
      </div>

      <!-- Category Box 4 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-utensils fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Food</h4>
            <p class="text-muted mb-0">Delicious recipes, culinary trends, and food culture</p>
          </div>
        </a>
      </div>

      <!-- Category Box 5 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-plane fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Travel</h4>
            <p class="text-muted mb-0">Travel guides, destinations, and adventure stories</p>
          </div>
        </a>
      </div>

      <!-- Category Box 6 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-futbol fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Sports</h4>
            <p class="text-muted mb-0">Sports news, analysis, and coverage of major events</p>
          </div>
        </a>
      </div>

      <!-- Category Box 7 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-landmark fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Politics</h4>
            <p class="text-muted mb-0">Political developments, policy updates, and analysis</p>
          </div>
        </a>
      </div>

      <!-- Category Box 8 -->
      <div class="col-lg-3 col-md-4 col-sm-6">
        <a href="#" class="text-decoration-none">
          <div class="category-box p-4 rounded h-100">
            <div class="icon-box mb-3">
              <i class="fas fa-graduation-cap fa-2x text-primary"></i>
            </div>
            <h4 class="mb-2">Education</h4>
            <p class="text-muted mb-0">Educational resources, learning tips, and academic news</p>
          </div>
        </a>
      </div>
    </div>

    <!-- View All Categories Button -->
    <div class="row mt-4">
      <div class="col-12 text-center">
        <a href="#" class="btn btn-primary px-4 py-2">View All Categories</a>
      </div>
    </div>
  </div>
</section>

<style>
  /* Styling for Categories Grid */
  .categories-grid {
    background-color: #f8f9fa;
  }

  .category-box {
    background-color: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
  }

  .category-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-color: rgba(0, 123, 255, 0.2);
  }

  .category-box h4 {
    color: #333;
    font-weight: 600;
    font-size: 1.25rem;
    transition: all 0.3s ease;
  }

  .category-box:hover h4 {
    color: #007bff;
    /* Use your primary color here */
  }

  .icon-box {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: rgba(0, 123, 255, 0.1);
    /* Use your primary color with opacity */
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .category-box:hover .icon-box {
    background-color: rgba(0, 123, 255, 0.2);
    /* Slightly darker on hover */
  }

  /* Responsive adjustments */
  @media (max-width: 767.98px) {
    .category-box {
      padding: 15px;
    }

    .category-box h4 {
      font-size: 1.1rem;
    }

    .icon-box {
      width: 50px;
      height: 50px;
    }
  }
</style>
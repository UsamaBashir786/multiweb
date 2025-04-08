<!--
================== 
Trending News 
===================
-->

<section class="trending-news py-5" style="background-color: #f8f9fa;">
  <div class="container">
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <h2 class="fw-bold mb-3 mb-md-0">Trending News</h2>
          <div class="trending-tabs">
            <ul class="nav nav-pills" id="trendingTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active px-4" style="background-color: #9B5DE5;" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-content" type="button" role="tab" aria-controls="all-content" aria-selected="true">All</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="politics-tab" data-bs-toggle="pill" data-bs-target="#politics-content" type="button" role="tab" aria-controls="politics-content" aria-selected="false">Politics</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="business-tab" data-bs-toggle="pill" data-bs-target="#business-content" type="button" role="tab" aria-controls="business-content" aria-selected="false">Business</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="tech-tab" data-bs-toggle="pill" data-bs-target="#tech-content" type="button" role="tab" aria-controls="tech-content" aria-selected="false">Technology</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="tab-content" id="trendingTabsContent">
      <!-- All Content Tab -->
      <div class="tab-pane fade show active" id="all-content" role="tabpanel" aria-labelledby="all-tab">
        <div class="row">
          <!-- Main Trending News -->
          <div class="col-lg-6 mb-4">
            <div class="position-relative rounded overflow-hidden" style="height: 400px;">
              <img src="https://via.placeholder.com/800x500" class="w-100 h-100 object-fit-cover" alt="Main Trending News">
              <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                <span class="badge mb-2" style="background-color: #9B5DE5;">Top Story</span>
                <h3 class="text-white mb-2">Major Breakthrough in Renewable Energy Research</h3>
                <div class="d-flex align-items-center">
                  <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Author" width="30" height="30">
                  <span class="text-white-50 me-3">John Doe</span>
                  <span class="text-white-50"><i class="far fa-clock me-1"></i> 3 hours ago</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Trending News List -->
          <div class="col-lg-6">
            <div class="row">
              <!-- Trending Item 1 -->
              <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                  <div class="row g-0">
                    <div class="col-4">
                      <img src="https://via.placeholder.com/300x200" class="img-fluid rounded-start h-100 object-fit-cover" alt="Trending News">
                    </div>
                    <div class="col-8">
                      <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                          <span class="badge" style="background-color: #9B5DE5;">Politics</span>
                          <small class="text-muted"><i class="fas fa-fire me-1"></i> 1.2K views</small>
                        </div>
                        <h5 class="card-title">New Policy Reforms Proposed by Government</h5>
                        <p class="card-text text-truncate">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                        <div class="d-flex align-items-center">
                          <small class="text-muted me-3"><i class="far fa-clock me-1"></i> 5 hours ago</small>
                          <a href="#" class="text-decoration-none ms-auto" style="color: #9B5DE5;">Read <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Trending Item 2 -->
              <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                  <div class="row g-0">
                    <div class="col-4">
                      <img src="https://via.placeholder.com/300x200" class="img-fluid rounded-start h-100 object-fit-cover" alt="Trending News">
                    </div>
                    <div class="col-8">
                      <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                          <span class="badge" style="background-color: #9B5DE5;">Business</span>
                          <small class="text-muted"><i class="fas fa-fire me-1"></i> 890 views</small>
                        </div>
                        <h5 class="card-title">Global Market Trends Show Economic Recovery</h5>
                        <p class="card-text text-truncate">Vivamus elementum semper nisi. Aenean vulputate eleifend tellus...</p>
                        <div class="d-flex align-items-center">
                          <small class="text-muted me-3"><i class="far fa-clock me-1"></i> 7 hours ago</small>
                          <a href="#" class="text-decoration-none ms-auto" style="color: #9B5DE5;">Read <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Trending Item 3 -->
              <div class="col-12 mb-3">
                <div class="card border-0 shadow-sm">
                  <div class="row g-0">
                    <div class="col-4">
                      <img src="https://via.placeholder.com/300x200" class="img-fluid rounded-start h-100 object-fit-cover" alt="Trending News">
                    </div>
                    <div class="col-8">
                      <div class="card-body">
                        <div class="d-flex justify-content-between mb-1">
                          <span class="badge" style="background-color: #9B5DE5;">Technology</span>
                          <small class="text-muted"><i class="fas fa-fire me-1"></i> 1.8K views</small>
                        </div>
                        <h5 class="card-title">Tech Giants Announce Collaboration on New Project</h5>
                        <p class="card-text text-truncate">Cras dapibus. Vivamus elementum semper nisi...</p>
                        <div class="d-flex align-items-center">
                          <small class="text-muted me-3"><i class="far fa-clock me-1"></i> 10 hours ago</small>
                          <a href="#" class="text-decoration-none ms-auto" style="color: #9B5DE5;">Read <i class="fas fa-arrow-right ms-1"></i></a>
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

      <!-- Politics Tab -->
      <div class="tab-pane fade" id="politics-content" role="tabpanel" aria-labelledby="politics-tab">
        <div class="row">
          <!-- Politics News Items -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Politics News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Politics</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 4 hours ago</small>
                </div>
                <h5 class="card-title">New Policy Reforms Proposed by Government</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Politics News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Politics</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 6 hours ago</small>
                </div>
                <h5 class="card-title">International Relations Strengthen Between Nations</h5>
                <p class="card-text">Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Business Tab -->
      <div class="tab-pane fade" id="business-content" role="tabpanel" aria-labelledby="business-tab">
        <div class="row">
          <!-- Business News Items -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Business News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Business</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 7 hours ago</small>
                </div>
                <h5 class="card-title">Global Market Trends Show Economic Recovery</h5>
                <p class="card-text">Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Business News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Business</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 9 hours ago</small>
                </div>
                <h5 class="card-title">Major Company Announces Expansion Plans</h5>
                <p class="card-text">Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Technology Tab -->
      <div class="tab-pane fade" id="tech-content" role="tabpanel" aria-labelledby="tech-tab">
        <div class="row">
          <!-- Technology News Items -->
          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Technology News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Technology</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 5 hours ago</small>
                </div>
                <h5 class="card-title">Tech Giants Announce Collaboration on New Project</h5>
                <p class="card-text">Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Technology News">
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span class="badge" style="background-color: #9B5DE5;">Technology</span>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> 8 hours ago</small>
                </div>
                <h5 class="card-title">Revolutionary AI Technology Unveiled This Week</h5>
                <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie.</p>
                <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read More</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12 text-center">
        <a href="#" class="btn px-4 py-2 text-white" style="border-radius: 30px; background-color: #9B5DE5;">View More Trending <i class="fas fa-chart-line ms-1"></i></a>
      </div>
    </div>
  </div>
</section>
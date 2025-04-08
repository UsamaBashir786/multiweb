<?php
// Categories for articles
$categories = ['All', 'Technology', 'Business', 'Politics', 'Entertainment', 'Health', 'Science', 'Sports', 'Lifestyle'];

// Get the current category from URL parameter, default to 'All'
$current_category = isset($_GET['category']) ? $_GET['category'] : 'All';

// In a real implementation, you would fetch articles from a database
// For now, we'll use dummy data for demonstration
$articles = [
  // Technology Articles
  [
    'id' => 1,
    'title' => 'Major Breakthrough in Renewable Energy Research',
    'category' => 'Technology',
    'author' => 'John Doe',
    'date' => 'April 8, 2025',
    'views' => '2.4K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'A team of international researchers has announced a major breakthrough in renewable energy technology that could revolutionize how solar power is harnessed...'
  ],
  [
    'id' => 2,
    'title' => 'AI Assistants Becoming More Human-Like',
    'category' => 'Technology',
    'author' => 'Sarah Johnson',
    'date' => 'April 7, 2025',
    'views' => '1.8K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Latest advancements in natural language processing have led to AI assistants that can understand context and respond in increasingly human-like ways...'
  ],
  [
    'id' => 3,
    'title' => '5G Networks Expand to Rural Areas',
    'category' => 'Technology',
    'author' => 'Michael Chen',
    'date' => 'April 6, 2025',
    'views' => '1.5K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'New infrastructure initiatives bring high-speed connectivity to previously underserved rural communities across the country...'
  ],

  // Business Articles
  [
    'id' => 4,
    'title' => 'Global Market Trends Show Economic Recovery',
    'category' => 'Business',
    'author' => 'Emma Wilson',
    'date' => 'April 7, 2025',
    'views' => '1.2K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Recent market indicators point to a strong economic recovery across global markets, with growth projections exceeding earlier forecasts...'
  ],
  [
    'id' => 5,
    'title' => 'Major Company Announces Expansion Plans',
    'category' => 'Business',
    'author' => 'Robert Johnson',
    'date' => 'April 5, 2025',
    'views' => '980',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'One of the world\'s leading technology companies has announced plans to expand operations with new offices in three countries...'
  ],

  // Politics Articles
  [
    'id' => 6,
    'title' => 'New Policy Reforms Proposed by Government',
    'category' => 'Politics',
    'author' => 'Thomas Wilson',
    'date' => 'April 7, 2025',
    'views' => '2.1K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'The government has unveiled a comprehensive package of policy reforms aimed at addressing economic inequality and environmental challenges...'
  ],
  [
    'id' => 7,
    'title' => 'International Relations Strengthen Between Nations',
    'category' => 'Politics',
    'author' => 'Jessica Miller',
    'date' => 'April 4, 2025',
    'views' => '1.7K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'A series of diplomatic breakthroughs has led to improved relations between previously conflicting nations, with new trade agreements...'
  ],

  // Entertainment Articles
  [
    'id' => 8,
    'title' => 'Award-Winning Film Director Announces New Project',
    'category' => 'Entertainment',
    'author' => 'David Kim',
    'date' => 'April 6, 2025',
    'views' => '3.5K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'The acclaimed director behind last year\'s Oscar-winning film has announced their next project, a science fiction epic...'
  ],
  [
    'id' => 9,
    'title' => 'Music Festival Announces Incredible Lineup for Summer',
    'category' => 'Entertainment',
    'author' => 'Sophia Rodriguez',
    'date' => 'April 5, 2025',
    'views' => '4.2K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'One of the world\'s most popular music festivals has revealed its lineup for this summer, featuring several chart-topping artists...'
  ],

  // Health Articles
  [
    'id' => 10,
    'title' => 'New Study Reveals Health Benefits of Mediterranean Diet',
    'category' => 'Health',
    'author' => 'Emily Santos',
    'date' => 'April 8, 2025',
    'views' => '2.3K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'A comprehensive new study has found additional health benefits associated with the Mediterranean diet, particularly for heart health...'
  ],
  [
    'id' => 11,
    'title' => 'Breakthrough in Alzheimer\'s Research Shows Promise',
    'category' => 'Health',
    'author' => 'Dr. James Wilson',
    'date' => 'April 6, 2025',
    'views' => '3.1K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Scientists have reported a significant breakthrough in understanding the mechanisms behind Alzheimer\'s disease, potentially opening new treatment pathways...'
  ]
];

// Filter articles by category if not 'All'
if ($current_category !== 'All') {
  $filtered_articles = array_filter($articles, function ($article) use ($current_category) {
    return $article['category'] === $current_category;
  });
} else {
  $filtered_articles = $articles;
}

// Pagination settings
$articles_per_page = 6;
$total_articles = count($filtered_articles);
$total_pages = ceil($total_articles / $articles_per_page);
$current_page = isset($_GET['page']) ? max(1, min($_GET['page'], $total_pages)) : 1;
$offset = ($current_page - 1) * $articles_per_page;

// Get articles for current page
$paged_articles = array_slice($filtered_articles, $offset, $articles_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Articles - <?= htmlspecialchars($current_category) ?></title>
  <!-- css -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- bootstrap css -->
  <link rel="stylesheet" href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <!-- font awesome icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
  <!-- navbar -->
  <?php include 'include/navbar.php' ?>

  <!--
  ================== 
  Page Header
  ===================
  -->
  <section class="bg-light py-5 mb-5">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="display-4 fw-bold mb-3">Articles</h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color: #9B5DE5;">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Articles</li>
              <?php if ($current_category !== 'All'): ?>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($current_category) ?></li>
              <?php endif; ?>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6">
          <div class="d-flex justify-content-lg-end mt-4 mt-lg-0">
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search articles..." aria-label="Search">
              <button class="btn" type="submit" style="background-color: #9B5DE5; color: white;">
                <i class="fas fa-search"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--
  ================== 
  Articles Content
  ===================
  -->
  <div class="container mb-5">
    <div class="row">
      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- Category Filters -->
        <div class="mb-4 category-filters">
          <div class="d-flex flex-wrap">
            <?php foreach ($categories as $category): ?>
              <a href="articles.php?category=<?= urlencode($category) ?>"
                class="btn me-2 mb-2 <?= $category === $current_category ? 'active' : '' ?>"
                style="<?= $category === $current_category ? 'background-color: #9B5DE5; color: white;' : 'background-color: #f8f9fa; color: #333;' ?>">
                <?= htmlspecialchars($category) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Articles Grid -->
        <div class="row">
          <?php if (empty($paged_articles)): ?>
            <div class="col-12">
              <div class="alert alert-info">
                No articles found for this category. Please try another category.
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($paged_articles as $article): ?>
              <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                  <img src="<?= htmlspecialchars($article['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($article['title']) ?>">
                  <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                      <span class="badge mb-1" style="background-color: #9B5DE5;"><?= htmlspecialchars($article['category']) ?></span>
                      <small class="text-muted"><i class="fas fa-eye me-1"></i> <?= htmlspecialchars($article['views']) ?> views</small>
                    </div>
                    <h5 class="card-title">
                      <a href="article.php?id=<?= $article['id'] ?>" class="text-decoration-none text-dark">
                        <?= htmlspecialchars($article['title']) ?>
                      </a>
                    </h5>
                    <p class="card-text"><?= htmlspecialchars($article['excerpt']) ?></p>
                  </div>
                  <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Author" width="30" height="30">
                        <small class="text-muted"><?= htmlspecialchars($article['author']) ?></small>
                      </div>
                      <small class="text-muted"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($article['date']) ?></small>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <nav aria-label="Articles pagination" class="mt-5">
            <ul class="pagination justify-content-center">
              <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="articles.php?category=<?= urlencode($current_category) ?>&page=<?= $current_page - 1 ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>

              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                  <a class="page-link" href="articles.php?category=<?= urlencode($current_category) ?>&page=<?= $i ?>"
                    style="<?= $i === $current_page ? 'background-color: #9B5DE5; border-color: #9B5DE5;' : '' ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="articles.php?category=<?= urlencode($current_category) ?>&page=<?= $current_page + 1 ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4 mt-5 mt-lg-0">
        <!-- Featured Article -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Featured Article</h5>
          </div>
          <img src="https://via.placeholder.com/600x400" class="card-img-top" alt="Featured Article">
          <div class="card-body">
            <span class="badge mb-2" style="background-color: #9B5DE5;">Technology</span>
            <h5 class="card-title">Quantum Computing Advancements Promise Energy Optimization</h5>
            <p class="card-text">Recent breakthroughs in quantum computing technology are showing promising applications in energy grid optimization...</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <small class="text-muted"><i class="far fa-clock me-1"></i> April 3, 2025</small>
              <a href="#" class="btn" style="background-color: #9B5DE5; color: white;">Read Article</a>
            </div>
          </div>
        </div>

        <!-- Popular Tags -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Popular Tags</h5>
          </div>
          <div class="card-body">
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Technology</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Science</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Politics</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Business</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Health</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Environment</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Education</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Entertainment</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Sports</a>
            <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Lifestyle</a>
          </div>
        </div>

        <!-- Popular Articles -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Popular Articles</h5>
          </div>
          <div class="card-body">
            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Popular Article">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Entertainment</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Music Festival Announces Incredible Lineup for Summer</a></h6>
                <small class="text-muted"><i class="fas fa-eye me-1"></i> 4.2K views</small>
              </div>
            </div>

            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Popular Article">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Health</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Breakthrough in Alzheimer's Research Shows Promise</a></h6>
                <small class="text-muted"><i class="fas fa-eye me-1"></i> 3.1K views</small>
              </div>
            </div>

            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Popular Article">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Politics</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">New Policy Reforms Proposed by Government</a></h6>
                <small class="text-muted"><i class="fas fa-eye me-1"></i> 2.1K views</small>
              </div>
            </div>

            <div class="d-flex">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Popular Article">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Technology</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Major Breakthrough in Renewable Energy Research</a></h6>
                <small class="text-muted"><i class="fas fa-eye me-1"></i> 2.4K views</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Newsletter -->
        <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
          <div class="card-body p-4">
            <h5 class="mb-3">Subscribe to Newsletter</h5>
            <p class="text-muted mb-4">Get the latest news and updates delivered directly to your inbox.</p>
            <form action="subscribe.php" method="post">
              <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Your email address" required>
              </div>
              <button type="submit" class="btn w-100" style="background-color: #9B5DE5; color: white;">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scroll to Top Button -->
  <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- footer -->
  <?php include 'include/footer.php' ?>

  <!-- bootstrap js -->
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
  <!-- js -->
  <script src="assets/js/script.js"></script>

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

  <style>
    /* Scroll to Top Button */
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

    /* Category Filters */
    .category-filters .btn {
      border-radius: 30px;
      transition: all 0.3s ease;
    }

    .category-filters .btn:hover {
      background-color: #9B5DE5 !important;
      color: white !important;
    }

    /* Card Hover Effect */
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Pagination Styling */
    .page-link {
      color: #9B5DE5;
    }

    .page-link:focus {
      box-shadow: 0 0 0 0.25rem rgba(155, 93, 229, 0.25);
    }

    .page-item.active .page-link {
      background-color: #9B5DE5;
      border-color: #9B5DE5;
    }
  </style>
</body>

</html>
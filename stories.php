<?php
// Story categories
$story_categories = ['All Stories', 'Travel', 'Culture', 'Adventure', 'Science', 'Personal', 'Historical', 'Nature'];

// Get the current category from URL parameter, default to 'All Stories'
$current_category = isset($_GET['category']) ? $_GET['category'] : 'All Stories';

// In a real implementation, you would fetch stories from a database
// For now, we'll use dummy data for demonstration
$stories = [
  // Travel Stories
  [
    'id' => 1,
    'title' => 'A Journey Through Ancient Ruins',
    'category' => 'Travel',
    'author' => 'Sarah Johnson',
    'author_role' => 'Travel Writer',
    'date' => 'April 8, 2025',
    'reads' => '3.2K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Exploring the forgotten temples of Southeast Asia revealed a world frozen in time, where ancient civilizations once thrived...'
  ],
  [
    'id' => 2,
    'title' => 'Lost in the Streets of Marrakech',
    'category' => 'Travel',
    'author' => 'James Wilson',
    'author_role' => 'Photojournalist',
    'date' => 'April 6, 2025',
    'reads' => '2.8K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'The labyrinthine streets of Marrakech\'s medina offer an intoxicating blend of colors, scents and sounds that transport you to another era...'
  ],

  // Culture Stories
  [
    'id' => 3,
    'title' => 'The Hidden Life of Mountain Communities',
    'category' => 'Culture',
    'author' => 'Michael Chen',
    'author_role' => 'Cultural Reporter',
    'date' => 'April 7, 2025',
    'reads' => '2.5K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'High in the Himalayas, isolated communities maintain traditions and ways of life that have remained unchanged for centuries...'
  ],
  [
    'id' => 4,
    'title' => 'Festivals of Light Around the World',
    'category' => 'Culture',
    'author' => 'Priya Sharma',
    'author_role' => 'Cultural Anthropologist',
    'date' => 'April 5, 2025',
    'reads' => '3.1K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'From Diwali to Hanukkah to Loy Krathong, cultures around the world celebrate light as a symbol of hope, knowledge, and new beginnings...'
  ],

  // Adventure Stories
  [
    'id' => 5,
    'title' => 'Surviving the Arctic Wilderness',
    'category' => 'Adventure',
    'author' => 'Alex Morgan',
    'author_role' => 'Expedition Leader',
    'date' => 'April 8, 2025',
    'reads' => '4.7K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'When our equipment failed 200 miles from the nearest settlement, we faced the ultimate test of survival in sub-zero temperatures...'
  ],
  [
    'id' => 6,
    'title' => 'Solo Sailing Across the Pacific',
    'category' => 'Adventure',
    'author' => 'Emma Rodriguez',
    'author_role' => 'Marine Explorer',
    'date' => 'April 4, 2025',
    'reads' => '3.9K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Forty-seven days alone on the world\'s largest ocean taught me more about myself than I had learned in a lifetime on land...'
  ],

  // Science Stories
  [
    'id' => 7,
    'title' => 'Underwater Exploration: New Species Discovered',
    'category' => 'Science',
    'author' => 'Emily Santos',
    'author_role' => 'Marine Biologist',
    'date' => 'April 7, 2025',
    'reads' => '3.5K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Deep in the Mariana Trench, our research expedition encountered life forms that challenge our understanding of adaptation and survival...'
  ],
  [
    'id' => 8,
    'title' => 'Living Among the Stars: Astronaut Diaries',
    'category' => 'Science',
    'author' => 'Robert Anderson',
    'author_role' => 'Space Correspondent',
    'date' => 'April 3, 2025',
    'reads' => '5.2K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'The personal journal entries of astronauts reveal the profound psychological impact of viewing Earth from orbit...'
  ],

  // Personal Stories
  [
    'id' => 9,
    'title' => 'Finding My Voice After Trauma',
    'category' => 'Personal',
    'author' => 'Jennifer Lee',
    'author_role' => 'Author & Advocate',
    'date' => 'April 5, 2025',
    'reads' => '6.1K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'My journey of recovery became an unexpected path to helping others find strength in their most vulnerable moments...'
  ],
  [
    'id' => 10,
    'title' => 'The Year I Lived Without Technology',
    'category' => 'Personal',
    'author' => 'David Thompson',
    'author_role' => 'Digital Minimalist',
    'date' => 'April 2, 2025',
    'reads' => '4.8K',
    'image' => 'https://via.placeholder.com/800x500',
    'excerpt' => 'Disconnecting from screens and reconnecting with reality transformed my relationships, productivity and mental health...'
  ]
];

// Filter stories by category if not 'All Stories'
if ($current_category !== 'All Stories') {
  $filtered_stories = array_filter($stories, function ($story) use ($current_category) {
    return $story['category'] === $current_category;
  });
} else {
  $filtered_stories = $stories;
}

// Pagination settings
$stories_per_page = 6;
$total_stories = count($filtered_stories);
$total_pages = ceil($total_stories / $stories_per_page);
$current_page = isset($_GET['page']) ? max(1, min($_GET['page'], $total_pages)) : 1;
$offset = ($current_page - 1) * $stories_per_page;

// Get stories for current page
$paged_stories = array_slice($filtered_stories, $offset, $stories_per_page);

// Featured stories (first 3 from all stories)
$featured_stories = array_slice($stories, 0, 3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'include/css-links.php' ?>

</head>

<body>
  <!-- navbar -->
  <?php include 'include/navbar.php' ?>

  <!--
  ================== 
  Featured Stories Hero
  ===================
  -->
  <section class="py-5 bg-dark text-white">
    <div class="container">
      <div class="row mb-4">
        <div class="col-12 text-center">
          <h1 class="display-4 fw-bold mb-3">Captivating Stories</h1>
          <p class="lead">Discover extraordinary narratives from around the world</p>
        </div>
      </div>

      <div class="row">
        <?php foreach ($featured_stories as $key => $story): ?>
          <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white border-0 h-100">
              <div class="position-relative">
                <img src="<?= htmlspecialchars($story['image']) ?>" class="card-img" alt="<?= htmlspecialchars($story['title']) ?>" style="height: 300px; object-fit: cover; opacity: 0.6;">
                <div class="card-img-overlay d-flex flex-column justify-content-end">
                  <div class="mb-2">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: #9B5DE5;">
                      <?= htmlspecialchars($story['category']) ?>
                    </span>
                  </div>
                  <h5 class="card-title h4 fw-bold"><?= htmlspecialchars($story['title']) ?></h5>
                  <div class="d-flex align-items-center mt-3">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Author" width="30" height="30">
                    <span class="small me-3"><?= htmlspecialchars($story['author']) ?></span>
                    <span class="small"><i class="far fa-eye me-1"></i> <?= htmlspecialchars($story['reads']) ?> reads</span>
                  </div>
                  <a href="story-detail.php?id=<?= $story['id'] ?>" class="stretched-link"></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!--
  ================== 
  Stories Content
  ===================
  -->
  <div class="container my-5">
    <div class="row">
      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- Category Filters -->
        <div class="mb-4 story-categories">
          <h2 class="mb-3">Browse Stories</h2>
          <div class="d-flex flex-wrap">
            <?php foreach ($story_categories as $category): ?>
              <a href="stories.php?category=<?= urlencode($category) ?>"
                class="btn me-2 mb-2 <?= $category === $current_category ? 'active' : '' ?>"
                style="<?= $category === $current_category ? 'background-color: #9B5DE5; color: white;' : 'background-color: #f8f9fa; color: #333;' ?>">
                <?= htmlspecialchars($category) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Stories Grid -->
        <div class="row">
          <?php if (empty($paged_stories)): ?>
            <div class="col-12">
              <div class="alert alert-info">
                No stories found for this category. Please try another category.
              </div>
            </div>
          <?php else: ?>
            <?php foreach ($paged_stories as $story): ?>
              <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                  <div class="position-relative">
                    <img src="<?= htmlspecialchars($story['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($story['title']) ?>" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                      <span class="badge rounded-pill px-3 py-2" style="background-color: #9B5DE5;">
                        <i class="fas fa-book-open me-1"></i> <?= htmlspecialchars($story['category']) ?>
                      </span>
                    </div>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title">
                      <a href="story-detail.php?id=<?= $story['id'] ?>" class="text-decoration-none text-dark">
                        <?= htmlspecialchars($story['title']) ?>
                      </a>
                    </h5>
                    <p class="card-text"><?= htmlspecialchars($story['excerpt']) ?></p>
                  </div>
                  <div class="card-footer bg-white border-0">
                    <div class="d-flex align-items-center">
                      <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="Author" width="40" height="40">
                      <div>
                        <p class="mb-0 fw-bold"><?= htmlspecialchars($story['author']) ?></p>
                        <small class="text-muted"><?= htmlspecialchars($story['author_role']) ?></small>
                      </div>
                      <div class="ms-auto">
                        <small class="text-muted me-2"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($story['date']) ?></small>
                        <small class="text-muted"><i class="far fa-eye me-1"></i> <?= htmlspecialchars($story['reads']) ?></small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <nav aria-label="Stories pagination" class="mt-5">
            <ul class="pagination justify-content-center">
              <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="stories.php?category=<?= urlencode($current_category) ?>&page=<?= $current_page - 1 ?>" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>

              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                  <a class="page-link" href="stories.php?category=<?= urlencode($current_category) ?>&page=<?= $i ?>"
                    style="<?= $i === $current_page ? 'background-color: #9B5DE5; border-color: #9B5DE5;' : '' ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="stories.php?category=<?= urlencode($current_category) ?>&page=<?= $current_page + 1 ?>" aria-label="Next">
                  <span aria-hidden="true">&raquo;</span>
                </a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4 mt-5 mt-lg-0">
        <!-- Stories of the Month -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Stories of the Month</h5>
          </div>
          <div class="card-body">
            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Story">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Personal</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Finding My Voice After Trauma</a></h6>
                <small class="text-muted"><i class="far fa-eye me-1"></i> 6.1K reads</small>
              </div>
            </div>

            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Story">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Science</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Living Among the Stars: Astronaut Diaries</a></h6>
                <small class="text-muted"><i class="far fa-eye me-1"></i> 5.2K reads</small>
              </div>
            </div>

            <div class="d-flex mb-3 pb-3 border-bottom">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Story">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Adventure</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Surviving the Arctic Wilderness</a></h6>
                <small class="text-muted"><i class="far fa-eye me-1"></i> 4.7K reads</small>
              </div>
            </div>

            <div class="d-flex">
              <img src="https://via.placeholder.com/100" class="rounded me-3" width="80" height="80" alt="Story">
              <div>
                <span class="badge mb-1" style="background-color: #9B5DE5;">Culture</span>
                <h6 class="mb-1"><a href="#" class="text-decoration-none text-dark">Festivals of Light Around the World</a></h6>
                <small class="text-muted"><i class="far fa-eye me-1"></i> 3.1K reads</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Featured Author -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Featured Author</h5>
          </div>
          <div class="card-body text-center">
            <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" width="120" height="120" alt="Featured Author">
            <h5 class="card-title">Sarah Johnson</h5>
            <p class="text-muted">Travel Writer & Photographer</p>
            <p class="card-text">Sarah has documented her travels across 47 countries, specializing in remote locations and cultural immersion experiences.</p>
            <div class="d-flex justify-content-center">
              <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle mx-1">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle mx-1">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle mx-1">
                <i class="fab fa-linkedin-in"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle mx-1">
                <i class="fas fa-globe"></i>
              </a>
            </div>
            <a href="#" class="btn mt-3" style="background-color: #9B5DE5; color: white;">View All Stories</a>
          </div>
        </div>

        <!-- Submit Your Story -->
        <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
          <div class="card-body p-4">
            <h5 class="mb-3">Share Your Story</h5>
            <p class="text-muted mb-4">Have an extraordinary experience to share? We welcome submissions from writers of all backgrounds.</p>
            <a href="#" class="btn w-100" style="background-color: #9B5DE5; color: white;">Submit Your Story</a>
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

  <?php include 'include/js-links.php' ?>


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
    .story-categories .btn {
      border-radius: 30px;
      transition: all 0.3s ease;
    }

    .story-categories .btn:hover {
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

    /* Featured Stories Overlay */
    .card-img-overlay {
      background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.1));
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
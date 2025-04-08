<?php
session_start();
include 'config/db.php';

// Story categories - these would ideally come from your categories table
$story_categories = ['All Stories', 'Travel', 'Culture', 'Adventure', 'Science', 'Personal', 'Historical', 'Nature'];

// Get the current category from URL parameter, default to 'All Stories'
$current_category = isset($_GET['category']) ? $_GET['category'] : 'All Stories';

// Fetch stories from database
// Note: Since there's no category column in the stories table based on your SQL dump,
// we'll fetch all stories and filter them on the PHP side for now
$sql = "SELECT id, title, author, author_role, DATE_FORMAT(created_at, '%M %d, %Y') as date, 
        view_count, image, excerpt, featured
        FROM stories 
        ORDER BY created_at DESC";
$result = $conn->query($sql);
$all_stories = [];

while ($row = $result->fetch_assoc()) {
  // Format view count for display
  $row['reads'] = $row['view_count'];
  if ($row['reads'] >= 1000) {
    $row['reads'] = number_format($row['reads'] / 1000, 1) . 'K';
  }

  // Set default image if none exists
  if (empty($row['image'])) {
    $row['image'] = 'https://via.placeholder.com/800x500';
  }

  // For demo purposes, assign a random category if not filtering
  // In production, you would have a proper category column or a relationship table
  if (!isset($row['category'])) {
    $category_index = array_rand(array_slice($story_categories, 1)); // Skip 'All Stories'
    $row['category'] = $story_categories[$category_index + 1];
  }

  $all_stories[] = $row;
}

// Filter stories by category if not 'All Stories'
if ($current_category !== 'All Stories') {
  $filtered_stories = array_filter($all_stories, function ($story) use ($current_category) {
    return $story['category'] === $current_category;
  });
} else {
  $filtered_stories = $all_stories;
}

// Pagination settings
$stories_per_page = 6;
$total_stories = count($filtered_stories);
$total_pages = ceil($total_stories / $stories_per_page);
$current_page = isset($_GET['page']) ? max(1, min($_GET['page'], $total_pages)) : 1;
$offset = ($current_page - 1) * $stories_per_page;

// Get stories for current page
$paged_stories = array_slice($filtered_stories, $offset, $stories_per_page);

// Featured stories (get stories marked as featured)
$featured_stories = array_filter($all_stories, function ($story) {
  return $story['featured'] == 1;
});
$featured_stories = array_slice($featured_stories, 0, 3);

// If we don't have enough featured stories, get the most recent ones
if (count($featured_stories) < 3) {
  $needed = 3 - count($featured_stories);
  $non_featured = array_filter($all_stories, function ($story) use ($featured_stories) {
    foreach ($featured_stories as $featured) {
      if ($featured['id'] == $story['id']) {
        return false;
      }
    }
    return true;
  });

  usort($non_featured, function ($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
  });

  $featured_stories = array_merge($featured_stories, array_slice($non_featured, 0, $needed));
}

// Stories of the Month (most viewed stories)
$popular_stories = $all_stories;
usort($popular_stories, function ($a, $b) {
  return $b['view_count'] - $a['view_count'];
});
$popular_stories = array_slice($popular_stories, 0, 4);

// Featured Author (get the author with the most stories)
$authors = [];
foreach ($all_stories as $story) {
  if (!isset($authors[$story['author']])) {
    $authors[$story['author']] = [
      'name' => $story['author'],
      'role' => $story['author_role'],
      'count' => 0
    ];
  }
  $authors[$story['author']]['count']++;
}

usort($authors, function ($a, $b) {
  return $b['count'] - $a['count'];
});

$featured_author = !empty($authors) ? reset($authors) : null;
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
                  <a href="story-detail.php?id=<?= $story['id'] ?>" class="stretched-link">
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
                  </a>
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
            <?php foreach ($popular_stories as $index => $story): ?>
              <div class="d-flex <?= $index < count($popular_stories) - 1 ? 'mb-3 pb-3 border-bottom' : '' ?>">
                <img src="<?= htmlspecialchars($story['image']) ?>" class="rounded me-3" width="80" height="80" alt="Story" style="object-fit: cover;">
                <div>
                  <span class="badge mb-1" style="background-color: #9B5DE5;"><?= htmlspecialchars($story['category']) ?></span>
                  <h6 class="mb-1"><a href="story-detail.php?id=<?= $story['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($story['title']) ?></a></h6>
                  <small class="text-muted"><i class="far fa-eye me-1"></i> <?= htmlspecialchars($story['reads']) ?> reads</small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Featured Author -->
        <?php if ($featured_author): ?>
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
              <h5 class="mb-0">Featured Author</h5>
            </div>
            <div class="card-body text-center">
              <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" width="120" height="120" alt="Featured Author">
              <h5 class="card-title"><?= htmlspecialchars($featured_author['name']) ?></h5>
              <p class="text-muted"><?= htmlspecialchars($featured_author['role']) ?></p>
              <p class="card-text">Published <?= $featured_author['count'] ?> <?= $featured_author['count'] == 1 ? 'story' : 'stories' ?> on our platform.</p>
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
              <a href="stories.php?author=<?= urlencode($featured_author['name']) ?>" class="btn mt-3" style="background-color: #9B5DE5; color: white;">View All Stories</a>
            </div>
          </div>
        <?php endif; ?>

        <!-- Submit Your Story -->
        <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
          <div class="card-body p-4">
            <h5 class="mb-3">Share Your Story</h5>
            <p class="text-muted mb-4">Have an extraordinary experience to share? We welcome submissions from writers of all backgrounds.</p>
            <a href="submit-story.php" class="btn w-100" style="background-color: #9B5DE5; color: white;">Submit Your Story</a>
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
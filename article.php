<?php
include 'config/db.php';

// Get pagination parameters
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 9;
$offset = ($page - 1) * $items_per_page;

// Fetch categories for sidebar
$categories = [];
$fetchCategoriesSql = "SELECT c.id, c.name, COUNT(a.id) as article_count 
                       FROM categories c 
                       LEFT JOIN articles a ON c.id = a.category_id 
                       GROUP BY c.id 
                       ORDER BY c.name ASC";
$categoryResult = $conn->query($fetchCategoriesSql);

if ($categoryResult && $categoryResult->num_rows > 0) {
  while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row;
  }
}

// Initialize filter variables
$category_filter = '';
$where_clause = '';
$params = [];
$param_types = '';

// Apply category filter if provided
if (isset($_GET['category']) && !empty($_GET['category'])) {
  $category_id = intval($_GET['category']);
  $where_clause = "WHERE a.category_id = ?";
  $params[] = $category_id;
  $param_types .= 'i';

  // Get category name for title
  $category_name = "Articles";
  foreach ($categories as $category) {
    if ($category['id'] == $category_id) {
      $category_name = $category['name'];
      break;
    }
  }
  $page_title = $category_name . " Articles";
} else {
  $page_title = "All Articles";
}

// Count total articles with filter
$count_sql = "SELECT COUNT(*) as total FROM articles a $where_clause";
$count_stmt = $conn->prepare($count_sql);

if (!empty($params)) {
  $count_stmt->bind_param($param_types, ...$params);
}

$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_articles = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_articles / $items_per_page);

// Fetch articles with optional filter, and include category info
$article_sql = "SELECT a.*, c.name as category_name 
               FROM articles a 
               LEFT JOIN categories c ON a.category_id = c.id 
               $where_clause 
               ORDER BY a.created_at DESC 
               LIMIT ?, ?";

$article_stmt = $conn->prepare($article_sql);

if (!empty($params)) {
  $article_stmt->bind_param($param_types . 'ii', ...[...$params, $offset, $items_per_page]);
} else {
  $article_stmt->bind_param('ii', $offset, $items_per_page);
}

$article_stmt->execute();
$article_result = $article_stmt->get_result();
$articles = [];

while ($row = $article_result->fetch_assoc()) {
  $articles[] = $row;
}

// Fetch popular tags
$popular_tags = [];
$tags_sql = "SELECT DISTINCT tags FROM articles WHERE tags IS NOT NULL AND tags != ''";
$tags_result = $conn->query($tags_sql);

if ($tags_result && $tags_result->num_rows > 0) {
  $all_tags = [];
  while ($row = $tags_result->fetch_assoc()) {
    $tag_array = explode(',', $row['tags']);
    foreach ($tag_array as $tag) {
      $tag = trim($tag);
      if (!empty($tag)) {
        if (isset($all_tags[$tag])) {
          $all_tags[$tag]++;
        } else {
          $all_tags[$tag] = 1;
        }
      }
    }
  }

  // Sort by count and take top 10
  arsort($all_tags);
  $popular_tags = array_slice(array_keys($all_tags), 0, 10);
}

// Fetch recent articles for sidebar
$recent_articles = [];
$recent_sql = "SELECT id, title, image, created_at FROM articles ORDER BY created_at DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);

if ($recent_result && $recent_result->num_rows > 0) {
  while ($row = $recent_result->fetch_assoc()) {
    $recent_articles[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $page_title; ?> - Your Site Name</title>
  <?php include 'include/css-links.php' ?>
  <style>
    .article-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .article-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .badge-featured {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #9B5DE5;
      color: white;
      z-index: 10;
    }

    .category-badge {
      background-color: #9B5DE5;
      color: white;
    }

    .pagination .page-item.active .page-link {
      background-color: #9B5DE5;
      border-color: #9B5DE5;
    }

    .pagination .page-link {
      color: #9B5DE5;
    }

    .sidebar-heading {
      border-bottom: 2px solid #9B5DE5;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .category-active {
      background-color: #9B5DE5 !important;
      color: white !important;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <?php include 'include/navbar.php' ?>

  <!-- Page Header -->
  <div class="container-fluid bg-light py-5 mb-5">
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto text-center">
          <h1 class="fw-bold mb-3"><?php echo $page_title; ?></h1>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
              <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color: #9B5DE5;">Home</a></li>
              <?php if (isset($_GET['category'])): ?>
                <li class="breadcrumb-item"><a href="article.php" class="text-decoration-none" style="color: #9B5DE5;">Articles</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $category_name; ?></li>
              <?php else: ?>
                <li class="breadcrumb-item active" aria-current="page">Articles</li>
              <?php endif; ?>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container">
    <div class="row">
      <!-- Article Listings -->
      <div class="col-lg-8">
        <?php if (count($articles) > 0): ?>
          <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
            <?php foreach ($articles as $article): ?>
              <div class="col">
                <div class="card h-100 border-0 shadow-sm article-card">
                  <?php if ($article['featured']): ?>
                    <span class="badge badge-featured">Featured</span>
                  <?php endif; ?>
                  <img src="<?php echo htmlspecialchars($article['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($article['title']); ?>">
                  <div class="card-body">
                    <?php if (!empty($article['category_name'])): ?>
                      <a href="article.php?category=<?php echo $article['category_id']; ?>" class="text-decoration-none">
                        <span class="badge category-badge mb-2"><?php echo htmlspecialchars($article['category_name']); ?></span>
                      </a>
                    <?php endif; ?>
                    <h5 class="card-title">
                      <a href="article-details.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" class="text-decoration-none text-dark">
                        <?php echo htmlspecialchars($article['title']); ?>
                      </a>
                    </h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                  </div>
                  <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="small text-muted">
                        <i class="bi bi-calendar me-1"></i> <?php echo date('M d, Y', strtotime($article['created_at'])); ?>
                      </div>
                      <a href="article-details.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" class="btn btn-sm" style="background-color: #9B5DE5; color: white;">
                        Read More
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4 mb-5">
              <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                  <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>">
                      <?php echo $i; ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>

        <?php else: ?>
          <div class="text-center py-5">
            <div class="mb-3">
              <i class="bi bi-newspaper" style="font-size: 3rem; color: #9B5DE5;"></i>
            </div>
            <h3>No Articles Found</h3>
            <p class="text-muted">Sorry, there are no articles in this category yet.</p>
            <a href="article.php" class="btn btn-primary mt-2" style="background-color: #9B5DE5; border-color: #9B5DE5;">View All Articles</a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Categories -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h4 class="sidebar-heading">Categories</h4>
            <div class="list-group">
              <a href="article.php" class="list-group-item list-group-item-action <?php echo !isset($_GET['category']) ? 'category-active' : ''; ?>">
                All Articles <span class="badge bg-secondary float-end"><?php echo $total_articles; ?></span>
              </a>
              <?php foreach ($categories as $category): ?>
                <a href="article.php?category=<?php echo $category['id']; ?>"
                  class="list-group-item list-group-item-action <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'category-active' : ''; ?>">
                  <?php echo htmlspecialchars($category['name']); ?>
                  <span class="badge bg-secondary float-end"><?php echo $category['article_count']; ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Recent Articles -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h4 class="sidebar-heading">Recent Articles</h4>
            <?php foreach ($recent_articles as $recent): ?>
              <div class="d-flex mb-3 pb-3 <?php echo ($recent !== end($recent_articles)) ? 'border-bottom' : ''; ?>">
                <img src="<?php echo htmlspecialchars($recent['image']); ?>" class="flex-shrink-0 me-3 rounded" width="60" height="60" style="object-fit: cover;" alt="<?php echo htmlspecialchars($recent['title']); ?>">
                <div>
                  <h6 class="mb-1">
                    <a href="article-details.php?id=<?php echo $recent['id']; ?>" class="text-decoration-none text-dark">
                      <?php echo htmlspecialchars($recent['title']); ?>
                    </a>
                  </h6>
                  <small class="text-muted"><i class="bi bi-calendar me-1"></i> <?php echo date('M d, Y', strtotime($recent['created_at'])); ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Popular Tags -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h4 class="sidebar-heading">Popular Tags</h4>
            <div class="d-flex flex-wrap">
              <?php foreach ($popular_tags as $tag): ?>
                <a href="article.php?tag=<?php echo urlencode($tag); ?>" class="badge bg-light text-dark text-decoration-none me-2 mb-2 py-2 px-3">
                  <?php echo htmlspecialchars($tag); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Subscribe -->
        <div class="card border-0 shadow-sm" style="background-color: #f8f9fa;">
          <div class="card-body p-4">
            <h4 class="sidebar-heading">Subscribe to Newsletter</h4>
            <p class="text-muted">Get the latest articles and updates delivered directly to your inbox.</p>
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

  <!-- Footer -->
  <?php include 'include/footer.php' ?>

  <!-- JavaScript -->
  <?php include 'include/js-links.php' ?>
</body>

</html>
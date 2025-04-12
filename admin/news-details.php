<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: manage-news.php");
  exit;
}

$news_id = intval($_GET['id']);
$errorMsg = "";
$successMsg = "";

// Fetch news details
$fetchSql = "SELECT n.*, c.name as category_name 
            FROM news n 
            LEFT JOIN categories c ON n.category_id = c.id 
            WHERE n.id = ?";
$fetchStmt = $conn->prepare($fetchSql);
$fetchStmt->bind_param("i", $news_id);
$fetchStmt->execute();
$result = $fetchStmt->get_result();

if ($result->num_rows === 0) {
  header("Location: manage-news.php");
  exit;
}

$news = $result->fetch_assoc();

// Parse tags
$news_tags = [];
if (!empty($news['tags'])) {
  $news_tags = explode(',', $news['tags']);
  $news_tags = array_map('trim', $news_tags);
}

// Handle Delete News
if (isset($_POST['delete_news'])) {
  // Get news image to delete file
  $imagePath = $news['image'];
  // Delete file if it's not the default placeholder
  if ($imagePath && $imagePath != 'https://via.placeholder.com/800x500' && strpos($imagePath, 'uploads/') !== false) {
    $fullPath = '../' . $imagePath;
    if (file_exists($fullPath)) {
      unlink($fullPath);
    }
  }

  // Delete news
  $deleteSql = "DELETE FROM news WHERE id = ?";
  $deleteStmt = $conn->prepare($deleteSql);
  $deleteStmt->bind_param("i", $news_id);

  if ($deleteStmt->execute()) {
    $_SESSION['success_message'] = "News deleted successfully";
    header("Location: manage-news.php");
    exit;
  } else {
    $errorMsg = "Error deleting news: " . $conn->error;
  }
}

// Handle Toggle Featured Status
if (isset($_POST['toggle_featured'])) {
  $featured = $news['featured'] ? 0 : 1; // Toggle current value

  $updateSql = "UPDATE news SET featured = ? WHERE id = ?";
  $updateStmt = $conn->prepare($updateSql);
  $updateStmt->bind_param("ii", $featured, $news_id);

  if ($updateStmt->execute()) {
    $successMsg = "Featured status updated successfully";
    // Refresh news data
    $fetchStmt->execute();
    $result = $fetchStmt->get_result();
    $news = $result->fetch_assoc();
  } else {
    $errorMsg = "Error updating featured status: " . $conn->error;
  }
}

// Get comment statistics
$commentStats = [
  'total' => 0,
  'approved' => 0,
  'pending' => 0,
  'spam' => 0
];

$commentStatsSql = "SELECT status, COUNT(*) as count FROM comments WHERE story_id = ? GROUP BY status";
$commentStatsStmt = $conn->prepare($commentStatsSql);
$commentStatsStmt->bind_param("i", $news_id);
$commentStatsStmt->execute();
$commentStatsResult = $commentStatsStmt->get_result();

while ($row = $commentStatsResult->fetch_assoc()) {
  $commentStats[$row['status']] = $row['count'];
  $commentStats['total'] += $row['count'];
}

// Fetch recent comments for this news
$recentComments = [];
$recentCommentsSql = "SELECT c.*, DATE_FORMAT(c.created_at, '%M %d, %Y at %h:%i %p') as formatted_date 
                     FROM comments c 
                     WHERE c.story_id = ? 
                     ORDER BY c.created_at DESC LIMIT 5";
$recentCommentsStmt = $conn->prepare($recentCommentsSql);
$recentCommentsStmt->bind_param("i", $news_id);
$recentCommentsStmt->execute();
$recentCommentsResult = $recentCommentsStmt->get_result();

while ($row = $recentCommentsResult->fetch_assoc()) {
  $recentComments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News Details - Admin Panel</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f9;
      overflow-x: hidden;
    }

    .news-content img {
      max-width: 100%;
      height: auto;
    }

    .action-buttons .btn {
      padding: 0.5rem 1rem;
    }

    .badge-featured {
      background-color: #9B5DE5;
      color: white;
    }

    .meta-info span {
      display: inline-block;
      margin-right: 1.5rem;
    }

    .comment-wrapper {
      border-left: 3px solid #dee2e6;
      padding-left: 1rem;
    }

    .tag-badge {
      background-color: #f0f0f0;
      color: #333;
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
      font-weight: normal;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">News Details</h2>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="manage-news.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Back to News List
        </a>
      </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($errorMsg)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $errorMsg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $successMsg; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-lg-8">
        <!-- News Details Card -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title mb-0">News Information</h5>
            <div class="action-buttons">
              <a href="../news-details.php?id=<?php echo $news['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i> View on Site
              </a>
              <a href="edit_news.php?id=<?php echo $news['id']; ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash"></i> Delete
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- Featured Image -->
            <div class="text-center mb-4">
              <img src="../<?php echo htmlspecialchars($news['image']); ?>" class="img-fluid rounded" style="max-height: 400px;" alt="<?php echo htmlspecialchars($news['title']); ?>">
            </div>

            <!-- News Meta Information -->
            <div class="meta-info mb-4 pb-3 border-bottom">
              <span><i class="bi bi-calendar me-1"></i> Published: <?php echo date('M d, Y', strtotime($news['created_at'])); ?></span>
              <span><i class="bi bi-eye me-1"></i> Views: <?php echo number_format($news['view_count']); ?></span>
              <?php if ($news['featured']): ?>
                <span class="badge badge-featured">Featured</span>
              <?php endif; ?>
              <form method="post" action="" class="d-inline">
                <button type="submit" name="toggle_featured" class="btn btn-sm <?php echo $news['featured'] ? 'btn-outline-secondary' : 'btn-outline-primary'; ?>">
                  <?php echo $news['featured'] ? 'Remove Featured Status' : 'Mark as Featured'; ?>
                </button>
              </form>
            </div>

            <!-- News Title -->
            <h3 class="mb-3"><?php echo htmlspecialchars($news['title']); ?></h3>

            <!-- Category and Tags -->
            <div class="mb-3">
              <?php if (!empty($news['category_name'])): ?>
                <span class="me-2">Category: <span class="badge bg-secondary"><?php echo htmlspecialchars($news['category_name']); ?></span></span>
              <?php endif; ?>

              <?php if (!empty($news_tags)): ?>
                <span>Tags:
                  <?php foreach ($news_tags as $tag): ?>
                    <span class="badge tag-badge"><?php echo htmlspecialchars($tag); ?></span>
                  <?php endforeach; ?>
                </span>
              <?php endif; ?>
            </div>

            <!-- Author Info -->
            <div class="mb-4">
              <p class="mb-1"><strong>Author:</strong> <?php echo htmlspecialchars($news['author']); ?></p>
              <p class="mb-0"><strong>Role:</strong> <?php echo htmlspecialchars($news['author_role']); ?></p>
            </div>

            <!-- News Excerpt -->
            <div class="mb-4">
              <h5>Excerpt</h5>
              <div class="p-3 bg-light rounded">
                <?php echo htmlspecialchars($news['excerpt']); ?>
              </div>
            </div>

            <!-- News Content -->
            <div class="mb-4">
              <h5>Content</h5>
              <div class="news-content">
                <?php echo $news['content']; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Comment Statistics -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">Comment Statistics</h5>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-6 mb-3">
                <div class="p-3 rounded bg-light">
                  <h2 class="mb-1"><?php echo $commentStats['total']; ?></h2>
                  <p class="mb-0 text-muted">Total Comments</p>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="p-3 rounded bg-light">
                  <h2 class="mb-1"><?php echo $commentStats['approved']; ?></h2>
                  <p class="mb-0 text-muted">Approved</p>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded bg-light">
                  <h2 class="mb-1"><?php echo $commentStats['pending']; ?></h2>
                  <p class="mb-0 text-muted">Pending</p>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 rounded bg-light">
                  <h2 class="mb-1"><?php echo $commentStats['spam']; ?></h2>
                  <p class="mb-0 text-muted">Spam</p>
                </div>
              </div>
            </div>
            <div class="d-grid mt-3">
              <a href="comments.php?story_id=<?php echo $news_id; ?>" class="btn btn-outline-primary">Manage Comments</a>
            </div>
          </div>
        </div>

        <!-- Recent Comments -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">Recent Comments</h5>
          </div>
          <div class="card-body">
            <?php if (empty($recentComments)): ?>
              <p class="text-center text-muted my-3">No comments yet</p>
            <?php else: ?>
              <?php foreach ($recentComments as $comment): ?>
                <div class="mb-3 pb-3 <?php echo ($comment !== end($recentComments)) ? 'border-bottom' : ''; ?>">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                      <h6 class="mb-0"><?php echo htmlspecialchars($comment['name']); ?></h6>
                      <small class="text-muted"><?php echo htmlspecialchars($comment['formatted_date']); ?></small>
                    </div>
                    <span class="badge <?php
                                        if ($comment['status'] === 'approved') echo 'bg-success';
                                        elseif ($comment['status'] === 'pending') echo 'bg-warning text-dark';
                                        else echo 'bg-danger';
                                        ?>">
                      <?php echo ucfirst($comment['status']); ?>
                    </span>
                  </div>
                  <div class="comment-wrapper">
                    <p class="mb-0 small"><?php echo nl2br(htmlspecialchars(substr($comment['comment'], 0, 100) . (strlen($comment['comment']) > 100 ? '...' : ''))); ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
              <?php if (count($recentComments) < $commentStats['total']): ?>
                <div class="text-center">
                  <a href="comments.php?story_id=<?php echo $news_id; ?>" class="btn btn-sm btn-outline-secondary">View All Comments</a>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

        <!-- SEO Information -->
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">SEO Information</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <h6>Slug</h6>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" value="<?php echo htmlspecialchars($news['slug']); ?>" readonly>
                <button class="btn btn-outline-secondary btn-sm copy-btn" type="button" data-clipboard-text="<?php echo htmlspecialchars($news['slug']); ?>">
                  <i class="bi bi-clipboard"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <h6>Full URL</h6>
              <div class="input-group">
                <input type="text" class="form-control form-control-sm" value="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . '/news-details.php?slug=' . $news['slug']); ?>" readonly>
                <button class="btn btn-outline-secondary btn-sm copy-btn" type="button" data-clipboard-text="<?php echo htmlspecialchars($_SERVER['HTTP_HOST'] . '/news-details.php?slug=' . $news['slug']); ?>">
                  <i class="bi bi-clipboard"></i>
                </button>
              </div>
            </div>
            <div>
              <h6>Meta Description</h6>
              <p class="small text-muted"><?php echo htmlspecialchars(substr($news['excerpt'], 0, 160) . (strlen($news['excerpt']) > 160 ? '...' : '')); ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this news article?</p>
          <p><strong><?php echo htmlspecialchars($news['title']); ?></strong></p>
          <p class="text-danger mb-0">This action cannot be undone. All associated comments will also be deleted.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form method="post" action="">
            <button type="submit" name="delete_news" class="btn btn-danger">Delete Permanently</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery, Bootstrap JS and Clipboard.js -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

  <script>
    // Initialize Clipboard.js
    document.addEventListener('DOMContentLoaded', function() {
      var clipboard = new ClipboardJS('.copy-btn');

      clipboard.on('success', function(e) {
        // Change button text temporarily
        const originalHTML = e.trigger.innerHTML;
        e.trigger.innerHTML = '<i class="bi bi-check"></i>';

        setTimeout(function() {
          e.trigger.innerHTML = originalHTML;
        }, 2000);

        e.clearSelection();
      });

      // Auto dismiss alerts after 5 seconds
      setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 5000);
    });
  </script>
</body>

</html>
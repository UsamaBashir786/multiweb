<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Initialize variables
$errorMsg = "";
$successMsg = "";

// Handle Delete News
if (isset($_POST['delete_news'])) {
  $newsId = $_POST['news_id'];

  // Get news image to delete file
  $getImageSql = "SELECT image FROM news WHERE id = ?";
  $getImageStmt = $conn->prepare($getImageSql);
  $getImageStmt->bind_param("i", $newsId);
  $getImageStmt->execute();
  $result = $getImageStmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $imagePath = $row['image'];
    // Delete file if it's not the default placeholder
    if ($imagePath && $imagePath != 'https://via.placeholder.com/800x500' && strpos($imagePath, 'uploads/') !== false) {
      $fullPath = '../' . $imagePath;
      if (file_exists($fullPath)) {
        unlink($fullPath);
      }
    }
  }

  // Delete news
  $deleteSql = "DELETE FROM news WHERE id = ?";
  $deleteStmt = $conn->prepare($deleteSql);
  $deleteStmt->bind_param("i", $newsId);

  if ($deleteStmt->execute()) {
    $successMsg = "News deleted successfully";
  } else {
    $errorMsg = "Error deleting news: " . $conn->error;
  }
}

// Handle Featured Status Toggle
if (isset($_POST['toggle_featured'])) {
  $newsId = $_POST['news_id'];
  $featured = $_POST['featured'] ? 0 : 1; // Toggle current value

  $updateSql = "UPDATE news SET featured = ? WHERE id = ?";
  $updateStmt = $conn->prepare($updateSql);
  $updateStmt->bind_param("ii", $featured, $newsId);

  if ($updateStmt->execute()) {
    $successMsg = "News featured status updated";
  } else {
    $errorMsg = "Error updating news: " . $conn->error;
  }
}

// Fetch all news with category names
$news_items = [];
$fetchSql = "SELECT n.*, c.name as category_name FROM news n 
             LEFT JOIN categories c ON n.category_id = c.id 
             ORDER BY n.created_at DESC";
$result = $conn->query($fetchSql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $news_items[] = $row;
  }
}

// Pagination settings
$items_per_page = 10;
$total_items = count($news_items);
$total_pages = ceil($total_items / $items_per_page);
$current_page = isset($_GET['page']) ? max(1, min($_GET['page'], $total_pages)) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Get news for current page
$paged_items = array_slice($news_items, $offset, $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage News - Admin Panel</title>
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

    .table-responsive {
      overflow-x: auto;
    }

    .news-image {
      width: 80px;
      height: 50px;
      object-fit: cover;
      border-radius: 4px;
    }

    .badge-featured {
      background-color: #9B5DE5;
      color: white;
    }

    .table th {
      white-space: nowrap;
    }

    .table td {
      vertical-align: middle;
    }

    .text-truncate-custom {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
    }

    .actions-column {
      white-space: nowrap;
      width: 180px;
    }

    .filter-row {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 0.25rem;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">Manage News</h2>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="add_news.php" class="btn btn-success">
          <i class="bi bi-plus-circle me-1"></i> Add News
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

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <?php if (count($news_items) > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col" width="80">Image</th>
                  <th scope="col">Title</th>
                  <th scope="col">Author</th>
                  <th scope="col">Category</th>
                  <th scope="col">Views</th>
                  <th scope="col">Date</th>
                  <th scope="col">Featured</th>
                  <th scope="col" class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($paged_items as $item): ?>
                  <tr>
                    <td>
                      <img src="../<?php echo htmlspecialchars($item['image']); ?>" alt="News thumbnail" class="news-image">
                    </td>
                    <td>
                      <div class="text-truncate-custom">
                        <?php echo htmlspecialchars($item['title']); ?>
                      </div>
                    </td>
                    <td><?php echo htmlspecialchars($item['author']); ?></td>
                    <td>
                      <?php if (!empty($item['category_name'])): ?>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($item['category_name']); ?></span>
                      <?php else: ?>
                        <span class="badge bg-light text-dark">Uncategorized</span>
                      <?php endif; ?>
                    </td>
                    <td><?php echo number_format($item['view_count']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                    <td>
                      <form method="post" action="">
                        <input type="hidden" name="news_id" value="<?php echo $item['id']; ?>">
                        <input type="hidden" name="featured" value="<?php echo $item['featured']; ?>">
                        <button type="submit" name="toggle_featured" class="btn btn-sm <?php echo $item['featured'] ? 'badge-featured' : 'btn-outline-secondary'; ?>">
                          <?php if ($item['featured']): ?>
                            <i class="bi bi-star-fill me-1"></i> Featured
                          <?php else: ?>
                            <i class="bi bi-star me-1"></i> Regular
                          <?php endif; ?>
                        </button>
                      </form>
                    </td>
                    <td class="text-end actions-column">
                      <a href="news-details.php?id=<?php echo $item['id']; ?>" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i> View
                      </a>
                      <a href="edit_news.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                      </a>
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $item['id']; ?>">
                        <i class="bi bi-trash"></i> Delete
                      </button>

                      <!-- Delete Confirmation Modal -->
                      <div class="modal fade" id="deleteModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="deleteModalLabel<?php echo $item['id']; ?>">Confirm Delete</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                              <p>Are you sure you want to delete the news:</p>
                              <p class="fw-bold"><?php echo htmlspecialchars($item['title']); ?></p>
                              <p class="text-danger mb-0">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <form method="post" action="" class="d-inline">
                                <input type="hidden" name="news_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="delete_news" class="btn btn-danger">Delete News</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <?php if ($total_pages > 1): ?>
            <div class="card-footer bg-white border-0 py-3">
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                  <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>

                  <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                      <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                  <?php endfor; ?>

                  <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="p-4 text-center">
            <div class="mb-3">
              <i class="bi bi-newspaper text-muted" style="font-size: 3rem;"></i>
            </div>
            <h5>No News Found</h5>
            <p class="text-muted">Get started by adding your first news item.</p>
            <a href="add_news.php" class="btn btn-primary">
              <i class="bi bi-plus-circle me-1"></i> Add News
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    // Auto dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
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
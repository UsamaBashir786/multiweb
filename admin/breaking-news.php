<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Initialize variables
$id = "";
$title = "";
$link = "";
$icon = "fas fa-bolt"; // Default icon
$is_active = 1;
$priority = 0;
$errorMsg = "";
$successMsg = "";

// Handle Add Breaking News
if (isset($_POST['add_news'])) {
  // Get form data
  $title = trim($_POST['title']);
  $link = trim($_POST['link']);
  $icon = trim($_POST['icon']);
  $is_active = isset($_POST['is_active']) ? 1 : 0;
  $priority = intval($_POST['priority']);

  // Validate input
  if (empty($title)) {
    $errorMsg = "News title cannot be empty";
  } else {
    // Insert new breaking news
    $insertSql = "INSERT INTO breaking_news (title, link, icon, is_active, priority) VALUES (?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("sssis", $title, $link, $icon, $is_active, $priority);

    if ($insertStmt->execute()) {
      $successMsg = "Breaking news added successfully";
      // Reset form
      $title = "";
      $link = "";
      $icon = "fas fa-bolt"; // Default icon
      $is_active = 1;
      $priority = 0;
    } else {
      $errorMsg = "Error adding breaking news: " . $conn->error;
    }
  }
}

// Handle Update Breaking News
if (isset($_POST['update_news'])) {
  // Get form data
  $id = $_POST['news_id'];
  $title = trim($_POST['title']);
  $link = trim($_POST['link']);
  $icon = trim($_POST['icon']);
  $is_active = isset($_POST['is_active']) ? 1 : 0;
  $priority = intval($_POST['priority']);

  // Validate input
  if (empty($title)) {
    $errorMsg = "News title cannot be empty";
  } else {
    // Update breaking news
    $updateSql = "UPDATE breaking_news SET title = ?, link = ?, icon = ?, is_active = ?, priority = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssiii", $title, $link, $icon, $is_active, $priority, $id);

    if ($updateStmt->execute()) {
      $successMsg = "Breaking news updated successfully";
      // Reset form
      $id = "";
      $title = "";
      $link = "";
      $icon = "fas fa-bolt"; // Default icon
      $is_active = 1;
      $priority = 0;
    } else {
      $errorMsg = "Error updating breaking news: " . $conn->error;
    }
  }
}

// Handle Delete Breaking News
if (isset($_POST['delete_news'])) {
  $newsId = $_POST['news_id'];

  // Delete breaking news
  $deleteSql = "DELETE FROM breaking_news WHERE id = ?";
  $deleteStmt = $conn->prepare($deleteSql);
  $deleteStmt->bind_param("i", $newsId);

  if ($deleteStmt->execute()) {
    $successMsg = "Breaking news deleted successfully";
  } else {
    $errorMsg = "Error deleting breaking news: " . $conn->error;
  }
}

// Handle Edit (Populate Form)
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
  $editId = $_GET['edit'];

  // Fetch breaking news details
  $fetchSql = "SELECT * FROM breaking_news WHERE id = ?";
  $fetchStmt = $conn->prepare($fetchSql);
  $fetchStmt->bind_param("i", $editId);
  $fetchStmt->execute();
  $result = $fetchStmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $title = $row['title'];
    $link = $row['link'];
    $icon = $row['icon'];
    $is_active = $row['is_active'];
    $priority = $row['priority'];
  }
}

// Fetch all breaking news
$news_items = [];
$fetchSql = "SELECT * FROM breaking_news ORDER BY priority DESC, created_at DESC";
$result = $conn->query($fetchSql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $news_items[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Breaking News Management - Admin Panel</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f9;
      overflow-x: hidden;
    }

    .required-field::after {
      content: "*";
      color: red;
      margin-left: 4px;
    }

    .icon-preview {
      font-size: 1.5rem;
      margin-right: 10px;
      width: 40px;
      text-align: center;
    }

    .status-badge.active {
      background-color: #28a745;
    }

    .status-badge.inactive {
      background-color: #6c757d;
    }

    .icon-select-option {
      display: flex;
      align-items: center;
      padding: 8px 10px;
      cursor: pointer;
      transition: background-color 0.2s;
      border-radius: 4px;
    }

    .icon-select-option:hover {
      background-color: #f8f9fa;
    }

    .icon-select-option.selected {
      background-color: #e9ecef;
    }

    .icon-selector {
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      padding: 0.5rem;
      margin-top: 0.5rem;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col">
        <h2 class="mb-0">Breaking News Management</h2>
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
      <!-- Add/Edit Breaking News Form -->
      <div class="col-md-4">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0"><?php echo empty($id) ? 'Add New Breaking News' : 'Edit Breaking News'; ?></h5>
          </div>
          <div class="card-body">
            <form method="post" action="">
              <?php if (!empty($id)): ?>
                <input type="hidden" name="news_id" value="<?php echo $id; ?>">
              <?php endif; ?>

              <div class="mb-3">
                <label for="title" class="form-label required-field">News Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
              </div>

              <div class="mb-3">
                <label for="link" class="form-label">Link (URL)</label>
                <input type="url" class="form-control" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>" placeholder="https://example.com/news">
                <div class="form-text">Leave empty if no link is needed</div>
              </div>

              <div class="mb-3">
                <label for="icon" class="form-label">Icon</label>
                <div class="input-group">
                  <span class="input-group-text icon-preview"><i class="<?php echo htmlspecialchars($icon); ?>"></i></span>
                  <input type="text" class="form-control" id="icon" name="icon" value="<?php echo htmlspecialchars($icon); ?>">
                  <button class="btn btn-outline-secondary" type="button" id="showIconSelector">Browse</button>
                </div>
                <div class="form-text">Font Awesome icon class (e.g., fas fa-bolt)</div>

                <!-- Icon Selector (Initially Hidden) -->
                <div class="icon-selector mt-2 d-none" id="iconSelector">
                  <div class="mb-2">
                    <input type="text" class="form-control form-control-sm" id="iconSearch" placeholder="Search icons...">
                  </div>
                  <div class="icons-container">
                    <div class="icon-select-option" data-icon="fas fa-bolt"><i class="fas fa-bolt icon-preview"></i> fas fa-bolt</div>
                    <div class="icon-select-option" data-icon="fas fa-newspaper"><i class="fas fa-newspaper icon-preview"></i> fas fa-newspaper</div>
                    <div class="icon-select-option" data-icon="fas fa-exclamation-circle"><i class="fas fa-exclamation-circle icon-preview"></i> fas fa-exclamation-circle</div>
                    <div class="icon-select-option" data-icon="fas fa-globe"><i class="fas fa-globe icon-preview"></i> fas fa-globe</div>
                    <div class="icon-select-option" data-icon="fas fa-bell"><i class="fas fa-bell icon-preview"></i> fas fa-bell</div>
                    <div class="icon-select-option" data-icon="fas fa-bullhorn"><i class="fas fa-bullhorn icon-preview"></i> fas fa-bullhorn</div>
                    <div class="icon-select-option" data-icon="fas fa-chart-line"><i class="fas fa-chart-line icon-preview"></i> fas fa-chart-line</div>
                    <div class="icon-select-option" data-icon="fas fa-fire"><i class="fas fa-fire icon-preview"></i> fas fa-fire</div>
                    <div class="icon-select-option" data-icon="fas fa-heartbeat"><i class="fas fa-heartbeat icon-preview"></i> fas fa-heartbeat</div>
                    <div class="icon-select-option" data-icon="fas fa-shield-alt"><i class="fas fa-shield-alt icon-preview"></i> fas fa-shield-alt</div>
                    <div class="icon-select-option" data-icon="fas fa-graduation-cap"><i class="fas fa-graduation-cap icon-preview"></i> fas fa-graduation-cap</div>
                    <div class="icon-select-option" data-icon="fas fa-landmark"><i class="fas fa-landmark icon-preview"></i> fas fa-landmark</div>
                    <div class="icon-select-option" data-icon="fas fa-microphone"><i class="fas fa-microphone icon-preview"></i> fas fa-microphone</div>
                    <div class="icon-select-option" data-icon="fas fa-trophy"><i class="fas fa-trophy icon-preview"></i> fas fa-trophy</div>
                    <div class="icon-select-option" data-icon="fas fa-anchor"><i class="fas fa-anchor icon-preview"></i> fas fa-anchor</div>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="priority" class="form-label">Priority</label>
                  <input type="number" class="form-control" id="priority" name="priority" value="<?php echo intval($priority); ?>" min="0">
                  <div class="form-text">Higher numbers appear first</div>
                </div>
                <div class="col-md-6">
                  <div class="form-check form-switch mt-4">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" <?php echo $is_active ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_active">Active</label>
                  </div>
                </div>
              </div>

              <div class="d-grid gap-2">
                <?php if (empty($id)): ?>
                  <button type="submit" name="add_news" class="btn btn-success">Add Breaking News</button>
                <?php else: ?>
                  <button type="submit" name="update_news" class="btn btn-primary">Update Breaking News</button>
                  <a href="breaking-news.php" class="btn btn-outline-secondary">Cancel Edit</a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Breaking News List -->
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">Breaking News Items</h5>
          </div>
          <div class="card-body p-0">
            <?php if (count($news_items) > 0): ?>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th scope="col" width="50">Icon</th>
                      <th scope="col">Title</th>
                      <th scope="col" width="80">Priority</th>
                      <th scope="col" width="100">Status</th>
                      <th scope="col" width="150">Date Added</th>
                      <th scope="col" width="180" class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($news_items as $news): ?>
                      <tr>
                        <td>
                          <i class="<?php echo htmlspecialchars($news['icon']); ?> fa-lg"></i>
                        </td>
                        <td>
                          <?php echo htmlspecialchars($news['title']); ?>
                          <?php if (!empty($news['link'])): ?>
                            <a href="<?php echo htmlspecialchars($news['link']); ?>" target="_blank" class="ms-2">
                              <i class="bi bi-link-45deg"></i>
                            </a>
                          <?php endif; ?>
                        </td>
                        <td><?php echo intval($news['priority']); ?></td>
                        <td>
                          <span class="badge status-badge <?php echo $news['is_active'] ? 'active' : 'inactive'; ?>">
                            <?php echo $news['is_active'] ? 'Active' : 'Inactive'; ?>
                          </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($news['created_at'])); ?></td>
                        <td class="text-end">
                          <a href="breaking-news.php?edit=<?php echo $news['id']; ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil"></i> Edit
                          </a>
                          <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $news['id']; ?>">
                            <i class="bi bi-trash"></i> Delete
                          </button>

                          <!-- Delete Confirmation Modal -->
                          <div class="modal fade" id="deleteModal<?php echo $news['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $news['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="deleteModalLabel<?php echo $news['id']; ?>">Confirm Delete</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                  Are you sure you want to delete this breaking news item?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <form method="post" action="" class="d-inline">
                                    <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
                                    <button type="submit" name="delete_news" class="btn btn-danger">Delete</button>
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
            <?php else: ?>
              <div class="p-4 text-center">
                <div class="mb-3">
                  <i class="bi bi-exclamation-circle text-muted" style="font-size: 3rem;"></i>
                </div>
                <h5>No Breaking News Items</h5>
                <p class="text-muted">Add your first breaking news item using the form.</p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Auto dismiss alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);

      // Icon preview update
      $('#icon').on('input', function() {
        const iconClass = $(this).val();
        $('.icon-preview i').attr('class', iconClass);
      });

      // Show/hide icon selector
      $('#showIconSelector').on('click', function() {
        $('#iconSelector').toggleClass('d-none');

        // Mark current selection
        const currentIcon = $('#icon').val();
        $('.icon-select-option').removeClass('selected');
        $(`.icon-select-option[data-icon="${currentIcon}"]`).addClass('selected');
      });

      // Icon selection
      $('.icon-select-option').on('click', function() {
        const iconClass = $(this).data('icon');
        $('#icon').val(iconClass);
        $('.icon-preview i').attr('class', iconClass);

        // Highlight selected
        $('.icon-select-option').removeClass('selected');
        $(this).addClass('selected');

        // Hide selector after selection
        $('#iconSelector').addClass('d-none');
      });

      // Icon search functionality
      $('#iconSearch').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.icon-select-option').each(function() {
          const iconText = $(this).text().toLowerCase();
          if (iconText.includes(searchTerm)) {
            $(this).show();
          } else {
            $(this).hide();
          }
        });
      });
    });
  </script>
</body>

</html>
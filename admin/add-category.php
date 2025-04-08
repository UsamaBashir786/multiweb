<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Initialize variables
$categoryName = "";
$categoryId = "";
$errorMsg = "";
$successMsg = "";

// Handle Add Category
if (isset($_POST['add_category'])) {
  $categoryName = trim($_POST['category_name']);

  // Validate input
  if (empty($categoryName)) {
    $errorMsg = "Category name cannot be empty";
  } else {
    // Check if category already exists
    $checkSql = "SELECT * FROM categories WHERE name = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $categoryName);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
      $errorMsg = "Category with this name already exists";
    } else {
      // Insert new category
      $insertSql = "INSERT INTO categories (name) VALUES (?)";
      $insertStmt = $conn->prepare($insertSql);
      $insertStmt->bind_param("s", $categoryName);

      if ($insertStmt->execute()) {
        $successMsg = "Category added successfully";
        $categoryName = ""; // Clear the form
      } else {
        $errorMsg = "Error adding category: " . $conn->error;
      }
    }
  }
}

// Handle Update Category
if (isset($_POST['update_category'])) {
  $categoryId = $_POST['category_id'];
  $categoryName = trim($_POST['category_name']);

  // Validate input
  if (empty($categoryName)) {
    $errorMsg = "Category name cannot be empty";
  } else {
    // Check if category already exists with this name (excluding current one)
    $checkSql = "SELECT * FROM categories WHERE name = ? AND id != ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("si", $categoryName, $categoryId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
      $errorMsg = "Category with this name already exists";
    } else {
      // Update category
      $updateSql = "UPDATE categories SET name = ? WHERE id = ?";
      $updateStmt = $conn->prepare($updateSql);
      $updateStmt->bind_param("si", $categoryName, $categoryId);

      if ($updateStmt->execute()) {
        $successMsg = "Category updated successfully";
        $categoryName = ""; // Clear the form
        $categoryId = ""; // Reset edit mode
      } else {
        $errorMsg = "Error updating category: " . $conn->error;
      }
    }
  }
}

// Handle Delete Category
if (isset($_POST['delete_category'])) {
  $categoryId = $_POST['category_id'];

  // Check if category is used in any stories/articles before deleting
  // This is a placeholder - you would add actual checks based on your database structure
  $canDelete = true;

  if ($canDelete) {
    $deleteSql = "DELETE FROM categories WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $categoryId);

    if ($deleteStmt->execute()) {
      $successMsg = "Category deleted successfully";
    } else {
      $errorMsg = "Error deleting category: " . $conn->error;
    }
  } else {
    $errorMsg = "Cannot delete category as it is currently in use";
  }
}

// Fetch all categories
$categories = [];
$fetchSql = "SELECT * FROM categories ORDER BY name ASC";
$result = $conn->query($fetchSql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Category Management - Admin Panel</title>
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

    /* Table Styles */
    .category-table {
      margin-bottom: 0;
    }

    .category-table th {
      font-weight: 600;
      background-color: #f8f9fa;
    }

    /* Edit form (initially hidden) */
    .edit-form {
      display: none;
      width: 100%;
    }

    /* Show edit form when in editing mode */
    .category-row.editing .category-display {
      display: none;
    }

    .category-row.editing .edit-form {
      display: flex;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col">
        <h2 class="mb-0">Manage Categories</h2>
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
      <!-- Add Category Form -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Add New Category</h5>
          </div>
          <div class="card-body">
            <form method="post" action="">
              <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($categoryName); ?>" required>
              </div>

              <div class="d-grid">
                <button type="submit" name="add_category" class="btn btn-success">Add Category</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Categories List -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Categories</h5>
          </div>
          <div class="card-body">
            <?php if (count($categories) > 0): ?>
              <div class="table-responsive">
                <table class="table table-hover category-table">
                  <thead>
                    <tr>
                      <th width="50">#</th>
                      <th>Category Name</th>
                      <th width="180" class="text-end">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($categories as $index => $category): ?>
                      <tr class="category-row" id="category-row-<?php echo $category['id']; ?>">
                        <td><?php echo $index + 1; ?></td>
                        <td>
                          <!-- Display mode -->
                          <div class="category-display">
                            <?php echo htmlspecialchars($category['name']); ?>
                          </div>

                          <!-- Edit mode (initially hidden) -->
                          <div class="edit-form">
                            <form method="post" action="" class="w-100">
                              <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                              <div class="input-group">
                                <input type="text" class="form-control" name="category_name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                                <button type="submit" name="update_category" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
                              </div>
                            </form>
                          </div>
                        </td>
                        <td class="text-end">
                          <div class="category-display">
                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="<?php echo $category['id']; ?>">
                              <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $category['id']; ?>">
                              <i class="bi bi-trash"></i> Delete
                            </button>
                          </div>

                          <!-- Delete Confirmation Modal -->
                          <div class="modal fade" id="deleteModal<?php echo $category['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $category['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="deleteModalLabel<?php echo $category['id']; ?>">Confirm Delete</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                  Are you sure you want to delete the category <strong><?php echo htmlspecialchars($category['name']); ?></strong>?
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <form method="post" action="" class="d-inline">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" name="delete_category" class="btn btn-danger">Delete</button>
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
              <p class="text-center mb-0">No categories found. Add your first category using the form.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    // Auto dismiss alerts after 5 seconds
    window.addEventListener('load', function() {
      setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 5000);
    });

    // Inline editing functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Edit buttons
      const editButtons = document.querySelectorAll('.edit-btn');
      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          const categoryId = this.getAttribute('data-id');
          const categoryRow = document.getElementById('category-row-' + categoryId);

          // Close any other open edit forms
          document.querySelectorAll('.category-row.editing').forEach(row => {
            if (row.id !== 'category-row-' + categoryId) {
              row.classList.remove('editing');
            }
          });

          // Toggle edit mode for this row
          categoryRow.classList.add('editing');

          // Focus on the input field
          const inputField = categoryRow.querySelector('input[name="category_name"]');
          inputField.focus();
          inputField.select();
        });
      });

      // Cancel buttons
      const cancelButtons = document.querySelectorAll('.cancel-edit');
      cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
          const categoryRow = this.closest('.category-row');
          categoryRow.classList.remove('editing');
        });
      });
    });
  </script>
</body>

</html>
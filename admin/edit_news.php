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
$author = "";
$author_role = "";
$content = "";
$excerpt = "";
$image = "";
$category_id = "";
$tags = "";
$featured = 0;
$errorMsg = "";
$successMsg = "";

// Fetch all categories for dropdown
$categories = [];
$fetchCategoriesSql = "SELECT * FROM categories ORDER BY name ASC";
$categoriesResult = $conn->query($fetchCategoriesSql);

if ($categoriesResult && $categoriesResult->num_rows > 0) {
  while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
  }
}

// Function to create slug from title
function createSlug($string)
{
  $slug = strtolower(trim($string));
  $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
  $slug = preg_replace('/-+/', '-', $slug);
  return $slug;
}

// Check if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id = $_GET['id'];

  // Fetch news details
  $fetchSql = "SELECT * FROM news WHERE id = ?";
  $fetchStmt = $conn->prepare($fetchSql);
  $fetchStmt->bind_param("i", $id);
  $fetchStmt->execute();
  $result = $fetchStmt->get_result();

  if ($result->num_rows > 0) {
    $news = $result->fetch_assoc();

    // Populate variables
    $title = $news['title'];
    $author = $news['author'];
    $author_role = $news['author_role'];
    $content = $news['content'];
    $excerpt = $news['excerpt'];
    $image = $news['image'];
    $category_id = $news['category_id'];
    $tags = $news['tags'];
    $featured = $news['featured'];
  } else {
    $errorMsg = "News not found";
  }
} else {
  $errorMsg = "No news ID provided";
}

// Handle Update News
if (isset($_POST['update_news'])) {
  // Get form data
  $id = $_POST['news_id'];
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $author_role = trim($_POST['author_role']);
  $content = trim($_POST['content']);
  $excerpt = trim($_POST['excerpt']);
  $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
  $tags = trim($_POST['tags']);
  $featured = isset($_POST['featured']) ? 1 : 0;
  $current_image = $_POST['current_image'];

  // Create new slug only if title has changed
  $new_slug = createSlug($title);
  $original_title = $_POST['original_title'];
  $slug_changed = false;

  if ($title != $original_title) {
    $slug_changed = true;
  }

  // Validate input
  if (empty($title)) {
    $errorMsg = "Title cannot be empty";
  } elseif (empty($author)) {
    $errorMsg = "Author name cannot be empty";
  } elseif (empty($content)) {
    $errorMsg = "News content cannot be empty";
  } elseif (empty($excerpt)) {
    $errorMsg = "News excerpt cannot be empty";
  } else {
    // Check if new slug already exists (only if title changed)
    if ($slug_changed) {
      $checkSlugSql = "SELECT id FROM news WHERE slug = ? AND id != ?";
      $checkSlugStmt = $conn->prepare($checkSlugSql);
      $checkSlugStmt->bind_param("si", $new_slug, $id);
      $checkSlugStmt->execute();
      $slugResult = $checkSlugStmt->get_result();

      if ($slugResult->num_rows > 0) {
        // Append a random string to make slug unique
        $new_slug = $new_slug . '-' . substr(md5(rand()), 0, 5);
      }
    } else {
      // Keep existing slug
      $new_slug = $_POST['current_slug'];
    }

    // Handle file upload
    $image = $current_image; // Keep current image by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];
      $filename = $_FILES['image']['name'];
      $filetype = pathinfo($filename, PATHINFO_EXTENSION);

      // Verify file extension
      if (in_array(strtolower($filetype), $allowed)) {
        // File path
        $upload_dir = '../uploads/news/';

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }

        // Create a unique filename
        $new_filename = uniqid() . '.' . $filetype;
        $upload_path = $upload_dir . $new_filename;

        // Upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
          // Delete old image if it exists and is not the default
          if ($current_image != 'https://via.placeholder.com/800x500' && strpos($current_image, 'uploads/') !== false) {
            $old_image_path = '../' . $current_image;
            if (file_exists($old_image_path)) {
              unlink($old_image_path);
            }
          }

          $image = 'uploads/news/' . $new_filename;
        } else {
          $errorMsg = "Error uploading file";
        }
      } else {
        $errorMsg = "Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.";
      }
    }

    if (empty($errorMsg)) {
      // Update news
      $updateSql = "UPDATE news SET title = ?, slug = ?, author = ?, author_role = ?, 
                    content = ?, excerpt = ?, image = ?, category_id = ?, tags = ?, featured = ? WHERE id = ?";
      $updateStmt = $conn->prepare($updateSql);
      $updateStmt->bind_param(
        "sssssssisii",
        $title,
        $new_slug,
        $author,
        $author_role,
        $content,
        $excerpt,
        $image,
        $category_id,
        $tags,
        $featured,
        $id
      );

      if ($updateStmt->execute()) {
        $successMsg = "News updated successfully";

        // Refresh news data
        $fetchSql = "SELECT * FROM news WHERE id = ?";
        $fetchStmt = $conn->prepare($fetchSql);
        $fetchStmt->bind_param("i", $id);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();

        if ($result->num_rows > 0) {
          $news = $result->fetch_assoc();
          $image = $news['image'];
        }
      } else {
        $errorMsg = "Error updating news: " . $conn->error;
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit News - Admin Panel</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

    .preview-image {
      max-width: 100%;
      max-height: 200px;
      object-fit: contain;
      margin-top: 10px;
    }

    .note-editor {
      margin-bottom: 20px;
    }

    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--multiple {
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
      background-color: #9B5DE5;
      border: none;
      color: white;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
      color: white;
      margin-right: 5px;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">Edit News</h2>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="manage-news.php" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-left me-1"></i> Back to News
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

    <?php if (empty($errorMsg) || !empty($id)): ?>
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="news_id" value="<?php echo $id; ?>">
            <input type="hidden" name="current_image" value="<?php echo $image; ?>">
            <input type="hidden" name="original_title" value="<?php echo htmlspecialchars($title); ?>">
            <input type="hidden" name="current_slug" value="<?php echo htmlspecialchars($news['slug']); ?>">

            <div class="row">
              <!-- Left Column - Basic Info -->
              <div class="col-md-8">
                <div class="mb-4">
                  <label for="title" class="form-label required-field">News Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
                </div>

                <div class="mb-4">
                  <label for="content" class="form-label required-field">News Content</label>
                  <textarea class="form-control" id="content" name="content" rows="12" required><?php echo htmlspecialchars($content); ?></textarea>
                </div>

                <div class="mb-4">
                  <label for="excerpt" class="form-label required-field">News Excerpt</label>
                  <p class="text-muted small">A short summary that appears in news listings (max 200 characters)</p>
                  <textarea class="form-control" id="excerpt" name="excerpt" rows="3" maxlength="200" required><?php echo htmlspecialchars($excerpt); ?></textarea>
                  <div class="d-flex justify-content-end">
                    <small class="text-muted mt-1"><span id="charCount">0</span>/200 characters</small>
                  </div>
                </div>
              </div>

              <!-- Right Column - Meta Info -->
              <div class="col-md-4">
                <div class="card mb-4">
                  <div class="card-header bg-white">
                    <h5 class="card-title mb-0">News Details</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="author" class="form-label required-field">Author Name</label>
                      <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
                    </div>

                    <div class="mb-3">
                      <label for="author_role" class="form-label required-field">Author Role</label>
                      <input type="text" class="form-control" id="author_role" name="author_role" value="<?php echo htmlspecialchars($author_role); ?>" placeholder="e.g. News Reporter, Editor" required>
                    </div>

                    <div class="mb-3">
                      <label for="category_id" class="form-label">Category</label>
                      <select class="form-select" id="category_id" name="category_id">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                          <option value="<?php echo $category['id']; ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label for="tags" class="form-label">Tags</label>
                      <input type="text" class="form-control" id="tags" name="tags" value="<?php echo htmlspecialchars($tags); ?>" placeholder="Enter tags, separated by commas">
                      <div class="form-text">e.g. politics, sports, technology</div>
                    </div>

                    <div class="mb-3">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="featured" name="featured" <?php echo $featured ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featured">Featured News</label>
                      </div>
                      <div class="text-muted small">Featured news appear in highlighted sections</div>
                    </div>
                  </div>
                </div>

                <div class="card mb-4">
                  <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Featured Image</h5>
                  </div>
                  <div class="card-body">
                    <div class="mb-3">
                      <label for="image" class="form-label">Change Image</label>
                      <input type="file" class="form-control" id="image" name="image" accept="image/*">
                      <div class="text-muted small mt-1">Leave empty to keep current image</div>
                    </div>

                    <div id="imagePreview" class="text-center">
                      <p class="text-muted small mb-2">Current Image:</p>
                      <img src="<?php echo htmlspecialchars('../' . $image); ?>" class="preview-image" alt="Current Image">
                    </div>
                  </div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" name="update_news" class="btn btn-primary btn-lg">Update News</button>
                  <a href="manage-news.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- jQuery, Bootstrap, and Summernote JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
      // Initialize Summernote editor
      $('#content').summernote({
        height: 400,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
          onImageUpload: function(files) {
            // You can implement custom image upload here
            alert('For security reasons, please use the image upload field to upload images');
          }
        }
      });

      // Character counter for excerpt
      $('#excerpt').on('input', function() {
        var characters = $(this).val().length;
        $('#charCount').text(characters);
      });

      // Trigger char count on page load
      $('#excerpt').trigger('input');

      // Image preview
      $('#image').change(function() {
        if (this.files && this.files[0]) {
          var reader = new FileReader();

          reader.onload = function(e) {
            $('#imagePreview img').attr('src', e.target.result);
          }

          reader.readAsDataURL(this.files[0]);
        }
      });

      // Auto dismiss alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);
    });
  </script>
</body>

</html>
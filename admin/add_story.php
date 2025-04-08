<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Initialize variables
$title = "";
$author = "";
$author_role = "";
$content = "";
$excerpt = "";
$featured = 0;
$errorMsg = "";
$successMsg = "";

// Handle Add Story
if (isset($_POST['add_story'])) {
  // Get form data
  $title = trim($_POST['title']);
  $author = trim($_POST['author']);
  $author_role = trim($_POST['author_role']);
  $content = trim($_POST['content']);
  $excerpt = trim($_POST['excerpt']);
  $featured = isset($_POST['featured']) ? 1 : 0;

  // Validate input
  if (empty($title)) {
    $errorMsg = "Title cannot be empty";
  } elseif (empty($author)) {
    $errorMsg = "Author name cannot be empty";
  } elseif (empty($content)) {
    $errorMsg = "Story content cannot be empty";
  } elseif (empty($excerpt)) {
    $errorMsg = "Story excerpt cannot be empty";
  } else {
    // Handle file upload
    $image = "https://via.placeholder.com/800x500"; // Default image

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];
      $filename = $_FILES['image']['name'];
      $filetype = pathinfo($filename, PATHINFO_EXTENSION);

      // Verify file extension
      if (in_array(strtolower($filetype), $allowed)) {
        // File path
        $upload_dir = '../uploads/stories/';

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }

        // Create a unique filename
        $new_filename = uniqid() . '.' . $filetype;
        $upload_path = $upload_dir . $new_filename;

        // Upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
          $image = 'uploads/stories/' . $new_filename;
        } else {
          $errorMsg = "Error uploading file";
        }
      } else {
        $errorMsg = "Invalid file type. Only JPG, JPEG, PNG and GIF files are allowed.";
      }
    }

    if (empty($errorMsg)) {
      // Get current date
      $date = date('Y-m-d H:i:s');

      // Insert story
      $insertSql = "INSERT INTO stories (title, author, author_role, content, excerpt, image, featured, created_at, view_count) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)";
      $insertStmt = $conn->prepare($insertSql);
      $insertStmt->bind_param("ssssssis", $title, $author, $author_role, $content, $excerpt, $image, $featured, $date);

      if ($insertStmt->execute()) {
        $successMsg = "Story added successfully";
        // Reset form
        $title = "";
        $author = "";
        $author_role = "";
        $content = "";
        $excerpt = "";
        $featured = 0;
      } else {
        $errorMsg = "Error adding story: " . $conn->error;
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
  <title>Add Story - Admin Panel</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Summernote CSS -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
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
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col">
        <h2 class="mb-0">Add New Story</h2>
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
      <div class="card-body p-4">
        <form method="post" action="" enctype="multipart/form-data">
          <div class="row">
            <!-- Left Column - Basic Info -->
            <div class="col-md-8">
              <div class="mb-4">
                <label for="title" class="form-label required-field">Story Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
              </div>

              <div class="mb-4">
                <label for="content" class="form-label required-field">Story Content</label>
                <textarea class="form-control" id="content" name="content" rows="12" required><?php echo htmlspecialchars($content); ?></textarea>
              </div>

              <div class="mb-4">
                <label for="excerpt" class="form-label required-field">Story Excerpt</label>
                <p class="text-muted small">A short summary that appears in story listings (max 200 characters)</p>
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
                  <h5 class="card-title mb-0">Story Details</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="author" class="form-label required-field">Author Name</label>
                    <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
                  </div>

                  <div class="mb-3">
                    <label for="author_role" class="form-label required-field">Author Role</label>
                    <input type="text" class="form-control" id="author_role" name="author_role" value="<?php echo htmlspecialchars($author_role); ?>" placeholder="e.g. Travel Writer, Photographer" required>
                  </div>

                  <div class="mb-3">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="featured" name="featured" <?php echo $featured ? 'checked' : ''; ?>>
                      <label class="form-check-label" for="featured">Featured Story</label>
                    </div>
                    <div class="text-muted small">Featured stories appear in highlighted sections</div>
                  </div>
                </div>
              </div>

              <div class="card mb-4">
                <div class="card-header bg-white">
                  <h5 class="card-title mb-0">Featured Image</h5>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="image" class="form-label">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="text-muted small mt-1">Recommended size: 800x500 pixels</div>
                  </div>

                  <div id="imagePreview" class="text-center">
                    <img src="https://via.placeholder.com/800x500" class="preview-image" alt="Preview">
                  </div>
                </div>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" name="add_story" class="btn btn-primary btn-lg">Publish Story</button>
                <button type="button" class="btn btn-outline-secondary" id="saveDraft">Save as Draft</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- jQuery, Bootstrap, and Summernote JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

  <script>
    // Initialize Summernote editor
    $(document).ready(function() {
      $('#content').summernote({
        height: 300,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']],
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

      // Save draft functionality (placeholder)
      $('#saveDraft').click(function() {
        alert('Draft saving functionality will be implemented here');
      });

      // Auto dismiss alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);
    });
  </script>
</body>

</html>
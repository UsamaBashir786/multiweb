<?php
session_start();
include "config/db.php";

// Handle form data only if it's POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST["title"]);
    $writer = $conn->real_escape_string($_POST["writer"]);
    $content = $conn->real_escape_string($_POST["content"]);

    // Handle multiple image uploads
    $uploaded_images = [];
    $upload_dir = "uploads/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $file_name = basename($_FILES['images']['name'][$key]);
        $target_file = $upload_dir . uniqid() . "_" . $file_name;
        if (move_uploaded_file($tmp_name, $target_file)) {
            $uploaded_images[] = $target_file;
        }
    }

    $images_json = json_encode($uploaded_images);

    // Insert into database
    $sql = "INSERT INTO stories (title, writer, content, images)
            VALUES ('$title', '$writer', '$content', '$images_json')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "🎉 Your story has been submitted successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Story</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
            min-height: 100vh;
            margin: 0;
        }

        .story-form {
            max-width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-title {
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
        }

        .btn-submit {
            width: 17%;
            margin-top: 20px;
        }

        .carousel-inner img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <?php include "include/slidebar.php"; ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Column - Left Side (col-md-2) -->
            <div class="col-md-2">

            </div>

            <!-- Main Content Column - Right Side (col-md-10) -->
            <div class="col-md-10">
                <div class="row">
                    <div class="col-12">
                        <div class="story-form">
                            <h4 class="form-title">Add New Story</h4>
                            <form action="#" method="POST" enctype="multipart/form-data">
                                <?php if (isset($_SESSION['success'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['error'])): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <!-- Story Title and Writer Name -->
                                <div class="row mb-3">
                                    <div class="col-md-12 mb-3">
                                        <label for="title" class="form-label">Story Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter story title" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="writer" class="form-label">Writer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="writer" name="writer" placeholder="Enter writer's name" required>
                                    </div>
                                </div>

                                <!-- Multiple Image Upload -->
                                <div class="mb-3">
                                    <label for="images" class="form-label">Upload Images (Multiple)</label>
                                    <input class="form-control" type="file" id="images" name="images[]" multiple>
                                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple files.</small>
                                </div>

                                <!-- Story Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Full Story <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" name="content" rows="6" placeholder="Write your full story here..." required></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary btn-submit">Submit Story</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Image Slider -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div id="storyCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                // Loop through uploaded images (if any)
                                if (!empty($uploaded_images)) {
                                    foreach ($uploaded_images as $index => $image) {
                                        $active_class = ($index === 0) ? 'active' : ''; // Mark the first image as active
                                        echo "<div class='carousel-item $active_class'>
                                                  <img src='$image' class='d-block w-100' alt='Story Image'>
                                              </div>";
                                    }
                                }
                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#storyCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#storyCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
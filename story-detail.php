<?php
session_start();
include 'config/db.php';

// Check if story ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: stories.php");
  exit;
}

$story_id = intval($_GET['id']);

// Fetch story from database
$sql = "SELECT s.*, DATE_FORMAT(s.created_at, '%M %d, %Y') as formatted_date 
        FROM stories s 
        WHERE s.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $story_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if story exists
if ($result->num_rows == 0) {
  header("Location: stories.php");
  exit;
}

$story = $result->fetch_assoc();

// Update view count
$update_views = "UPDATE stories SET view_count = view_count + 1 WHERE id = ?";
$stmt = $conn->prepare($update_views);
$stmt->bind_param("i", $story_id);
$stmt->execute();

// Format view count for display
$view_count = $story['view_count'];
if ($view_count >= 1000) {
  $formatted_views = number_format($view_count / 1000, 1) . 'K';
} else {
  $formatted_views = $view_count;
}

// Get related stories
$related_sql = "SELECT id, title, author, image, view_count, DATE_FORMAT(created_at, '%M %d, %Y') as formatted_date 
                FROM stories 
                WHERE id != ? 
                ORDER BY RAND() 
                LIMIT 3";
$stmt = $conn->prepare($related_sql);
$stmt->bind_param("i", $story_id);
$stmt->execute();
$related_result = $stmt->get_result();
$related_stories = [];

while ($row = $related_result->fetch_assoc()) {
  // Format view count for display
  if ($row['view_count'] >= 1000) {
    $row['views'] = number_format($row['view_count'] / 1000, 1) . 'K';
  } else {
    $row['views'] = $row['view_count'];
  }

  // Set default image if none exists
  if (empty($row['image'])) {
    $row['image'] = 'https://via.placeholder.com/800x500';
  }

  $related_stories[] = $row;
}

// Get story categories (for sidebar filter)
$story_categories = ['All Stories', 'Travel', 'Culture', 'Adventure', 'Science', 'Personal', 'Historical', 'Nature'];

// Assign a random category for the story for demo purposes
// In production, you would have a proper category column
if (!isset($story['category'])) {
  $category_index = array_rand(array_slice($story_categories, 1)); // Skip 'All Stories'
  $story['category'] = $story_categories[$category_index + 1];
}

// Process comment submission
$comment_message = '';
$comment_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $comment = trim($_POST['comment']);

  // Fix for parent_id - properly handle empty values
  $parent_id = null;
  if (isset($_POST['parent_id']) && !empty($_POST['parent_id'])) {
    $parent_id = intval($_POST['parent_id']);

    // Verify that the parent comment exists
    $check_parent = "SELECT id FROM comments WHERE id = ? AND story_id = ?";
    $stmt = $conn->prepare($check_parent);
    $stmt->bind_param("ii", $parent_id, $story_id);
    $stmt->execute();
    $parent_result = $stmt->get_result();

    if ($parent_result->num_rows == 0) {
      // Parent comment doesn't exist, clear parent_id
      $parent_id = null;
    }
  }

  // Simple validation
  if (empty($name) || empty($email) || empty($comment)) {
    $comment_error = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $comment_error = "Please enter a valid email address.";
  } else {
    // Prepare SQL with or without parent_id
    if ($parent_id === null) {
      $comment_sql = "INSERT INTO comments (story_id, name, email, comment, status) 
                     VALUES (?, ?, ?, ?, 'pending')";
      $stmt = $conn->prepare($comment_sql);
      $stmt->bind_param("isss", $story_id, $name, $email, $comment);
    } else {
      $comment_sql = "INSERT INTO comments (story_id, parent_id, name, email, comment, status) 
                     VALUES (?, ?, ?, ?, ?, 'pending')";
      $stmt = $conn->prepare($comment_sql);
      $stmt->bind_param("iisss", $story_id, $parent_id, $name, $email, $comment);
    }

    if ($stmt->execute()) {
      $comment_message = "Your comment has been submitted and is awaiting moderation.";
      // Clear form data after successful submission
      $name = $email = $comment = '';
    } else {
      $comment_error = "Error submitting comment: " . $conn->error;
    }
  }
}

// Get comments for this story
$comments_sql = "SELECT c.*, DATE_FORMAT(c.created_at, '%M %d, %Y at %h:%i %p') as formatted_date 
                FROM comments c 
                WHERE c.story_id = ? AND c.parent_id IS NULL AND c.status = 'approved'
                ORDER BY c.created_at DESC";
$stmt = $conn->prepare($comments_sql);
$stmt->bind_param("i", $story_id);
$stmt->execute();
$comments_result = $stmt->get_result();
$comments = [];

// Function to get comment replies
function getCommentReplies($conn, $parent_id, $story_id)
{
  $replies_sql = "SELECT c.*, DATE_FORMAT(c.created_at, '%M %d, %Y at %h:%i %p') as formatted_date 
                 FROM comments c 
                 WHERE c.parent_id = ? AND c.story_id = ? AND c.status = 'approved'
                 ORDER BY c.created_at ASC";
  $stmt = $conn->prepare($replies_sql);
  $stmt->bind_param("ii", $parent_id, $story_id);
  $stmt->execute();
  $result = $stmt->get_result();

  $replies = [];
  while ($reply = $result->fetch_assoc()) {
    $replies[] = $reply;
  }

  return $replies;
}

// Count total approved comments including replies
$count_sql = "SELECT COUNT(*) as total FROM comments WHERE story_id = ? AND status = 'approved'";
$stmt = $conn->prepare($count_sql);
$stmt->bind_param("i", $story_id);
$stmt->execute();
$count_result = $stmt->get_result();
$comment_count = $count_result->fetch_assoc()['total'];

// Build comments array with replies
while ($comment = $comments_result->fetch_assoc()) {
  $comment['replies'] = getCommentReplies($conn, $comment['id'], $story_id);
  $comments[] = $comment;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'include/css-links.php' ?>
  <style>
    .story-content img {
      max-width: 100%;
      height: auto;
      margin: 20px 0;
    }

    .story-content figure {
      margin: 20px 0;
    }

    .story-content figcaption {
      font-size: 0.9rem;
      color: #6c757d;
      text-align: center;
      margin-top: 5px;
    }

    .story-content blockquote {
      padding: 20px;
      margin: 20px 0;
      border-left: 5px solid #9B5DE5;
      background-color: #f8f9fa;
      font-style: italic;
    }

    .author-info {
      border-radius: 10px;
      overflow: hidden;
    }

    .share-buttons .btn {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .share-buttons .btn:hover {
      transform: translateY(-3px);
    }

    .story-meta {
      border-bottom: 1px solid #dee2e6;
      border-top: 1px solid #dee2e6;
    }

    .related-story-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .related-story-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

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

    .reply-form {
      display: none;
      margin-top: 15px;
    }

    .comment-actions {
      display: flex;
      gap: 10px;
    }
  </style>
</head>

<body>
  <!-- navbar -->
  <?php include 'include/navbar.php' ?>

  <!-- Story Header -->
  <header class="py-5 bg-dark text-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 mx-auto text-center">
          <span class="badge rounded-pill px-3 py-2 mb-3" style="background-color: #9B5DE5;">
            <?= htmlspecialchars($story['category'] ?? 'Story') ?>
          </span>
          <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($story['title']) ?></h1>
          <div class="d-flex justify-content-center align-items-center">
            <img src="https://via.placeholder.com/60" class="rounded-circle me-2" alt="Author" width="50" height="50">
            <div class="text-start">
              <p class="mb-0 fw-bold"><?= htmlspecialchars($story['author']) ?></p>
              <small><?= htmlspecialchars($story['author_role']) ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Story Content -->
  <main class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <!-- Story Meta Information -->
          <div class="d-flex justify-content-between py-3 story-meta mb-4">
            <div>
              <span class="text-muted me-3"><i class="far fa-calendar me-1"></i> <?= htmlspecialchars($story['formatted_date']) ?></span>
              <span class="text-muted"><i class="far fa-eye me-1"></i> <?= htmlspecialchars($formatted_views) ?> reads</span>
            </div>
            <div class="share-buttons">
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
              <a href="https://twitter.com/intent/tweet?url=<?= urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($story['title']) ?>" target="_blank" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i></a>
              <a href="https://api.whatsapp.com/send?text=<?= urlencode($story['title'] . ' - https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" target="_blank" class="btn btn-outline-success"><i class="fab fa-whatsapp"></i></a>
            </div>
          </div>

          <!-- Featured Image -->
          <div class="mb-4">
            <img src="<?= htmlspecialchars($story['image']) ?>" alt="<?= htmlspecialchars($story['title']) ?>" class="img-fluid rounded shadow">
          </div>

          <!-- Story Content -->
          <div class="story-content mb-5">
            <?= $story['content'] ?>
          </div>

          <!-- Tags -->
          <div class="mb-5">
            <h5>Tags</h5>
            <div class="d-flex flex-wrap">
              <a href="stories.php?category=<?= urlencode($story['category'] ?? 'Travel') ?>" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">
                <?= htmlspecialchars($story['category'] ?? 'Travel') ?>
              </a>
              <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Storytelling</a>
              <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3">Experience</a>
            </div>
          </div>

          <!-- Author Box -->
          <div class="author-info bg-light p-4 mb-5">
            <div class="row align-items-center">
              <div class="col-md-3 text-center mb-3 mb-md-0">
                <img src="https://via.placeholder.com/150" class="rounded-circle mb-2" width="120" height="120" alt="Author">
              </div>
              <div class="col-md-9">
                <h4><?= htmlspecialchars($story['author']) ?></h4>
                <p class="text-muted mb-2"><?= htmlspecialchars($story['author_role']) ?></p>
                <p class="mb-3">An experienced writer with a passion for sharing unique perspectives and stories from around the world.</p>
                <div class="d-flex">
                  <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle me-2">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle me-2">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle me-2">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                  <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle">
                    <i class="fas fa-globe"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Comments Section -->
          <div class="mb-5">
            <h3 class="mb-4" id="comments">Comments (<?= $comment_count ?>)</h3>

            <!-- Comment Form -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-body">
                <h5 class="mb-3">Leave a Comment</h5>

                <?php if (!empty($comment_message)): ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($comment_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>

                <?php if (!empty($comment_error)): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($comment_error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>

                <form method="post" action="#comments" id="main-comment-form">
                  <input type="hidden" name="parent_id" id="comment_parent_id" value="">
                  <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Share your thoughts..." required><?= isset($comment) ? htmlspecialchars($comment) : '' ?></textarea>
                  </div>
                  <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                      <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
                    </div>
                  </div>
                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="saveInfo">
                    <label class="form-check-label" for="saveInfo">
                      Save my name and email for the next time I comment
                    </label>
                  </div>
                  <button type="submit" name="submit_comment" class="btn" style="background-color: #9B5DE5; color: white;">Post Comment</button>
                </form>
              </div>
            </div>

            <!-- Comment List -->
            <div class="comment-list">
              <?php if (empty($comments)): ?>
                <div class="alert alert-info">
                  No comments yet. Be the first to comment!
                </div>
              <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                  <div class="card border-0 shadow-sm mb-3" id="comment-<?= $comment['id'] ?>">
                    <div class="card-body">
                      <div class="d-flex mb-3">
                        <img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($comment['email']))) ?>?s=50&d=mp" class="rounded-circle me-3" width="50" height="50" alt="<?= htmlspecialchars($comment['name']) ?>">
                        <div>
                          <h6 class="mb-1"><?= htmlspecialchars($comment['name']) ?></h6>
                          <small class="text-muted"><?= htmlspecialchars($comment['formatted_date']) ?></small>
                        </div>
                      </div>
                      <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                      <div class="comment-actions">
                        <button class="btn btn-sm btn-outline-secondary reply-button" data-comment-id="<?= $comment['id'] ?>">Reply</button>
                      </div>

                      <!-- Reply Form -->
                      <div class="reply-form" id="reply-form-<?= $comment['id'] ?>">
                        <form method="post" action="#comment-<?= $comment['id'] ?>" class="reply-comment-form">
                          <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                          <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Write your reply..." required></textarea>
                          </div>
                          <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                              <input type="text" name="name" class="form-control reply-name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                              <input type="email" name="email" class="form-control reply-email" placeholder="Your Email" required>
                            </div>
                          </div>
                          <div class="d-flex gap-2">
                            <button type="submit" name="submit_comment" class="btn btn-sm" style="background-color: #9B5DE5; color: white;">Post Reply</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary cancel-reply" data-comment-id="<?= $comment['id'] ?>">Cancel</button>
                          </div>
                        </form>
                      </div>

                      <!-- Replies -->
                      <?php if (!empty($comment['replies'])): ?>
                        <?php foreach ($comment['replies'] as $reply): ?>
                          <div class="ms-5 p-3 bg-light rounded mt-3" id="comment-<?= $reply['id'] ?>">
                            <div class="d-flex mb-3">
                              <img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($reply['email']))) ?>?s=40&d=mp" class="rounded-circle me-3" width="40" height="40" alt="<?= htmlspecialchars($reply['name']) ?>">
                              <div>
                                <h6 class="mb-1">
                                  <?= htmlspecialchars($reply['name']) ?>
                                  <?php if ($reply['name'] === $story['author']): ?>
                                    <span class="badge bg-secondary">Author</span>
                                  <?php endif; ?>
                                </h6>
                                <small class="text-muted"><?= htmlspecialchars($reply['formatted_date']) ?></small>
                              </div>
                            </div>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($reply['comment'])) ?></p>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Related Stories -->
  <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center mb-5">You May Also Like</h2>
      <div class="row">
        <?php foreach ($related_stories as $related): ?>
          <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100 related-story-card">
              <img src="<?= htmlspecialchars($related['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($related['title']) ?>" style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title">
                  <a href="story-detail.php?id=<?= $related['id'] ?>" class="text-decoration-none text-dark">
                    <?= htmlspecialchars($related['title']) ?>
                  </a>
                </h5>
                <div class="mt-3 d-flex justify-content-between align-items-center">
                  <small class="text-muted"><?= htmlspecialchars($related['author']) ?></small>
                  <small class="text-muted"><i class="far fa-eye me-1"></i> <?= htmlspecialchars($related['views']) ?> reads</small>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mb-3">Have Your Own Story to Share?</h2>
          <p class="lead mb-4">We welcome submissions from writers of all backgrounds. Share your unique perspective with our community.</p>
          <a href="submit-story.php" class="btn btn-lg px-4" style="background-color: #9B5DE5; color: white;">Submit Your Story</a>
        </div>
      </div>
    </div>
  </section>

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

      // Comment reply functionality
      const replyButtons = document.querySelectorAll('.reply-button');
      const cancelButtons = document.querySelectorAll('.cancel-reply');

      replyButtons.forEach(button => {
        button.addEventListener('click', function() {
          const commentId = this.getAttribute('data-comment-id');
          document.getElementById(`reply-form-${commentId}`).style.display = 'block';

          // Populate reply form with saved info
          const replyForm = document.getElementById(`reply-form-${commentId}`);
          if (localStorage.getItem('comment_name')) {
            replyForm.querySelector('.reply-name').value = localStorage.getItem('comment_name');
          }

          if (localStorage.getItem('comment_email')) {
            replyForm.querySelector('.reply-email').value = localStorage.getItem('comment_email');
          }
        });
      });

      cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
          const commentId = this.getAttribute('data-comment-id');
          document.getElementById(`reply-form-${commentId}`).style.display = 'none';
        });
      });

      // Save comment info in localStorage if checkbox is checked
      const saveInfoCheckbox = document.getElementById('saveInfo');
      const nameInput = document.querySelector('input[name="name"]');
      const emailInput = document.querySelector('input[name="email"]');

      // Load saved data if available
      if (localStorage.getItem('comment_name')) {
        nameInput.value = localStorage.getItem('comment_name');
      }

      if (localStorage.getItem('comment_email')) {
        emailInput.value = localStorage.getItem('comment_email');
        saveInfoCheckbox.checked = true;
      }

      // Save data when form is submitted
      document.getElementById('main-comment-form').addEventListener('submit', function() {
        if (saveInfoCheckbox.checked) {
          localStorage.setItem('comment_name', nameInput.value);
          localStorage.setItem('comment_email', emailInput.value);
        } else {
          localStorage.removeItem('comment_name');
          localStorage.removeItem('comment_email');
        }
      });

      // Also save from reply forms
      document.querySelectorAll('.reply-comment-form').forEach(form => {
        form.addEventListener('submit', function() {
          if (saveInfoCheckbox.checked) {
            const name = this.querySelector('input[name="name"]').value;
            const email = this.querySelector('input[name="email"]').value;
            localStorage.setItem('comment_name', name);
            localStorage.setItem('comment_email', email);
          }
        });
      });

      // Scroll to comment section if there's a comment hash in URL
      if (window.location.hash && window.location.hash.includes('comment-')) {
        const commentElement = document.querySelector(window.location.hash);
        if (commentElement) {
          commentElement.scrollIntoView({
            behavior: 'smooth'
          });

          // Highlight the comment temporarily
          commentElement.style.transition = 'background-color 0.5s ease';
          commentElement.style.backgroundColor = 'rgba(155, 93, 229, 0.1)';

          setTimeout(() => {
            commentElement.style.backgroundColor = '';
          }, 2000);
        }
      }
    });
  </script>
</body>

</html>
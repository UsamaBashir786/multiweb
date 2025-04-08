<?php
include 'config/db.php';

// Check if article slug is provided
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Initialize variables
$article = null;
$errorMsg = "";

// Fetch article details
if ($slug) {
  $fetchSql = "SELECT a.*, c.name as category_name FROM articles a 
               LEFT JOIN categories c ON a.category_id = c.id 
               WHERE a.slug = ?";
  $fetchStmt = $conn->prepare($fetchSql);
  $fetchStmt->bind_param("s", $slug);
} elseif ($id) {
  $fetchSql = "SELECT a.*, c.name as category_name FROM articles a 
               LEFT JOIN categories c ON a.category_id = c.id 
               WHERE a.id = ?";
  $fetchStmt = $conn->prepare($fetchSql);
  $fetchStmt->bind_param("i", $id);
} else {
  $errorMsg = "Article not found";
}

if (empty($errorMsg)) {
  $fetchStmt->execute();
  $result = $fetchStmt->get_result();

  if ($result->num_rows > 0) {
    $article = $result->fetch_assoc();
    
    // Update view count
    $updateViewsSql = "UPDATE articles SET view_count = view_count + 1 WHERE id = ?";
    $updateViewsStmt = $conn->prepare($updateViewsSql);
    $updateViewsStmt->bind_param("i", $article['id']);
    $updateViewsStmt->execute();
    
    // Parse tags
    $article_tags = [];
    if (!empty($article['tags'])) {
      $article_tags = explode(',', $article['tags']);
      // Trim whitespace
      $article_tags = array_map('trim', $article_tags);
    }
    
  } else {
    $errorMsg = "Article not found";
  }
}

// If article is found, fetch related articles
$related_articles = [];
if ($article) {
  // Fetch related articles by category
  $relatedSql = "SELECT id, title, slug, image, created_at FROM articles 
                WHERE category_id = ? AND id != ? 
                ORDER BY created_at DESC LIMIT 4";
  $relatedStmt = $conn->prepare($relatedSql);
  $relatedStmt->bind_param("ii", $article['category_id'], $article['id']);
  $relatedStmt->execute();
  $relatedResult = $relatedStmt->get_result();
  
  while ($row = $relatedResult->fetch_assoc()) {
    $related_articles[] = $row;
  }
  
  // If not enough related articles by category, fetch by tags
  if (count($related_articles) < 4 && !empty($article_tags)) {
    // Create placeholders for tags (?,?,?...)
    $tag_placeholders = implode(',', array_fill(0, count($article_tags), '?'));
    
    // Build the query with LIKE conditions for each tag
    $tagRelatedSql = "SELECT id, title, slug, image, created_at FROM articles 
                     WHERE id != ? AND id NOT IN (" . implode(',', array_map(function($a) { return $a['id']; }, $related_articles)) . ")
                     AND (";
    
    $conditions = [];
    foreach ($article_tags as $tag) {
      $conditions[] = "tags LIKE ?";
    }
    
    $tagRelatedSql .= implode(' OR ', $conditions) . ") LIMIT " . (4 - count($related_articles));
    
    $tagRelatedStmt = $conn->prepare($tagRelatedSql);
    
    // Create parameter types and values
    $paramTypes = "i" . str_repeat("s", count($article_tags));
    $paramValues = [$article['id']];
    
    foreach ($article_tags as $tag) {
      $paramValues[] = '%' . $tag . '%';
    }
    
    $tagRelatedStmt->bind_param($paramTypes, ...$paramValues);
    $tagRelatedStmt->execute();
    $tagRelatedResult = $tagRelatedStmt->get_result();
    
    while ($row = $tagRelatedResult->fetch_assoc()) {
      $related_articles[] = $row;
    }
  }
  
  // If still not enough, get most recent articles
  if (count($related_articles) < 4) {
    $excludeIds = [$article['id']];
    foreach ($related_articles as $ra) {
      $excludeIds[] = $ra['id'];
    }
    
    $excludeStr = implode(',', $excludeIds);
    
    $recentSql = "SELECT id, title, slug, image, created_at FROM articles 
                 WHERE id NOT IN ($excludeStr) 
                 ORDER BY created_at DESC LIMIT " . (4 - count($related_articles));
    $recentResult = $conn->query($recentSql);
    
    while ($row = $recentResult->fetch_assoc()) {
      $related_articles[] = $row;
    }
  }
}

// Fetch popular tags for sidebar
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

// Initialize comments variables
$comments = [];
$comments_count = 0;

// If article is found, fetch comments
if ($article) {
  // Fetch comments for this article (only approved ones)
  $commentsSql = "SELECT c.*, 
                 (SELECT COUNT(*) FROM comments WHERE parent_id = c.id) as reply_count 
                 FROM comments c 
                 WHERE c.story_id = ? AND c.status = 'approved' AND c.parent_id IS NULL 
                 ORDER BY c.created_at DESC";
  $commentsStmt = $conn->prepare($commentsSql);
  $commentsStmt->bind_param("i", $article['id']);
  $commentsStmt->execute();
  $commentsResult = $commentsStmt->get_result();
  
  while ($row = $commentsResult->fetch_assoc()) {
    // Fetch replies for this comment
    $repliesSql = "SELECT * FROM comments 
                  WHERE parent_id = ? AND status = 'approved' 
                  ORDER BY created_at ASC";
    $repliesStmt = $conn->prepare($repliesSql);
    $repliesStmt->bind_param("i", $row['id']);
    $repliesStmt->execute();
    $repliesResult = $repliesStmt->get_result();
    
    $row['replies'] = [];
    while ($reply = $repliesResult->fetch_assoc()) {
      $row['replies'][] = $reply;
    }
    
    $comments[] = $row;
  }
  
  // Count total comments including replies
  $countSql = "SELECT COUNT(*) as total FROM comments WHERE story_id = ? AND status = 'approved'";
  $countStmt = $conn->prepare($countSql);
  $countStmt->bind_param("i", $article['id']);
  $countStmt->execute();
  $countResult = $countStmt->get_result();
  $comments_count = $countResult->fetch_assoc()['total'];
}

// Handle New Comment Submission
$commentSuccessMsg = '';
$commentErrorMsg = '';

if (isset($_POST['submit_comment'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $comment_text = trim($_POST['comment']);
  $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
  $article_id = $article['id'];
  
  // Simple validation
  if (empty($name)) {
    $commentErrorMsg = "Name is required";
  } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $commentErrorMsg = "Valid email is required";
  } elseif (empty($comment_text)) {
    $commentErrorMsg = "Comment cannot be empty";
  } else {
    // Insert comment
    $insertSql = "INSERT INTO comments (story_id, parent_id, name, email, comment, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iisss", $article_id, $parent_id, $name, $email, $comment_text);
    
    if ($insertStmt->execute()) {
      $commentSuccessMsg = "Your comment has been submitted and is awaiting approval.";
    } else {
      $commentErrorMsg = "Error posting comment. Please try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $article ? htmlspecialchars($article['title']) : 'Article Not Found'; ?> - Your Site Name</title>
  <?php include 'include/css-links.php' ?>
  <style>
    /* Article Styling */
    .article-content {
      font-size: 1.1rem;
      line-height: 1.8;
    }
    
    .article-content p {
      margin-bottom: 1.5rem;
    }
    
    .article-content h2, .article-content h3 {
      font-weight: 700;
      color: #333;
      margin-top: 2rem;
      margin-bottom: 1rem;
    }
    
    blockquote {
      border-radius: 5px;
      border-color: #9B5DE5 !important;
    }
    
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
    
    /* Comment Section Styling */
    .comment-list {
      max-height: 800px;
      overflow-y: auto;
    }
    
    .category-badge {
      background-color: #9B5DE5;
      color: white;
    }
    
    /* Tags */
    .tag-link {
      background-color: #f0f0f0;
      color: #333;
      transition: all 0.3s ease;
    }
    
    .tag-link:hover {
      background-color: #9B5DE5;
      color: white;
    }
    
    /* Related Article Card */
    .related-article-card {
      transition: transform 0.3s ease;
    }
    
    .related-article-card:hover {
      transform: translateY(-5px);
    }
    
    .sidebar-heading {
      border-bottom: 2px solid #9B5DE5;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    
    .reply-form {
      display: none;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <?php include 'include/navbar.php' ?>

  <?php if ($article): ?>
    <!--
    ================== 
    Article Header
    ===================
    -->
    <div class="container mt-5 pt-4">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color: #9B5DE5;">Home</a></li>
              <li class="breadcrumb-item"><a href="article.php" class="text-decoration-none" style="color: #9B5DE5;">Articles</a></li>
              <?php if (!empty($article['category_name'])): ?>
                <li class="breadcrumb-item">
                  <a href="article.php?category=<?php echo $article['category_id']; ?>" class="text-decoration-none" style="color: #9B5DE5;">
                    <?php echo htmlspecialchars($article['category_name']); ?>
                  </a>
                </li>
              <?php endif; ?>
              <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($article['title']); ?></li>
            </ol>
          </nav>

          <!-- Category Badge -->
          <?php if (!empty($article['category_name'])): ?>
            <a href="article.php?category=<?php echo $article['category_id']; ?>" class="text-decoration-none">
              <span class="badge category-badge mb-2"><?php echo htmlspecialchars($article['category_name']); ?></span>
            </a>
          <?php endif; ?>

          <!-- Article Title -->
          <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($article['title']); ?></h1>

          <!-- Article Meta -->
          <div class="d-flex align-items-center mb-4">
            <img src="https://via.placeholder.com/60" class="rounded-circle me-3" alt="Author" width="60" height="60">
            <div>
              <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($article['author']); ?></h6>
              <p class="text-muted mb-0"><?php echo htmlspecialchars($article['author_role']); ?></p>
            </div>
            <div class="ms-auto d-flex align-items-center">
              <span class="text-muted me-3"><i class="far fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($article['created_at'])); ?></span>
              <span class="text-muted"><i class="fas fa-eye me-1"></i> <?php echo number_format($article['view_count']); ?> views</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--
    ================== 
    Featured Image
    ===================
    -->
    <div class="container-fluid px-0 mb-5">
      <div class="position-relative">
        <img src="<?php echo htmlspecialchars($article['image']); ?>" class="w-100" style="max-height: 500px; object-fit: cover;" alt="Featured Image">
      </div>
    </div>

    <!--
    ================== 
    Article Content
    ===================
    -->
    <div class="container mb-5">
      <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
          <article class="article-content">
            <?php echo $article['content']; ?>
          </article>

          <!-- Tags -->
          <?php if (!empty($article_tags)): ?>
            <div class="mt-5 mb-4">
              <h5 class="mb-3">Related Topics:</h5>
              <?php foreach ($article_tags as $tag): ?>
                <a href="article.php?tag=<?php echo urlencode($tag); ?>" class="badge tag-link text-decoration-none me-2 mb-2 py-2 px-3">
                  <?php echo htmlspecialchars($tag); ?>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Share Buttons -->
          <div class="d-flex align-items-center mb-5">
            <h5 class="me-3 mb-0">Share:</h5>
            <a href="#" class="btn btn-sm rounded-circle me-2" style="background-color: #3b5998; color: white;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="btn btn-sm rounded-circle me-2" style="background-color: #1da1f2; color: white;"><i class="fab fa-twitter"></i></a>
            <a href="#" class="btn btn-sm rounded-circle me-2" style="background-color: #0e76a8; color: white;"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="btn btn-sm rounded-circle me-2" style="background-color: #25D366; color: white;"><i class="fab fa-whatsapp"></i></a>
            <a href="#" class="btn btn-sm rounded-circle" style="background-color: #BD081C; color: white;"><i class="fab fa-pinterest"></i></a>
          </div>

          <!-- Author Bio -->
          <div class="bg-light p-4 rounded mb-5">
            <div class="d-flex">
              <img src="https://via.placeholder.com/120" class="rounded-circle me-4" width="100" height="100" alt="Author">
              <div>
                <h4 class="mb-2">About <?php echo htmlspecialchars($article['author']); ?></h4>
                <p class="text-muted mb-2"><?php echo htmlspecialchars($article['author_role']); ?></p>
                <p>
                  <?php echo htmlspecialchars($article['author']); ?> is an experienced writer specializing in 
                  <?php echo !empty($article['category_name']) ? htmlspecialchars($article['category_name']) : 'various topics'; ?>.
                  With years of experience in the field, they bring valuable insights and perspectives to their writing.
                </p>
                <div class="d-flex">
                  <a href="#" class="text-decoration-none me-3" style="color: #9B5DE5;"><i class="fab fa-twitter me-1"></i> Twitter</a>
                  <a href="#" class="text-decoration-none" style="color: #9B5DE5;"><i class="fas fa-envelope me-1"></i> View all articles</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Comments Section -->
          <div class="mb-5">
            <h3 class="mb-4">Comments (<?php echo $comments_count; ?>)</h3>

            <!-- Comment Success/Error Messages -->
            <?php if (!empty($commentSuccessMsg)): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $commentSuccessMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if (!empty($commentErrorMsg)): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $commentErrorMsg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!-- Comment Form -->
            <div class="card mb-4 border-0 shadow-sm">
              <div class="card-body">
                <h5 class="mb-3">Leave a comment</h5>
                <form action="" method="post">
                  <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                  <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                      <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>
                    <div class="col-md-6">
                      <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                  </div>
                  <div class="



                  <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Your comment" required></textarea>
                  </div>
                  <button type="submit" name="submit_comment" class="btn px-4 py-2" style="background-color: #9B5DE5; color: white;">Post Comment</button>
                </form>
              </div>
            </div>

            <!-- Comment List -->
            <div class="comment-list">
              <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                  <!-- Comment -->
                  <div class="d-flex mb-4" id="comment-<?php echo $comment['id']; ?>">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($comment['email']))); ?>?s=50&d=mp" class="rounded-circle me-3" width="50" height="50" alt="Commenter">
                    <div class="flex-grow-1">
                      <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                          <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($comment['name']); ?></h6>
                          <small class="text-muted"><?php echo date('M d, Y \a\t h:i A', strtotime($comment['created_at'])); ?></small>
                        </div>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                      </div>
                      <div class="d-flex mt-2">
                        <a href="#" class="reply-btn text-decoration-none me-3" style="color: #9B5DE5;" data-comment-id="<?php echo $comment['id']; ?>">
                          <small><i class="fas fa-reply me-1"></i> Reply</small>
                        </a>
                        <a href="#" class="text-decoration-none" style="color: #9B5DE5;">
                          <small><i class="far fa-heart me-1"></i> Like</small>
                        </a>
                      </div>

                      <!-- Reply Form (Hidden by default) -->
                      <div class="reply-form mt-3 ps-4" id="reply-form-<?php echo $comment['id']; ?>">
                        <form action="" method="post">
                          <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                          <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                          <div class="row mb-3">
                            <div class="col-md-6 mb-2 mb-md-0">
                              <input type="text" class="form-control form-control-sm" name="name" placeholder="Name" required>
                            </div>
                            <div class="col-md-6">
                              <input type="email" class="form-control form-control-sm" name="email" placeholder="Email" required>
                            </div>
                          </div>
                          <div class="mb-2">
                            <textarea class="form-control form-control-sm" name="comment" rows="3" placeholder="Your reply" required></textarea>
                          </div>
                          <div>
                            <button type="submit" name="submit_comment" class="btn btn-sm" style="background-color: #9B5DE5; color: white;">
                              Post Reply
                            </button>
                            <button type="button" class="btn btn-sm btn-light cancel-reply" data-comment-id="<?php echo $comment['id']; ?>">
                              Cancel
                            </button>
                          </div>
                        </form>
                      </div>

                      <!-- Nested Replies -->
                      <?php if (!empty($comment['replies'])): ?>
                        <?php foreach ($comment['replies'] as $reply): ?>
                          <div class="d-flex mt-3 ps-4" id="comment-<?php echo $reply['id']; ?>">
                            <img src="https://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($reply['email']))); ?>?s=40&d=mp" class="rounded-circle me-3" width="40" height="40" alt="Commenter">
                            <div class="flex-grow-1">
                              <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                  <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($reply['name']); ?></h6>
                                  <small class="text-muted"><?php echo date('M d, Y \a\t h:i A', strtotime($reply['created_at'])); ?></small>
                                </div>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($reply['comment'])); ?></p>
                              </div>
                              <div class="d-flex mt-2">
                                <a href="#" class="text-decoration-none" style="color: #9B5DE5;">
                                  <small><i class="far fa-heart me-1"></i> Like</small>
                                </a>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="text-center py-4">
                  <p class="text-muted mb-0">No comments yet. Be the first to comment!</p>
                </div>
              <?php endif; ?>
              
              <?php if ($comments_count > 5): ?>
                <!-- View More Comments Button -->
                <div class="text-center">
                  <button class="btn btn-outline-secondary px-4">View More Comments</button>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Related Articles -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
              <h5 class="mb-0">Related Articles</h5>
            </div>
            <div class="card-body">
              <?php if (!empty($related_articles)): ?>
                <?php foreach ($related_articles as $related): ?>
                  <div class="d-flex mb-3 pb-3 <?php echo ($related !== end($related_articles)) ? 'border-bottom' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($related['image']); ?>" class="rounded me-3" width="80" height="80" style="object-fit: cover;" alt="Related Article">
                    <div>
                      <?php if (!empty($article['category_name'])): ?>
                        <span class="badge category-badge mb-1"><?php echo htmlspecialchars($article['category_name']); ?></span>
                      <?php endif; ?>
                      <h6 class="mb-1">
                        <a href="article-details.php?<?php echo !empty($related['slug']) ? 'slug=' . $related['slug'] : 'id=' . $related['id']; ?>" class="text-decoration-none text-dark">
                          <?php echo htmlspecialchars($related['title']); ?>
                        </a>
                      </h6>
                      <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($related['created_at'])); ?></small>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="text-center text-muted mb-0">No related articles found.</p>
              <?php endif; ?>
            </div>
          </div>

          <!-- Popular Tags -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
              <h5 class="mb-0">Popular Tags</h5>
            </div>
            <div class="card-body">
              <?php foreach ($popular_tags as $tag): ?>
                <a href="article.php?tag=<?php echo urlencode($tag); ?>" class="badge bg-light text-dark text-decoration-none me-2 mb-2 py-2 px-3">
                  <?php echo htmlspecialchars($tag); ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="card border-0 shadow-sm mb-4" style="background-color: #f8f9fa;">
            <div class="card-body p-4">
              <h5 class="mb-3">Subscribe to Newsletter</h5>
              <p class="text-muted mb-4">Get the latest news and updates delivered directly to your inbox.</p>
              <form action="subscribe.php" method="post">
                <div class="mb-3">
                  <input type="email" class="form-control" name="email" placeholder="Your email address" required>
                </div>
                <button type="submit" class="btn w-100" style="background-color: #9B5DE5; color: white;">Subscribe</button>
              </form>
            </div>
          </div>

          <!-- Social Media -->
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
              <h5 class="mb-0">Follow Us</h5>
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-outline-secondary d-flex flex-column align-items-center py-3 flex-grow-1 me-2">
                  <i class="fab fa-facebook-f mb-2"></i>
                  <span>Facebook</span>
                </a>
                <a href="#" class="btn btn-outline-secondary d-flex flex-column align-items-center py-3 flex-grow-1 me-2">
                  <i class="fab fa-twitter mb-2"></i>
                  <span>Twitter</span>
                </a>
                <a href="#" class="btn btn-outline-secondary d-flex flex-column align-items-center py-3 flex-grow-1">
                  <i class="fab fa-instagram mb-2"></i>
                  <span>Instagram</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--
    ================== 
    More From Category
    ===================
    -->
    <?php if (!empty($article['category_name'])): ?>
      <section class="py-5 bg-light">
        <div class="container">
          <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
              <h2 class="fw-bold mb-0">More From <?php echo htmlspecialchars($article['category_name']); ?></h2>
              <a href="article.php?category=<?php echo $article['category_id']; ?>" class="text-decoration-none" style="color: #9B5DE5;">
                View All <i class="fas fa-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
          mb-3">
                    <textarea class="form-control" name="comment" rows="4" placeholder="Your comment" required></textarea>
                  </div>
                  <button type="submit" name="submit_comment" class="btn px-4 py-2" style="background-color: #9B5DE5; color: white;">Post Comment</button>
                </form>
              </div>
            </div>

            <!-- Comment List -->
            <div class="comment-list">
              <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                  <!-- Comment -->
                  <div class="d-flex mb-4" id="comment-<?php echo $comment['id']; ?>">
                    <img src="https://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($comment['email']))); ?>?s=50&d=mp" class="rounded-circle me-3" width="50" height="50" alt="Commenter">
                    <div class="flex-grow-1">
                      <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                          <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($comment['name']); ?></h6>
                          <small class="text-muted"><?php echo date('M d, Y \a\t h:i A', strtotime($comment['created_at'])); ?></small>
                        </div>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                      </div>
                      <div class="d-flex mt-2">
                        <a href="#" class="reply-btn text-decoration-none me-3" style="color: #9B5DE5;" data-comment-id="<?php echo $comment['id'];<?php
          <div class="row">
            <?php
            // Fetch more articles from same category
            $moreSql = "SELECT id, title, slug, excerpt, image, created_at FROM articles 
                      WHERE category_id = ? AND id != ? 
                      ORDER BY created_at DESC LIMIT 4";
            $moreStmt = $conn->prepare($moreSql);
            $moreStmt->bind_param("ii", $article['category_id'], $article['id']);
            $moreStmt->execute();
            $moreResult = $moreStmt->get_result();
            
            while ($more = $moreResult->fetch_assoc()):
            ?>
              <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100 related-article-card">
                  <img src="<?php echo htmlspecialchars($more['image']); ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?php echo htmlspecialchars($more['title']); ?>">
                  <div class="card-body">
                    <span class="badge category-badge mb-2"><?php echo htmlspecialchars($article['category_name']); ?></span>
                    <h5 class="card-title">
                      <a href="article-details.php?<?php echo !empty($more['slug']) ? 'slug=' . $more['slug'] : 'id=' . $more['id']; ?>" class="text-decoration-none text-dark">
                        <?php echo htmlspecialchars($more['title']); ?>
                      </a>
                    </h5>
                    <p class="card-text text-truncate"><?php echo htmlspecialchars($more['excerpt']); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                      <small class="text-muted"><i class="far fa-clock me-1"></i> <?php echo date('M d, Y', strtotime($more['created_at'])); ?></small>
                      <a href="article-details.php?<?php echo !empty($more['slug']) ? 'slug=' . $more['slug'] : 'id=' . $more['id']; ?>" class="text-decoration-none" style="color: #9B5DE5;">
                        Read <i class="fas fa-arrow-right ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>
  
  <?php else: ?>
    <!-- Article Not Found -->
    <div class="container mt-5 pt-5">
      <div class="row">
        <div class="col-md-8 mx-auto text-center py-5">
          <div class="mb-4">
            <i class="bi bi-file-earmark-x" style="font-size: 5rem; color: #9B5DE5;"></i>
          </div>
          <h2 class="mb-3">Article Not Found</h2>
          <p class="text-muted mb-4">The article you're looking for doesn't exist or might have been removed.</p>
          <div>
            <a href="index.php" class="btn me-2" style="background-color: #9B5DE5; color: white;">Back to Home</a>
            <a href="article.php" class="btn btn-outline-secondary">Browse Articles</a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Scroll to Top Button -->
  <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- Footer -->
  <?php include 'include/footer.php' ?>

  <!-- JavaScript -->
  <?php include 'include/js-links.php' ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Show/hide scroll to top button
      const scrollToTopBtn = document.getElementById('scrollToTopBtn');
      
      window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
          scrollToTopBtn.classList.add('visible');
        } else {
          scrollToTopBtn.classList.remove('visible');
        }
      });
      
      scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
      
      // Comment reply functionality
      const replyButtons = document.querySelectorAll('.reply-btn');
      const cancelButtons = document.querySelectorAll('.cancel-reply');
      
      replyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          const commentId = this.getAttribute('data-comment-id');
          document.getElementById(`reply-form-${commentId}`).style.display = 'block';
        });
      });
      
      cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          const commentId = this.getAttribute('data-comment-id');
          document.getElementById(`reply-form-${commentId}`).style.display = 'none';
        });
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
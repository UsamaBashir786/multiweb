<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Process comment status changes
if (isset($_GET['action']) && isset($_GET['id'])) {
  $action = $_GET['action'];
  $comment_id = intval($_GET['id']);

  if ($action === 'approve') {
    $update_sql = "UPDATE comments SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();

    $message = "Comment approved successfully.";
    $message_type = "success";
  } elseif ($action === 'reject') {
    $update_sql = "UPDATE comments SET status = 'spam' WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();

    $message = "Comment marked as spam.";
    $message_type = "warning";
  } elseif ($action === 'delete') {
    $delete_sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();

    $message = "Comment deleted successfully.";
    $message_type = "danger";
  }
}

// Filter settings
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Build the query based on filters
$query_conditions = [];
$query_params = [];
$param_types = "";

if ($status_filter !== 'all') {
  $query_conditions[] = "c.status = ?";
  $query_params[] = $status_filter;
  $param_types .= "s";
}

if (!empty($search_term)) {
  $query_conditions[] = "(c.name LIKE ? OR c.email LIKE ? OR c.comment LIKE ?)";
  $search_term_wildcard = "%$search_term%";
  $query_params[] = $search_term_wildcard;
  $query_params[] = $search_term_wildcard;
  $query_params[] = $search_term_wildcard;
  $param_types .= "sss";
}

// Build the WHERE clause
$where_clause = "";
if (!empty($query_conditions)) {
  $where_clause = "WHERE " . implode(" AND ", $query_conditions);
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as total FROM comments c $where_clause";
$stmt = $conn->prepare($count_sql);

if (!empty($param_types)) {
  $stmt->bind_param($param_types, ...$query_params);
}

$stmt->execute();
$count_result = $stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// Fetch comments with story details
$sql = "SELECT c.*, s.title as story_title, s.id as story_id,
        DATE_FORMAT(c.created_at, '%M %d, %Y at %h:%i %p') as formatted_date,
        (SELECT COUNT(*) FROM comments WHERE parent_id = c.id) as reply_count
        FROM comments c
        LEFT JOIN stories s ON c.story_id = s.id
        $where_clause
        ORDER BY c.created_at DESC
        LIMIT ?, ?";

$stmt = $conn->prepare($sql);

if (!empty($param_types)) {
  $stmt->bind_param($param_types . "ii", ...[...$query_params, $offset, $per_page]);
} else {
  $stmt->bind_param("ii", $offset, $per_page);
}

$stmt->execute();
$result = $stmt->get_result();
$comments = [];

while ($row = $result->fetch_assoc()) {
  $comments[] = $row;
}

// Count comments by status
$status_counts = [
  'all' => $total_records,
  'pending' => 0,
  'approved' => 0,
  'spam' => 0
];

$count_by_status_sql = "SELECT status, COUNT(*) as count FROM comments GROUP BY status";
$status_counts_result = $conn->query($count_by_status_sql);

while ($row = $status_counts_result->fetch_assoc()) {
  $status_counts[$row['status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Comments - Admin Panel</title>
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

    .status-badge {
      font-size: 0.75rem;
      font-weight: 500;
      padding: 0.35em 0.65em;
    }

    .comment-text {
      max-height: 100px;
      overflow: hidden;
      position: relative;
    }

    .comment-text.expanded {
      max-height: none;
    }

    .read-more {
      position: absolute;
      bottom: 0;
      right: 0;
      background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 50%);
      padding: 0 10px;
      cursor: pointer;
      color: #6c757d;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .actions-col {
      width: 180px;
    }

    .avatar-sm {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }
  </style>
</head>

<body>
  <?php include 'include/slidebar.php' ?>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="row mb-4">
      <div class="col">
        <h2 class="mb-0">Manage Comments</h2>
      </div>
    </div>

    <?php if (isset($message)): ?>
      <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link <?= $status_filter === 'all' ? 'active' : '' ?>" href="?status=all<?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  All <span class="badge bg-secondary ms-1"><?= $status_counts['all'] ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $status_filter === 'pending' ? 'active' : '' ?>" href="?status=pending<?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  Pending <span class="badge bg-warning text-dark ms-1"><?= $status_counts['pending'] ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $status_filter === 'approved' ? 'active' : '' ?>" href="?status=approved<?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  Approved <span class="badge bg-success ms-1"><?= $status_counts['approved'] ?></span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?= $status_filter === 'spam' ? 'active' : '' ?>" href="?status=spam<?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  Spam <span class="badge bg-danger ms-1"><?= $status_counts['spam'] ?></span>
                </a>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            <form action="" method="get" class="mt-3 mt-md-0">
              <?php if ($status_filter !== 'all'): ?>
                <input type="hidden" name="status" value="<?= $status_filter ?>">
              <?php endif; ?>
              <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search comments..." value="<?= htmlspecialchars($search_term) ?>">
                <button class="btn btn-outline-secondary" type="submit">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Comments Table -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th scope="col">Author</th>
                <th scope="col">Comment</th>
                <th scope="col">In Response To</th>
                <th scope="col">Submitted On</th>
                <th scope="col" class="actions-col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($comments)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4">No comments found.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($comment['email']))) ?>?s=32&d=mp" class="avatar-sm me-2" alt="<?= htmlspecialchars($comment['name']) ?>">
                        <div>
                          <div><?= htmlspecialchars($comment['name']) ?></div>
                          <div class="small text-muted"><?= htmlspecialchars($comment['email']) ?></div>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="comment-text">
                        <?php if ($comment['parent_id']): ?>
                          <span class="badge bg-secondary mb-1">Reply</span>
                        <?php endif; ?>

                        <div><?= nl2br(htmlspecialchars($comment['comment'])) ?></div>

                        <?php if (strlen($comment['comment']) > 150): ?>
                          <span class="read-more">Read more</span>
                        <?php endif; ?>
                      </div>

                      <?php if ($comment['reply_count'] > 0): ?>
                        <div class="mt-1">
                          <span class="badge bg-info text-white"><?= $comment['reply_count'] ?> replies</span>
                        </div>
                      <?php endif; ?>

                      <span class="status-badge badge 
                        <?php
                        if ($comment['status'] === 'approved') echo 'bg-success';
                        elseif ($comment['status'] === 'pending') echo 'bg-warning text-dark';
                        else echo 'bg-danger';
                        ?>">
                        <?= ucfirst($comment['status']) ?>
                      </span>
                    </td>
                    <td>
                      <a href="../story-detail.php?id=<?= $comment['story_id'] ?>#comment-<?= $comment['id'] ?>" target="_blank">
                        <?= htmlspecialchars($comment['story_title']) ?>
                      </a>
                    </td>
                    <td>
                      <?= htmlspecialchars($comment['formatted_date']) ?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <?php if ($comment['status'] !== 'approved'): ?>
                          <a href="?action=approve&id=<?= $comment['id'] ?>&status=<?= $status_filter ?>&page=<?= $page ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>" class="btn btn-sm btn-outline-success" title="Approve">
                            <i class="bi bi-check-lg"></i>
                          </a>
                        <?php endif; ?>

                        <?php if ($comment['status'] !== 'spam'): ?>
                          <a href="?action=reject&id=<?= $comment['id'] ?>&status=<?= $status_filter ?>&page=<?= $page ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>" class="btn btn-sm btn-outline-warning" title="Mark as Spam">
                            <i class="bi bi-exclamation-triangle"></i>
                          </a>
                        <?php endif; ?>

                        <a href="#" class="btn btn-sm btn-outline-secondary view-comment-btn"
                          data-bs-toggle="modal"
                          data-bs-target="#viewCommentModal"
                          data-comment-id="<?= $comment['id'] ?>"
                          data-comment-name="<?= htmlspecialchars($comment['name']) ?>"
                          data-comment-email="<?= htmlspecialchars($comment['email']) ?>"
                          data-comment-date="<?= htmlspecialchars($comment['formatted_date']) ?>"
                          data-comment-content="<?= htmlspecialchars($comment['comment']) ?>"
                          data-comment-story="<?= htmlspecialchars($comment['story_title']) ?>"
                          data-comment-status="<?= $comment['status'] ?>"
                          title="View">
                          <i class="bi bi-eye"></i>
                        </a>

                        <a href="?action=delete&id=<?= $comment['id'] ?>&status=<?= $status_filter ?>&page=<?= $page ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this comment?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
        <div class="card-footer bg-white">
          <nav aria-label="Comments pagination">
            <ul class="pagination justify-content-center mb-0">
              <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?status=<?= $status_filter ?>&page=<?= $page - 1 ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  Previous
                </a>
              </li>

              <?php
              $start_page = max(1, $page - 2);
              $end_page = min($total_pages, $start_page + 4);
              if ($end_page - $start_page < 4 && $start_page > 1) {
                $start_page = max(1, $end_page - 4);
              }
              ?>

              <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                  <a class="page-link" href="?status=<?= $status_filter ?>&page=<?= $i ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?status=<?= $status_filter ?>&page=<?= $page + 1 ?><?= !empty($search_term) ? '&search=' . $search_term : '' ?>">
                  Next
                </a>
              </li>
            </ul>
          </nav>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- View Comment Modal -->
  <div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewCommentModalLabel">Comment Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Author Name:</label>
                <div id="modalCommentName"></div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Email:</label>
                <div id="modalCommentEmail"></div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Submitted On:</label>
                <div id="modalCommentDate"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Story:</label>
                <div id="modalCommentStory"></div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold">Status:</label>
                <div id="modalCommentStatus"></div>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Comment:</label>
            <div class="p-3 bg-light rounded" id="modalCommentContent"></div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="modalCommentId" value="">
          <a href="#" id="modalApproveBtn" class="btn btn-success">Approve</a>
          <a href="#" id="modalRejectBtn" class="btn btn-warning">Mark as Spam</a>
          <a href="#" id="modalDeleteBtn" class="btn btn-danger">Delete</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      // Read more functionality
      $('.read-more').click(function() {
        $(this).parent().toggleClass('expanded');
        if ($(this).parent().hasClass('expanded')) {
          $(this).text('Show less');
        } else {
          $(this).text('Read more');
        }
      });

      // Auto dismiss alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);

      // Set modal content when view button is clicked
      $('.view-comment-btn').click(function() {
        const id = $(this).data('comment-id');
        const name = $(this).data('comment-name');
        const email = $(this).data('comment-email');
        const date = $(this).data('comment-date');
        const content = $(this).data('comment-content').replace(/\n/g, '<br>');
        const story = $(this).data('comment-story');
        const status = $(this).data('comment-status');

        $('#modalCommentId').val(id);
        $('#modalCommentName').text(name);
        $('#modalCommentEmail').text(email);
        $('#modalCommentDate').text(date);
        $('#modalCommentContent').html(content);
        $('#modalCommentStory').text(story);

        // Set status with badge
        let statusHTML = '';
        if (status === 'approved') {
          statusHTML = '<span class="badge bg-success">Approved</span>';
        } else if (status === 'pending') {
          statusHTML = '<span class="badge bg-warning text-dark">Pending</span>';
        } else if (status === 'spam') {
          statusHTML = '<span class="badge bg-danger">Spam</span>';
        }
        $('#modalCommentStatus').html(statusHTML);

        // Set action button states and URLs
        const baseURL = `?id=${id}&status=<?= $status_filter ?>&page=<?= $page ?><?= !empty($search_term) ? '&search=' . urlencode($search_term) : '' ?>`;

        $('#modalApproveBtn').attr('href', `${baseURL}&action=approve`);
        $('#modalRejectBtn').attr('href', `${baseURL}&action=reject`);
        $('#modalDeleteBtn').attr('href', `${baseURL}&action=delete`);

        if (status === 'approved') {
          $('#modalApproveBtn').hide();
        } else {
          $('#modalApproveBtn').show();
        }

        if (status === 'spam') {
          $('#modalRejectBtn').hide();
        } else {
          $('#modalRejectBtn').show();
        }

        // Confirm delete
        $('#modalDeleteBtn').off('click').on('click', function(e) {
          if (!confirm('Are you sure you want to delete this comment?')) {
            e.preventDefault();
          }
        });
      });
    });
  </script>
</body>

</html>
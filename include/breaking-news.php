<?php
// Fetch active breaking news items
$breakingNews = [];
$newsQuery = "SELECT title, link, icon FROM breaking_news WHERE is_active = 1 ORDER BY priority DESC, created_at DESC LIMIT 10";
$newsResult = $conn->query($newsQuery);

if ($newsResult && $newsResult->num_rows > 0) {
  while ($row = $newsResult->fetch_assoc()) {
    $breakingNews[] = $row;
  }
}

// Only display the breaking news section if there are active items
if (count($breakingNews) > 0):
?>

  <!--
================== 
Breaking News 
===================
-->
  <section class="my-5 breaking-news-ticker py-3" style="background-color: #9B5DE5;">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <span class="badge bg-danger px-3 py-2 fs-6 fw-bold">BREAKING</span>
            </div>
            <div class="ticker-wrapper overflow-hidden" style="width: 100%;">
              <div class="ticker-content d-flex" style="animation: ticker-scroll <?php echo count($breakingNews) * 10; ?>s linear infinite; white-space: nowrap;">
                <?php foreach ($breakingNews as $news): ?>
                  <div class="ticker-item me-5">
                    <?php if (!empty($news['link'])): ?>
                      <a href="<?php echo htmlspecialchars($news['link']); ?>" class="text-white text-decoration-none">
                        <i class="<?php echo htmlspecialchars($news['icon']); ?> me-2"></i>
                        <?php echo htmlspecialchars($news['title']); ?>
                      </a>
                    <?php else: ?>
                      <span class="text-white">
                        <i class="<?php echo htmlspecialchars($news['icon']); ?> me-2"></i>
                        <?php echo htmlspecialchars($news['title']); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <style>
    @keyframes ticker-scroll {
      0% {
        transform: translateX(100%);
      }

      100% {
        transform: translateX(-100%);
      }
    }

    .ticker-wrapper {
      position: relative;
      overflow: hidden;
    }

    .ticker-content {
      position: relative;
      width: 100%;
      overflow: visible;
    }

    .ticker-item {
      font-weight: 500;
      font-size: 1.1rem;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const tickerContent = document.querySelector('.ticker-content');

      if (tickerContent) {
        // Pause animation on hover
        tickerContent.addEventListener('mouseenter', function() {
          this.style.animationPlayState = 'paused';
        });

        // Resume animation when mouse leaves
        tickerContent.addEventListener('mouseleave', function() {
          this.style.animationPlayState = 'running';
        });
      }
    });
  </script>
<?php endif; ?>
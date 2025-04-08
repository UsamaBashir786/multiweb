<?php
// Check if article ID is provided
$article_id = isset($_GET['id']) ? $_GET['id'] : null;

// In a real implementation, you would fetch the article data from a database
// For now, we'll use dummy data for demonstration
$article = [
  'id' => $article_id ?? 1,
  'title' => 'Major Breakthrough in Renewable Energy Research',
  'category' => 'Technology',
  'author' => 'John Doe',
  'author_role' => 'Science & Technology Editor',
  'author_bio' => 'John Doe has been covering science and technology developments for over 12 years. With a background in Environmental Engineering and Science Communication, he specializes in renewable energy technologies and climate solutions. He has received multiple awards for his reporting on emerging technologies.',
  'date' => 'April 8, 2025',
  'views' => '2.4K',
  'image' => 'https://via.placeholder.com/1920x800',
  'caption' => 'Scientists have made a significant breakthrough in solar energy conversion efficiency',
  'content' => '<p class="lead mb-4">A team of international researchers has announced a major breakthrough in renewable energy technology that could revolutionize how solar power is harnessed and stored, potentially making it more efficient and accessible worldwide.</p>
          
          <p>The research team, led by scientists from the International Energy Research Institute, has developed a new type of photovoltaic cell that can achieve unprecedented energy conversion rates of up to 45%, significantly higher than the current commercial solar panels that typically convert 15-22% of sunlight into electricity.</p>
          
          <h2 class="mt-5 mb-3">Revolutionizing Solar Technology</h2>
          
          <p>The breakthrough involves a novel material composition that can capture and convert a broader spectrum of solar radiation, including previously untapped infrared wavelengths. This advancement marks a significant milestone in the field of renewable energy research.</p>
          
          <p>"This discovery represents the culmination of over a decade of collaborative research," said Dr. Eleanor Richards, the lead researcher on the project. "Our team has developed a multi-junction solar cell using a combination of perovskite and silicon materials that fundamentally changes what\'s possible in solar energy conversion."</p>
          
          <blockquote class="blockquote my-5 p-4 border-start border-5 bg-light" style="border-color: #9B5DE5 !important;">
            <p>"We\'re not just incrementally improving existing technology – we\'re introducing an entirely new paradigm for how solar energy can be harvested. This could be as transformative to renewable energy as the transistor was to computing."</p>
            <footer class="blockquote-footer mt-2">Dr. Eleanor Richards, Lead Researcher</footer>
          </blockquote>
          
          <h2 class="mt-5 mb-3">Industry Implications</h2>
          
          <p>Energy experts suggest this breakthrough could accelerate the global transition to renewable energy sources by making solar power more efficient and cost-effective. Market analysts predict that if successfully commercialized, these new solar cells could reduce the cost of solar energy by up to 60% over the next decade.</p>
          
          <p>The research has already attracted significant attention from major energy companies, with several expressing interest in partnering to bring the technology to market. Government agencies from multiple countries have also expressed interest in supporting further development.</p>
          
          <div class="row my-5">
            <div class="col-md-6">
              <img src="https://via.placeholder.com/600x400" class="img-fluid rounded mb-3" alt="Research Lab">
              <p class="text-muted text-center fst-italic">Researchers working in the advanced materials laboratory</p>
            </div>
            <div class="col-md-6">
              <img src="https://via.placeholder.com/600x400" class="img-fluid rounded mb-3" alt="Solar Panel Installation">
              <p class="text-muted text-center fst-italic">Prototype installation of the new photovoltaic technology</p>
            </div>
          </div>
          
          <h2 class="mt-5 mb-3">Environmental Impact</h2>
          
          <p>Beyond the economic benefits, the environmental implications of this breakthrough are substantial. Researchers estimate that widespread adoption of this technology could reduce global carbon emissions by up to 8% within the next 15 years – a significant contribution to international climate goals.</p>
          
          <p>The technology also addresses another critical challenge in renewable energy: efficiency in varying weather conditions. Initial tests suggest the new cells maintain higher efficiency levels even in low-light conditions, potentially making solar power more viable in regions that previously weren\'t considered suitable for solar energy production.</p>
          
          <h2 class="mt-5 mb-3">What\'s Next for the Research</h2>
          
          <p>The team is now focused on scaling up production and conducting real-world testing. They estimate that commercial applications could begin within 3-5 years, pending successful field trials and manufacturing partnerships.</p>
          
          <p>Funding for continued research has been secured through a combination of government grants, private investment, and academic research partnerships, ensuring the momentum behind this breakthrough can be maintained through the crucial next phases of development.</p>
          
          <p>The full research findings have been published in the latest issue of the journal Nature Energy, detailing both the scientific innovations and potential applications of this groundbreaking technology.</p>',
  'tags' => ['Renewable Energy', 'Solar Power', 'Climate Change', 'Technology', 'Research'],
  'comments_count' => 12
];

// Related articles (dummy data)
$related_articles = [
  [
    'id' => 2,
    'title' => 'New Battery Storage Technology Shows Promise',
    'category' => 'Technology',
    'date' => 'April 6, 2025',
    'image' => 'https://via.placeholder.com/100'
  ],
  [
    'id' => 3,
    'title' => 'Global Investment in Clean Energy Reaches Record High',
    'category' => 'Environment',
    'date' => 'April 5, 2025',
    'image' => 'https://via.placeholder.com/100'
  ],
  [
    'id' => 4,
    'title' => 'Quantum Computing Advancements Promise Energy Optimization',
    'category' => 'Science',
    'date' => 'April 3, 2025',
    'image' => 'https://via.placeholder.com/100'
  ],
  [
    'id' => 5,
    'title' => 'New Legislation Aims to Accelerate Renewable Energy Adoption',
    'category' => 'Policy',
    'date' => 'April 2, 2025',
    'image' => 'https://via.placeholder.com/100'
  ]
];

// Popular tags (dummy data)
$popular_tags = ['Technology', 'Science', 'Politics', 'Business', 'Health', 'Environment', 'Education', 'Entertainment', 'Sports', 'Lifestyle'];

// More from category (dummy data)
$more_articles = [
  [
    'id' => 6,
    'title' => 'AI Assistants Becoming More Human-Like',
    'category' => 'Technology',
    'date' => 'April 7, 2025',
    'image' => 'https://via.placeholder.com/400x300',
    'excerpt' => 'Latest advancements in natural language processing...'
  ],
  [
    'id' => 7,
    'title' => '5G Networks Expand to Rural Areas',
    'category' => 'Technology',
    'date' => 'April 6, 2025',
    'image' => 'https://via.placeholder.com/400x300',
    'excerpt' => 'New infrastructure initiatives bring high-speed connectivity...'
  ],
  [
    'id' => 8,
    'title' => 'Electric Vehicles Set New Range Records',
    'category' => 'Technology',
    'date' => 'April 5, 2025',
    'image' => 'https://via.placeholder.com/400x300',
    'excerpt' => 'Breakthrough battery technology extends driving range...'
  ],
  [
    'id' => 9,
    'title' => 'Wearable Health Tech Becomes More Accurate',
    'category' => 'Technology',
    'date' => 'April 4, 2025',
    'image' => 'https://via.placeholder.com/400x300',
    'excerpt' => 'New sensors provide medical-grade monitoring...'
  ]
];

// Comments (dummy data)
$comments = [
  [
    'id' => 1,
    'name' => 'Michael Brown',
    'image' => 'https://via.placeholder.com/60',
    'time' => '2 hours ago',
    'content' => 'This is truly revolutionary! I wonder how long until we see this technology integrated into consumer products. The efficiency rates they\'re reporting would be game-changing for residential solar installations.',
    'replies' => [
      [
        'id' => 2,
        'name' => 'John Doe',
        'is_author' => true,
        'image' => 'https://via.placeholder.com/60',
        'time' => '1 hour ago',
        'content' => 'Thanks for your comment, Michael! According to the researchers, they\'re aiming for commercial applications within 3-5 years. Several companies are already working on scaling up production for residential use.'
      ]
    ]
  ],
  [
    'id' => 3,
    'name' => 'Sarah Johnson',
    'image' => 'https://via.placeholder.com/60',
    'time' => '5 hours ago',
    'content' => 'I\'m curious about the durability and lifespan of these new cells. Current solar panels degrade over time - have they addressed this issue with the new material composition?',
    'replies' => []
  ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($article['title']) ?></title>
  <!-- css -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- bootstrap css -->
  <link rel="stylesheet" href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <!-- font awesome icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
  <!-- navbar -->
  <?php include 'include/navbar.php' ?>

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
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none" style="color: #9B5DE5;"><?= htmlspecialchars($article['category']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($article['title']) ?></li>
          </ol>
        </nav>

        <!-- Category Badge -->
        <span class="badge mb-2" style="background-color: #9B5DE5;"><?= htmlspecialchars($article['category']) ?></span>

        <!-- Article Title -->
        <h1 class="fw-bold mb-3"><?= htmlspecialchars($article['title']) ?></h1>

        <!-- Article Meta -->
        <div class="d-flex align-items-center mb-4">
          <img src="https://via.placeholder.com/60" class="rounded-circle me-3" alt="Author" width="60" height="60">
          <div>
            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($article['author']) ?></h6>
            <p class="text-muted mb-0"><?= htmlspecialchars($article['author_role']) ?></p>
          </div>
          <div class="ms-auto d-flex align-items-center">
            <span class="text-muted me-3"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($article['date']) ?></span>
            <span class="text-muted"><i class="fas fa-eye me-1"></i> <?= htmlspecialchars($article['views']) ?> views</span>
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
      <img src="<?= htmlspecialchars($article['image']) ?>" class="w-100" style="max-height: 500px; object-fit: cover;" alt="Featured Image">
      <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
        <div class="container">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <p class="text-white fst-italic mb-0"><?= htmlspecialchars($article['caption']) ?></p>
            </div>
          </div>
        </div>
      </div>
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
          <?= $article['content'] ?>
        </article>

        <!-- Tags -->
        <div class="mt-5 mb-4">
          <h5 class="mb-3">Related Topics:</h5>
          <?php foreach ($article['tags'] as $tag): ?>
            <a href="#" class="badge text-bg-secondary text-decoration-none me-2 mb-2 py-2 px-3"><?= htmlspecialchars($tag) ?></a>
          <?php endforeach; ?>
        </div>

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
              <h4 class="mb-2">About <?= htmlspecialchars($article['author']) ?></h4>
              <p class="text-muted mb-2"><?= htmlspecialchars($article['author_role']) ?></p>
              <p><?= htmlspecialchars($article['author_bio']) ?></p>
              <div class="d-flex">
                <a href="#" class="text-decoration-none me-3" style="color: #9B5DE5;"><i class="fab fa-twitter me-1"></i> @johndoe</a>
                <a href="#" class="text-decoration-none" style="color: #9B5DE5;"><i class="fas fa-envelope me-1"></i> View all articles</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Comments Section -->
        <div class="mb-5">
          <h3 class="mb-4">Comments (<?= $article['comments_count'] ?>)</h3>

          <!-- Comment Form -->
          <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
              <h5 class="mb-3">Leave a comment</h5>
              <form action="process_comment.php" method="post">
                <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                <div class="row mb-3">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                  </div>
                  <div class="col-md-6">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                  </div>
                </div>
                <div class="mb-3">
                  <textarea class="form-control" name="comment" rows="4" placeholder="Your comment" required></textarea>
                </div>
                <button type="submit" class="btn px-4 py-2" style="background-color: #9B5DE5; color: white;">Post Comment</button>
              </form>
            </div>
          </div>

          <!-- Comment List -->
          <div class="comment-list">
            <?php foreach ($comments as $comment): ?>
              <!-- Comment -->
              <div class="d-flex mb-4">
                <img src="<?= htmlspecialchars($comment['image']) ?>" class="rounded-circle me-3" width="50" height="50" alt="Commenter">
                <div class="flex-grow-1">
                  <div class="bg-light p-3 rounded">
                    <div class="d-flex justify-content-between mb-2">
                      <h6 class="mb-0 fw-bold"><?= htmlspecialchars($comment['name']) ?></h6>
                      <small class="text-muted"><?= htmlspecialchars($comment['time']) ?></small>
                    </div>
                    <p class="mb-0"><?= htmlspecialchars($comment['content']) ?></p>
                  </div>
                  <div class="d-flex mt-2">
                    <a href="#" class="text-decoration-none me-3" style="color: #9B5DE5;"><small><i class="fas fa-reply me-1"></i> Reply</small></a>
                    <a href="#" class="text-decoration-none" style="color: #9B5DE5;"><small><i class="far fa-heart me-1"></i> Like</small></a>
                  </div>

                  <!-- Nested Replies -->
                  <?php foreach ($comment['replies'] as $reply): ?>
                    <div class="d-flex mt-3 ps-4">
                      <img src="<?= htmlspecialchars($reply['image']) ?>" class="rounded-circle me-3" width="40" height="40" alt="Commenter">
                      <div class="flex-grow-1">
                        <div class="bg-light p-3 rounded">
                          <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0 fw-bold">
                              <?= htmlspecialchars($reply['name']) ?>
                              <?php if (isset($reply['is_author']) && $reply['is_author']): ?>
                                <span class="badge text-bg-secondary ms-2">Author</span>
                              <?php endif; ?>
                            </h6>
                            <small class="text-muted"><?= htmlspecialchars($reply['time']) ?></small>
                          </div>
                          <p class="mb-0"><?= htmlspecialchars($reply['content']) ?></p>
                        </div>
                        <div class="d-flex mt-2">
                          <a href="#" class="text-decoration-none me-3" style="color: #9B5DE5;"><small><i class="fas fa-reply me-1"></i> Reply</small></a>
                          <a href="#" class="text-decoration-none" style="color: #9B5DE5;"><small><i class="far fa-heart me-1"></i> Like</small></a>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>

            <!-- View More Comments Button -->
            <div class="text-center">
              <button class="btn btn-outline-secondary px-4">View More Comments</button>
            </div>
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
            <?php foreach ($related_articles as $related): ?>
              <div class="d-flex mb-3 pb-3 <?= $related !== end($related_articles) ? 'border-bottom' : '' ?>">
                <img src="<?= htmlspecialchars($related['image']) ?>" class="rounded me-3" width="80" height="80" alt="Related Article">
                <div>
                  <span class="badge mb-1" style="background-color: #9B5DE5;"><?= htmlspecialchars($related['category']) ?></span>
                  <h6 class="mb-1">
                    <a href="article.php?id=<?= $related['id'] ?>" class="text-decoration-none text-dark">
                      <?= htmlspecialchars($related['title']) ?>
                    </a>
                  </h6>
                  <small class="text-muted"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($related['date']) ?></small>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Popular Tags -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0">
            <h5 class="mb-0">Popular Tags</h5>
          </div>
          <div class="card-body">
            <?php foreach ($popular_tags as $tag): ?>
              <a href="#" class="badge text-bg-light text-decoration-none me-2 mb-2 py-2 px-3"><?= htmlspecialchars($tag) ?></a>
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
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
          <h2 class="fw-bold mb-0">More From <?= htmlspecialchars($article['category']) ?></h2>
          <a href="#" class="text-decoration-none" style="color: #9B5DE5;">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
      </div>

      <div class="row">
        <?php foreach ($more_articles as $more): ?>
          <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
              <img src="<?= htmlspecialchars($more['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($more['category']) ?> News">
              <div class="card-body">
                <span class="badge mb-2" style="background-color: #9B5DE5;"><?= htmlspecialchars($more['category']) ?></span>
                <h5 class="card-title"><?= htmlspecialchars($more['title']) ?></h5>
                <p class="card-text text-truncate"><?= htmlspecialchars($more['excerpt']) ?></p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <small class="text-muted"><i class="far fa-clock me-1"></i> <?= htmlspecialchars($more['date']) ?></small>
                  <a href="article.php?id=<?= $more['id'] ?>" class="text-decoration-none" style="color: #9B5DE5;">Read <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Scroll to Top Button -->
  <button id="scrollToTopBtn" class="scroll-to-top-btn" aria-label="Scroll to top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-- footer -->
  <?php include 'include/footer.php' ?>

  <!-- bootstrap js -->
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
  <!-- js -->
  <script src="assets/js/script.js"></script>

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
    });
  </script>

  <style>
    /* Article Styling */
    .article-content p {
      font-size: 1.1rem;
      line-height: 1.8;
      margin-bottom: 1.5rem;
    }

    .article-content h2 {
      font-weight: 700;
      color: #333;
    }

    blockquote {
      border-radius: 5px;
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

    .scroll-to-top-btn:active {
      transform: translateY(0);
    }

    @media (max-width: 576px) {
      .scroll-to-top-btn {
        width: 40px;
        height: 40px;
        bottom: 20px;
        right: 20px;
      }
    }

    /* Comment Section Styling */
    .comment-list {
      max-height: 800px;
      overflow-y: auto;
    }

    /* Related Articles */
    .card-header {
      font-weight: 600;
    }

    /* Improved Readability for Article */
    @media (min-width: 992px) {
      .article-content {
        padding-right: 2rem;
      }
    }

    /* Image Captions */
    .text-center.fst-italic {
      font-size: 0.9rem;
    }

    /* Author Bio Box */
    .bg-light {
      background-color: #f8f9fa !important;
    }
  </style>
</body>

</html>
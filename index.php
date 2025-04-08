<!DOCTYPE html>
<html lang="en">

<head>
  <!--================== CSS LINKS ===================-->
  <?php include 'include/css-links.php' ?>
</head>

<body>
  <!--================== navbar ===================-->
  <?php include 'include/navbar.php' ?>
  <!--================== hero ===================-->

  <div class="mt-5 container d-sm-flex align-items-center justify-content-between w-100" style="height: 100vh;">
    <div class="col-md-4 mx-auto mb-4 mb-sm-0 headline">
      <span class="text-secondary text-uppercase">Subheadline</span>
      <h1 class="display-4 my-4 font-weight-bold">Enter Your <span style="color: #9B5DE5;">Headline Here</span></h1>
      <a href="#" class="btn px-5 py-3 text-white mt-3 mt-sm-0" style="border-radius: 30px; background-color: #9B5DE5;">Get Started</a>
    </div>
    <!-- in mobile remove the clippath -->
    <div class="col-md-8 h-100 clipped" style="min-height: 350px; background-image: url(https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=2850&q=80); background-position: center; background-size: cover;">

    </div>
  </div>


  <!--================== breaking news ===================-->

  <?php include 'include/breaking-news.php' ?>

  <!--================== trending news ===================-->

  <?php include 'include/trending-news.php' ?>

  <!--================== Stories ===================-->

  <?php include 'include/stories.php' ?>

  <!--================== Categories ===================-->

  <?php include 'include/categories.php' ?>




  <!--================== scroll to top ===================-->
  <?php include 'include/scroll-top.php' ?>
  <!--================== footer ===================-->
  <?php include 'include/footer.php' ?>
  <!--================== js links ===================-->
  <?php include 'include/js-links.php' ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap-5.3.5-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
  <div class="login-container">
    <br><br>
    <br><br>
    <div class="card">
      <div class="card-header">
        <h2>Welcome Back</h2>
        <p>Enter your credentials to access your account</p>
      </div>
      <div class="card-body">
        <form action="#" method="post">
          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-envelope text-primary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="email" class="form-control border-start-0" id="emailInput" placeholder="name@example.com" required>
              <label for="emailInput">Email address</label>
            </div>
          </div>
          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-lock text-primary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="password" class="form-control border-start-0" id="passwordInput" placeholder="Password" required>
              <label for="passwordInput">Password</label>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberCheck">
              <label class="form-check-label" for="rememberCheck">
                Remember me
              </label>
            </div>
            <a href="#" class="text-decoration-none">Forgot password?</a>
          </div>

          <button type="submit" class="btn btn-login w-100">Sign In</button>
        </form>

        <div class="divider">
          <span>OR</span>
        </div>

        <div class="social-login">
          <a href="#" class="social-btn google">
            <i class="fab fa-google"></i>
          </a>
          <a href="#" class="social-btn facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
          <a href="#" class="social-btn twitter">
            <i class="fab fa-twitter"></i>
          </a>
        </div>

        <div class="register-link">
          <p>Don't have an account? <a href="register.html">Create one now</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>
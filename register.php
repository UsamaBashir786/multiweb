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
  <div class="register-container">
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <br><br><br>
    <div class="card">
      <div class="card-header">
        <h2>Create an Account</h2>
        <p>Fill in your information to get started</p>
      </div>
      <div class="card-body">
        <form action="#" method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group mb-3">
                <span class="input-group-text bg-transparent border-end-0">
                  <i class="fas fa-user text-secondary"></i>
                </span>
                <div class="form-floating flex-grow-1">
                  <input type="text" class="form-control border-start-0" id="firstNameInput" placeholder="First Name" required>
                  <label for="firstNameInput">First Name</label>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group mb-3">
                <span class="input-group-text bg-transparent border-end-0">
                  <i class="fas fa-user text-secondary"></i>
                </span>
                <div class="form-floating flex-grow-1">
                  <input type="text" class="form-control border-start-0" id="lastNameInput" placeholder="Last Name" required>
                  <label for="lastNameInput">Last Name</label>
                </div>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-envelope text-secondary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="email" class="form-control border-start-0" id="emailInput" placeholder="name@example.com" required>
              <label for="emailInput">Email address</label>
            </div>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="password" class="form-control border-start-0" id="passwordInput" placeholder="Password" required oninput="checkPasswordStrength()">
              <label for="passwordInput">Password</label>
            </div>
          </div>

          <div class="password-strength">
            <div class="progress-bar" role="progressbar" style="width: 0%; background-color: #e74a3b;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>

          <div class="password-requirements">
            <p><i class="fas fa-info-circle me-1"></i> Password must contain:</p>
            <ul>
              <li><i class="fas fa-check-circle me-1 text-muted"></i> At least 8 characters</li>
              <li><i class="fas fa-check-circle me-1 text-muted"></i> At least one uppercase letter</li>
              <li><i class="fas fa-check-circle me-1 text-muted"></i> At least one lowercase letter</li>
              <li><i class="fas fa-check-circle me-1 text-muted"></i> At least one number</li>
              <li><i class="fas fa-check-circle me-1 text-muted"></i> At least one special character</li>
            </ul>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="password" class="form-control border-start-0" id="confirmPasswordInput" placeholder="Confirm Password" required>
              <label for="confirmPasswordInput">Confirm Password</label>
            </div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="termsCheck" required>
            <label class="form-check-label" for="termsCheck">
              I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="btn btn-register w-100">Create Account</button>
        </form>

        <div class="divider">
          <span>OR</span>
        </div>

        <div class="social-register">
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

        <div class="login-link">
          <p>Already have an account? <a href="login.html">Sign in here</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.bundle.js"></script>
  <script src="assets/bootstrap-5.3.5-dist/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
  <script src="assets/js/script.js"></script>
</body>

</html>
<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: login.php");
    exit();
  }

  // Check user exists and verify password
  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['first_name'] = $user['first_name'];
      $_SESSION['last_name'] = $user['last_name'];
      $_SESSION['success'] = "Welcome back, " . $user['first_name'] . "!";
      header("Location: index.php");
      exit();
    } else {
      $_SESSION['error'] = "Invalid email or password";
    }
  } else {
    $_SESSION['error'] = "Invalid email or password";
  }

  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Lion Of Web</title>
  <?php include 'include/css-links.php' ?>
  <style>
    .login-container {
      min-height: 100vh;
      /* display: flex; */
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-card {
      max-width: 500px;
      width: 100%;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .login-header {
      background: #9B5DE5;
      color: white;
      padding: 30px;
      text-align: center;
    }

    .login-header h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .login-body {
      padding: 40px;
    }

    .form-floating>.form-control {
      padding: 1rem 0.75rem;
    }

    .btn-login {
      background-color: #9B5DE5;
      color: white;
      padding: 12px 30px;
      border-radius: 30px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background-color: #8348c6;
      color: white;
      transform: translateY(-2px);
    }

    .login-footer {
      text-align: center;
      padding: 20px 40px;
      background: #f8f9fa;
    }

    .input-group-text {
      border-right: none;
    }

    .form-control {
      border-left: none;
    }

    .form-control:focus {
      border-color: #ced4da;
      box-shadow: none;
    }

    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
      border-color: #9B5DE5;
    }

    .remember-me {
      color: #6c757d;
    }

    .forgot-password {
      color: #9B5DE5;
      text-decoration: none;
    }

    .forgot-password:hover {
      color: #8348c6;
      text-decoration: underline;
    }

    .register-link {
      color: #9B5DE5;
      text-decoration: none;
      font-weight: 500;
    }

    .register-link:hover {
      color: #8348c6;
      text-decoration: underline;
    }
  </style>
</head>

<body class="bg-light">
  <?php include 'include/navbar.php' ?>

  <div class="login-container">
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <div class="login-card">
      <div class="login-header">
        <h1>Welcome Back!</h1>
        <p>Sign in to continue to Lion Of Web</p>
      </div>

      <div class="login-body">
        <?php if (isset($_SESSION['error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="input-group mb-4">
            <span class="input-group-text bg-transparent">
              <i class="fas fa-envelope text-secondary"></i>
            </span>
            <div class="form-floating">
              <input type="email" class="form-control" id="emailInput" name="email" placeholder="name@example.com" required>
              <label for="emailInput">Email address</label>
            </div>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <div class="form-floating">
              <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
              <label for="passwordInput">Password</label>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check remember-me">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe">
                Remember me
              </label>
            </div>
            <a href="#" class="forgot-password">Forgot Password?</a>
          </div>

          <button type="submit" class="btn btn-login w-100">Sign In</button>
        </form>
      </div>

      <div class="login-footer">
        <p class="mb-0">
          Don't have an account?
          <a href="register.php" class="register-link">Create Account</a>
        </p>
      </div>
    </div>
  </div>

  <?php include 'include/scroll-top.php' ?>
  <?php include 'include/js-links.php' ?>
</body>

</html>
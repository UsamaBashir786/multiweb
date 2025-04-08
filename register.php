<?php
session_start();
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data and sanitize inputs
  $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
  $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Invalid email format";
    header("Location: register.php");
    exit();
  }

  // Check if passwords match
  if ($password !== $confirmPassword) {
    $_SESSION['error'] = "Passwords do not match";
    header("Location: register.php");
    exit();
  }

  // Check if email already exists
  $checkEmail = "SELECT email FROM users WHERE email = ?";
  $stmt = $conn->prepare($checkEmail);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $_SESSION['error'] = "Email already exists";
    header("Location: register.php");
    exit();
  }

  // Password validation
  if (
    strlen($password) < 8 ||
    !preg_match("/[A-Z]/", $password) ||
    !preg_match("/[a-z]/", $password) ||
    !preg_match("/[0-9]/", $password) ||
    !preg_match("/[^A-Za-z0-9]/", $password)
  ) {
    $_SESSION['error'] = "Password does not meet requirements";
    header("Location: register.php");
    exit();
  }

  // Hash password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Prepare and execute SQL query to insert user
  $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

  if ($stmt->execute()) {
    $_SESSION['success'] = "Registration successful! Please login.";
    header("Location: login.php");
    exit();
  } else {
    $_SESSION['error'] = "Error occurred during registration";
    header("Location: register.php");
    exit();
  }

  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Create Account</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .register-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .card {
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
    }

    .card-header {
      background: #f8f9fa;
      border-radius: 15px 15px 0 0;
      padding: 20px;
      text-align: center;
    }

    .card-body {
      padding: 30px;
    }

    .btn-register {
      background-color: #4e73df;
      color: white;
      padding: 12px;
      margin-top: 20px;
      border-radius: 5px;
    }

    .btn-register:hover {
      background-color: #2e59d9;
      color: white;
    }

    .password-strength {
      height: 5px;
      background-color: #e9ecef;
      margin: 10px 0;
      border-radius: 3px;
    }

    .password-requirements {
      font-size: 0.9rem;
      color: #6c757d;
      margin: 15px 0;
    }

    .password-requirements ul {
      list-style: none;
      padding-left: 0;
    }

    .password-requirements li {
      margin: 5px 0;
    }

    .text-success {
      color: #1cc88a !important;
    }
  </style>
</head>

<body class="bg-light">
  <div class="register-container">
    <div class="card">
      <div class="card-header">
        <h2>Create an Account</h2>
        <p>Fill in your information to get started</p>
      </div>
      <div class="card-body">
        <?php if (isset($_SESSION['error'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registerForm">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group mb-3">
                <span class="input-group-text bg-transparent border-end-0">
                  <i class="fas fa-user text-secondary"></i>
                </span>
                <div class="form-floating flex-grow-1">
                  <input type="text" class="form-control border-start-0" id="firstNameInput" name="firstName" placeholder="First Name" required>
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
                  <input type="text" class="form-control border-start-0" id="lastNameInput" name="lastName" placeholder="Last Name" required>
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
              <input type="email" class="form-control border-start-0" id="emailInput" name="email" placeholder="name@example.com" required>
              <label for="emailInput">Email address</label>
            </div>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="password" class="form-control border-start-0" id="passwordInput" name="password" placeholder="Password" required oninput="checkPasswordStrength()">
              <label for="passwordInput">Password</label>
            </div>
          </div>

          <div class="password-strength">
            <div class="progress-bar" role="progressbar" style="width: 0%; background-color: #e74a3b;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>

          <div class="password-requirements">
            <p><i class="fas fa-info-circle me-1"></i> Password must contain:</p>
            <ul>
              <li id="length-check"><i class="fas fa-check-circle me-1 text-muted"></i> At least 8 characters</li>
              <li id="uppercase-check"><i class="fas fa-check-circle me-1 text-muted"></i> At least one uppercase letter</li>
              <li id="lowercase-check"><i class="fas fa-check-circle me-1 text-muted"></i> At least one lowercase letter</li>
              <li id="number-check"><i class="fas fa-check-circle me-1 text-muted"></i> At least one number</li>
              <li id="special-check"><i class="fas fa-check-circle me-1 text-muted"></i> At least one special character</li>
            </ul>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-lock text-secondary"></i>
            </span>
            <div class="form-floating flex-grow-1">
              <input type="password" class="form-control border-start-0" id="confirmPasswordInput" name="confirmPassword" placeholder="Confirm Password" required>
              <label for="confirmPasswordInput">Confirm Password</label>
            </div>
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="termsCheck" required>
            <label class="form-check-label" for="termsCheck">
              I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
            </label>
          </div>

          <button type="submit" class="btn btn-register w-100">Create Account</button>
        </form>

        <div class="login-link text-center mt-3">
          <p>Already have an account? <a href="login.php" class="text-decoration-none">Sign in here</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function checkPasswordStrength() {
      const password = document.getElementById('passwordInput').value;
      const progressBar = document.querySelector('.progress-bar');
      let strength = 0;
      let requirements = {
        length: false,
        uppercase: false,
        lowercase: false,
        number: false,
        special: false
      };

      // Check length
      if (password.length >= 8) {
        strength += 20;
        requirements.length = true;
      }

      // Check uppercase
      if (/[A-Z]/.test(password)) {
        strength += 20;
        requirements.uppercase = true;
      }

      // Check lowercase
      if (/[a-z]/.test(password)) {
        strength += 20;
        requirements.lowercase = true;
      }

      // Check numbers
      if (/[0-9]/.test(password)) {
        strength += 20;
        requirements.number = true;
      }

      // Check special characters
      if (/[^A-Za-z0-9]/.test(password)) {
        strength += 20;
        requirements.special = true;
      }

      // Update progress bar
      progressBar.style.width = strength + '%';
      if (strength < 40) {
        progressBar.style.backgroundColor = '#e74a3b';
      } else if (strength < 80) {
        progressBar.style.backgroundColor = '#f6c23e';
      } else {
        progressBar.style.backgroundColor = '#1cc88a';
      }

      // Update requirement checks
      updateRequirementCheck('length-check', requirements.length);
      updateRequirementCheck('uppercase-check', requirements.uppercase);
      updateRequirementCheck('lowercase-check', requirements.lowercase);
      updateRequirementCheck('number-check', requirements.number);
      updateRequirementCheck('special-check', requirements.special);
    }

    function updateRequirementCheck(elementId, isMet) {
      const element = document.getElementById(elementId);
      const icon = element.querySelector('i');
      if (isMet) {
        icon.classList.remove('text-muted');
        icon.classList.add('text-success');
      } else {
        icon.classList.remove('text-success');
        icon.classList.add('text-muted');
      }
    }

    document.getElementById('registerForm').addEventListener('submit', function(event) {
      const password = document.getElementById('passwordInput').value;
      const confirmPassword = document.getElementById('confirmPasswordInput').value;

      if (password !== confirmPassword) {
        event.preventDefault();
        alert('Passwords do not match!');
      }
    });
  </script>
</body>

</html>
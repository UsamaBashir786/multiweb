function checkPasswordStrength() {
  const password = document.getElementById('passwordInput').value;
  const progressBar = document.querySelector('.password-strength .progress-bar');
  
  let strength = 0;
  
  // Check length
  if (password.length >= 8) {
      strength += 20;
  }
  
  // Check uppercase
  if (/[A-Z]/.test(password)) {
      strength += 20;
  }
  
  // Check lowercase
  if (/[a-z]/.test(password)) {
      strength += 20;
  }
  
  // Check numbers
  if (/[0-9]/.test(password)) {
      strength += 20;
  }
  
  // Check special characters
  if (/[^A-Za-z0-9]/.test(password)) {
      strength += 20;
  }
  
  // Update progress bar
  progressBar.style.width = strength + '%';
  
  // Update color based on strength
  if (strength < 40) {
      progressBar.style.backgroundColor = '#e74a3b'; // Red
  } else if (strength < 80) {
      progressBar.style.backgroundColor = '#f6c23e'; // Yellow
  } else {
      progressBar.style.backgroundColor = '#1cc88a'; // Green
  }
}
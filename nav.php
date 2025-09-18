<!-- nav.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container d-flex justify-content-between align-items-center">
    
    <!-- Brand -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="fa-solid fa-square-h fa-3x"></i>
      <span class="ms-2 h2 mb-0">Hospital</span>
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarButtons"
      aria-controls="navbarButtons" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarButtons">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center mt-3 mt-lg-0">
        
        <?php if(!isset($_SESSION['patient_id'])): ?>
          <!-- Guest: show Sign Up / Sign In -->
          <a href="register.php" class="btn btn-outline-primary me-lg-2 mb-2 mb-lg-0">Sign Up</a>
          <a href="login.php" class="btn btn-primary">Sign In</a>
        <?php else: ?>
          <!-- Logged-in patient: show Dashboard / Logout -->
          <a href="patients.php" class="btn btn-success me-lg-2 mb-2 mb-lg-0">Dashboard</a>
          <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</nav>

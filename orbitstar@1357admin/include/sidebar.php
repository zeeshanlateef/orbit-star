<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<!-- Sidebar / Startbar -->
<div class="startbar d-print-none">

  <!-- Brand / Logo -->
  <div class="brand d-flex align-items-center justify-content-between px-3">
    <a href="home.php" class="d-flex align-items-center text-decoration-none" style="gap:10px;">
      <img src="../images/logo.png" alt="Orbit Star" class="sidebar-logo-full">
      <img src="../images/favicon.webp" alt="OS" class="sidebar-logo-icon">
    </a>
  </div>

  <!-- Menu -->
  <div class="startbar-menu">
    <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
      <div class="d-flex align-items-start flex-column w-100">

        <ul class="navbar-nav mb-auto w-100 px-2 pt-2">

          <!-- Section Label -->
          <li class="nav-item sidebar-section-label">
            <span>MAIN MENU</span>
          </li>

          <!-- Dashboard -->
          <li class="nav-item <?php echo $currentPage === 'home.php' ? 'active' : ''; ?>">
            <a class="nav-link sidebar-link <?php echo $currentPage === 'home.php' ? 'active' : ''; ?>" href="home.php">
              <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
              <span class="sidebar-label">Dashboard</span>
            </a>
          </li>

          <!-- Section Label -->
          <li class="nav-item sidebar-section-label mt-2">
            <span>INQUIRIES</span>
          </li>

          <!-- Contact Inquiries -->
          <li class="nav-item <?php echo $currentPage === 'contact_inquiries.php' ? 'active' : ''; ?>">
            <a class="nav-link sidebar-link <?php echo $currentPage === 'contact_inquiries.php' ? 'active' : ''; ?>" href="contact_inquiries.php">
              <span class="sidebar-icon"><i class="fas fa-envelope-open-text"></i></span>
              <span class="sidebar-label">Contact Inquiries</span>
            </a>
          </li>

          <!-- Free Consultation -->
          <li class="nav-item <?php echo $currentPage === 'service_inquiries.php' ? 'active' : ''; ?>">
            <a class="nav-link sidebar-link <?php echo $currentPage === 'service_inquiries.php' ? 'active' : ''; ?>" href="service_inquiries.php">
              <span class="sidebar-icon"><i class="fas fa-handshake"></i></span>
              <span class="sidebar-label">Free Consultation</span>
            </a>
          </li>

          <!-- Section Label -->
          <li class="nav-item sidebar-section-label mt-2">
            <span>ACCOUNT</span>
          </li>

          <!-- Change Password -->
          <li class="nav-item <?php echo $currentPage === 'change-password.php' ? 'active' : ''; ?>">
            <a class="nav-link sidebar-link <?php echo $currentPage === 'change-password.php' ? 'active' : ''; ?>" href="change-password.php">
              <span class="sidebar-icon"><i class="fas fa-key"></i></span>
              <span class="sidebar-label">Change Password</span>
            </a>
          </li>

          <!-- Logout -->
          <li class="nav-item mt-1">
            <a class="nav-link sidebar-link sidebar-logout" href="logout.php">
              <span class="sidebar-icon"><i class="fas fa-sign-out-alt"></i></span>
              <span class="sidebar-label">Logout</span>
            </a>
          </li>

        </ul>
      </div>
    </div>
  </div>
</div>
<div class="startbar-overlay d-print-none"></div>
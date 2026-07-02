<?php
// Only try to get admin header info if ADMIN_USER_ID is set
$adminUsername = 'Admin';
$adminEmail = '';
if (isset($_SESSION['ADMIN_USER_ID'])) {
    $headerUserRow = $auth->getAdminHeaderInfo($_SESSION['ADMIN_USER_ID']);
    $adminUsername = !empty($headerUserRow['username']) ? $headerUserRow['username'] : 'Admin';
    $adminEmail = !empty($headerUserRow['email']) ? $headerUserRow['email'] : '';
}
?>

<div class="topbar d-print-none">
  <div class="container-fluid">
    <nav class="topbar-custom d-flex justify-content-between align-items-center" id="topbar-custom">

      <!-- Left: Toggle + Title -->
      <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0 gap-2">
        <li>
          <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu" title="Toggle Sidebar">
            <i class="fas fa-bars" style="font-size:18px; color:#d8aa53;"></i>
          </button>
        </li>
        <li class="ms-2 welcome-text d-none d-sm-block">
          <h5 class="mb-0 fw-bold" style="color:#ffffff; letter-spacing:0.3px;">Orbit Star Services</h5>
        </li>
      </ul>

      <!-- Right: Admin Dropdown -->
      <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0 gap-1">
        <li class="dropdown topbar-item">
          <a class="nav-link dropdown-toggle arrow-none d-flex align-items-center gap-2 px-2 py-1"
             data-bs-toggle="dropdown" href="#" style="text-decoration:none; border-radius:8px; transition:background 0.2s;">
            <div class="admin-avatar-circle">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="d-none d-md-block">
              <span style="color:#ffffff; font-size:13px; font-weight:600;"><?php echo htmlspecialchars($adminUsername); ?></span>
            </div>
            <i class="fas fa-chevron-down" style="color:#d8aa53; font-size:10px;"></i>
          </a>

          <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width:210px; border-radius:12px; overflow:hidden;">
            <!-- Admin Info -->
            <div class="px-3 py-3" style="background: linear-gradient(135deg, #000056, #1a1a7a);">
              <div class="d-flex align-items-center gap-2">
                <div class="admin-avatar-circle-lg">
                  <i class="fas fa-user-tie"></i>
                </div>
                <div>
                  <div style="color:#fff; font-weight:700; font-size:14px;"><?php echo htmlspecialchars($adminUsername); ?></div>
                  <div style="color:#d8aa53; font-size:11px;"><?php echo htmlspecialchars($adminEmail); ?></div>
                </div>
              </div>
            </div>
            <div class="dropdown-divider m-0"></div>

            <a class="dropdown-item py-2" href="change-password.php">
              <i class="fas fa-key me-2 text-warning"></i> Change Password
            </a>
            <div class="dropdown-divider m-0"></div>
            <a class="dropdown-item py-2 text-danger" href="logout.php">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>

    </nav>
  </div>
</div>

<script>
  (function() {
    try {
      const savedSidebarState = localStorage.getItem('sidebarState');
      if (savedSidebarState === 'collapsed') {
        document.body.setAttribute('data-sidebar-size', 'collapsed');
      }
    } catch (e) {}
  })();
</script>
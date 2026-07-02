<?php

if (!isset($_SESSION)) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/config.php';
require 'functions/authentication.php';

$db   = new dbClass();
$auth = new AdminManager();
$auth->checkSession();

// Handle form submission
if (isset($_POST['change'])) {

    $current_pass = trim($_POST['current_pass'] ?? '');
    $new_pass     = trim($_POST['new_pass'] ?? '');
    $confirm_pass = trim($_POST['confirm_pass'] ?? '');

    // Basic server-side validation
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $_SESSION['errmsg'] = "All password fields are required.";

    } elseif ($new_pass !== $confirm_pass) {
        $_SESSION['errmsg'] = "New password and confirm password do not match.";

    } elseif (strlen($new_pass) < 6) {
        $_SESSION['errmsg'] = "New password must be at least 6 characters.";

    } else {
        $result = $auth->changePassword($_SESSION['ADMIN_USER_ID'], $current_pass, $new_pass);

        if ($result['success']) {
            $_SESSION['msg'] = $result['message'];
        } else {
            $_SESSION['errmsg'] = $result['message'];
        }
    }

    header('Location: change-password.php');
    exit();
}

// Get admin header info
$headerUserRow = $auth->getAdminHeaderInfo($_SESSION['ADMIN_USER_ID']);
$adminUsername = !empty($headerUserRow['username']) ? $headerUserRow['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Orbit Star Services | Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="icon" href="../images/favicon.webp" type="image/webp">

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/icons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- Orbit Star Theme Override -->
    <link href="css/orbitstar-theme.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php include 'include/header.php'; ?>
<?php include 'include/sidebar.php'; ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-key me-2" style="color:#d8aa53;"></i> Change Password
                            </h4>
                        </div>
                        <div class="card-body p-4">

                            <form id="pass-form" method="post" novalidate>

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="current_pass">
                                        Current Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#000056; color:#d8aa53; border-color:#000056;">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" name="current_pass" id="current_pass"
                                            class="form-control"
                                            placeholder="Enter current password"
                                            autocomplete="current-password" required>
                                        <span class="input-group-text toggle-pass" data-target="current_pass" style="cursor:pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="current_pass_err"></div>
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold" for="new_pass">
                                        New Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#000056; color:#d8aa53; border-color:#000056;">
                                            <i class="fas fa-key"></i>
                                        </span>
                                        <input type="password" name="new_pass" id="new_pass"
                                            class="form-control"
                                            placeholder="Enter new password (min 6 chars)"
                                            autocomplete="new-password" required minlength="6">
                                        <span class="input-group-text toggle-pass" data-target="new_pass" style="cursor:pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="new_pass_err"></div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" for="confirm_pass">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background:#000056; color:#d8aa53; border-color:#000056;">
                                            <i class="fas fa-shield-alt"></i>
                                        </span>
                                        <input type="password" name="confirm_pass" id="confirm_pass"
                                            class="form-control"
                                            placeholder="Re-enter new password"
                                            autocomplete="new-password" required>
                                        <span class="input-group-text toggle-pass" data-target="confirm_pass" style="cursor:pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="confirm_pass_err"></div>
                                </div>

                                <!-- Submit -->
                                <div class="d-flex gap-2">
                                    <button type="submit" name="change" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i> Update Password
                                    </button>
                                    <a href="home.php" class="btn btn-outline-secondary px-4">Cancel</a>
                                </div>

                            </form>

                        </div><!-- /card-body -->
                    </div><!-- /card -->

                </div>
            </div>

        </div><!-- /container -->

        <?php include 'include/footer.php'; ?>
    </div><!-- /page-content -->
</div><!-- /page-wrapper -->

<!-- JavaScript -->
<script src="js/jquery-3.6.0.js"></script>
<script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="libs/simplebar/simplebar.min.js"></script>
<script src="libs/toastr/js/toastr.min.js"></script>
<script src="js/toastr-init.js"></script>
<script src="js/app.js"></script>

<!-- Toastr notifications -->
<script>
    <?php if (isset($_SESSION['msg'])): ?>
        toastr.success("<?php echo addslashes($_SESSION['msg']); ?>");
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['errmsg'])): ?>
        toastr.error("<?php echo addslashes($_SESSION['errmsg']); ?>");
        <?php unset($_SESSION['errmsg']); ?>
    <?php endif; ?>
</script>

<!-- Form Validation + Password Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Password visibility toggles ──────────────────────
    document.querySelectorAll('.toggle-pass').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var input    = document.getElementById(targetId);
            var icon     = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    });

    // ── Client-side validation before submit ─────────────
    document.getElementById('pass-form').addEventListener('submit', function (e) {
        var current  = document.getElementById('current_pass');
        var newPass  = document.getElementById('new_pass');
        var confirm  = document.getElementById('confirm_pass');
        var valid    = true;

        // Reset
        [current, newPass, confirm].forEach(function (f) {
            f.classList.remove('is-invalid', 'is-valid');
        });

        if (!current.value.trim()) {
            current.classList.add('is-invalid');
            document.getElementById('current_pass_err').textContent = 'Please enter your current password.';
            valid = false;
        } else {
            current.classList.add('is-valid');
        }

        if (!newPass.value.trim()) {
            newPass.classList.add('is-invalid');
            document.getElementById('new_pass_err').textContent = 'Please enter a new password.';
            valid = false;
        } else if (newPass.value.trim().length < 6) {
            newPass.classList.add('is-invalid');
            document.getElementById('new_pass_err').textContent = 'New password must be at least 6 characters.';
            valid = false;
        } else {
            newPass.classList.add('is-valid');
        }

        if (!confirm.value.trim()) {
            confirm.classList.add('is-invalid');
            document.getElementById('confirm_pass_err').textContent = 'Please confirm your new password.';
            valid = false;
        } else if (confirm.value.trim() !== newPass.value.trim()) {
            confirm.classList.add('is-invalid');
            document.getElementById('confirm_pass_err').textContent = 'Passwords do not match.';
            valid = false;
        } else {
            confirm.classList.add('is-valid');
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
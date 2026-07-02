<?php

if(!isset($_SESSION)){session_start();}
error_reporting(E_ALL);

require '../config/config.php';
require 'functions/authentication.php';

$db = new dbClass();
$auth = new AdminManager();
$auth->checkSession();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

    <head>      
        <meta charset="utf-8" />
        <title>Orbit Star Services | Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">        
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="icon" href="../images/favicon.webp" type="image/webp">
         <!-- App css -->
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

                <!-- Welcome Banner -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0" style="background: linear-gradient(135deg, #000056 0%, #1a1a7a 100%); border-radius:16px;">
                            <div class="card-body py-4 px-4 d-flex align-items-center justify-content-between flex-wrap" style="gap:16px;">
                                <div>
                                    <h4 class="mb-1 fw-bold" style="color:#d8aa53; font-size:22px;">Welcome back, <?php echo htmlspecialchars($adminUsername ?? 'Admin'); ?>!</h4>
                                    <p class="mb-0" style="color:#d8aa53; font-size:14px;">Here's an overview of your inquiries today.</p>
                                </div>
                                <div style="font-size:48px; color:rgba(216,170,83,0.3);">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">

                    <div class="col-12 col-sm-6">
                        <div class="card h-100" style="border-left: 4px solid #000056 !important; border-radius:14px;">
                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                <div style="width:52px;height:52px;background:rgba(0,0,86,0.08);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-envelope-open-text" style="color:#000056;font-size:22px;"></i>
                                </div>
                                <div>
                                    <div style="font-size:28px;font-weight:800;color:#000056;line-height:1;">
                                        <?php
                                            try {
                                                $c = $db->getData("SELECT COUNT(*) as cnt FROM contact_us");
                                                echo $c['cnt'] ?? 0;
                                            } catch(Exception $e){ echo 0; }
                                        ?>
                                    </div>
                                    <div style="font-size:13px;color:#6c757d;font-weight:500;">Contact Inquiries</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="card h-100" style="border-left: 4px solid #d8aa53 !important; border-radius:14px;">
                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                <div style="width:52px;height:52px;background:rgba(216,170,83,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-handshake" style="color:#d8aa53;font-size:22px;"></i>
                                </div>
                                <div>
                                    <div style="font-size:28px;font-weight:800;color:#d8aa53;line-height:1;">
                                        <?php
                                            try {
                                                $q = $db->getData("SELECT COUNT(*) as cnt FROM quote");
                                                echo $q['cnt'] ?? 0;
                                            } catch(Exception $e){ echo 0; }
                                        ?>
                                    </div>
                                    <div style="font-size:13px;color:#6c757d;font-weight:500;">Free Consultations</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Quick Links -->
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <a href="contact_inquiries.php" class="text-decoration-none">
                            <div class="card" style="border-radius:14px; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,86,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <i class="fas fa-envelope-open-text" style="color:#000056;font-size:20px;"></i>
                                    <div>
                                        <div style="font-weight:700;color:#000056;font-size:14px;">View Contact Inquiries</div>
                                        <div style="font-size:12px;color:#9097b1;">See all messages from the contact form</div>
                                    </div>
                                    <i class="fas fa-chevron-right ms-auto" style="color:#d8aa53;"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-6">
                        <a href="service_inquiries.php" class="text-decoration-none">
                            <div class="card" style="border-radius:14px; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,86,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <i class="fas fa-handshake" style="color:#d8aa53;font-size:20px;"></i>
                                    <div>
                                        <div style="font-weight:700;color:#000056;font-size:14px;">View Free Consultations</div>
                                        <div style="font-size:12px;color:#9097b1;">See all free consultation requests</div>
                                    </div>
                                    <i class="fas fa-chevron-right ms-auto" style="color:#d8aa53;"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div><!-- /container -->

            <?php include 'include/footer.php'; ?>
        </div><!-- /page-content -->
    </div><!-- /page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->
    

       
        <script src="js/jquery-3.6.0.js"></script>
        <script src="libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="libs/simplebar/simplebar.min.js"></script>
        <script src="js/pages/form-validation.js"></script>
        <script src="libs/toastr/js/toastr.min.js"></script>
        <script src="js/toastr-init.js"></script>
        <script src="js/app.js"></script>
</body>
<!--end body-->

</html>
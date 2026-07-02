<?php
// Helper to check if current page matches nav item
function isActivePage($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}
?>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta content="Orbit Star Services Admin" name="author" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<!-- Favicon -->
<link rel="icon" href="../images/favicon.webp" type="image/webp">

<!-- CSS -->
<link href="libs/simple-datatables/style.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/icons.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
<link href="libs/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
<link href="css/app.min.css" rel="stylesheet" type="text/css" />
<!-- Orbit Star Theme Override (always load last) -->
<link href="css/orbitstar-theme.css" rel="stylesheet" type="text/css" />

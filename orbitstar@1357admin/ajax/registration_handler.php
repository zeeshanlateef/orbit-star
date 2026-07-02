<?php
error_reporting(0);
header('Content-Type: application/json');

if (!isset($_SESSION)) {
    session_start();
}

require '../../config/config.php';
require '../functions/registration.php';

$registrationObj = new Registration();

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid request'];


if ($action === 'delete_registration') {

    $ids = $_POST['ids'] ?? [];

    $result = $registrationObj->deleteRegistration($ids);

    echo json_encode($result);
    exit;
}

echo json_encode($response);
exit;

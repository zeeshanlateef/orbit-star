<?php
session_start();
error_reporting(0);
include 'include/connection.php';

if (!isset($_SESSION['ADMIN_USERID']) || $_SESSION['ADMIN_USERID'] == '') {
    header('location:index.php');
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'contact';
$txtStartDate = isset($_GET['txtStartDate']) ? $_GET['txtStartDate'] : '';
$txtEndDate   = isset($_GET['txtEndDate']) ? $_GET['txtEndDate'] : '';

$searchStartDate = '';
$searchEndDate = '';

if (!empty($txtStartDate)) {
    $searchStartDate = "AND CAST(date AS DATE) >= '" . $txtStartDate . "'";
}
if (!empty($txtEndDate)) {
    $searchEndDate = "AND CAST(date AS DATE) <= '" . $txtEndDate . "'";
}

if ($type === 'quote') {
    $tbl_name = "quote";
    $filename = "OrbitStarServices_QuoteExport_" . date('Ymd');
    
    $sql = $conn->prepare("SELECT * FROM `$tbl_name` WHERE id!='0' $searchStartDate $searchEndDate ORDER BY `id` DESC");
    $sql->execute();
    $count = $sql->rowCount();
    
    $output = '';
    if ($count > 0) {
        $output .= '<table class="table" style="border-style: double;">  
                    <tr> 
                    <th>S.No</th> 
                    <th>Name</th>  
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Zip Code</th>
                    <th>Services</th>
                    <th>Message</th>
                    <th>Date</th>
                    </tr>';
        $k = 1;            
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '<tr>
            <td>' . $k++ . '</td>   
            <td>' . $row["name"] . '</td>  
            <td>' . $row["email"] . '</td>
            <td>' . $row["phone"] . '</td>
            <td>' . $row["code"] . '</td>  
            <td>' . $row["services"] . '</td>  
            <td>' . $row["message"] . '</td>
            <td>' . date('d-m-Y', strtotime($row['date'])) . '</td>
            </tr>';
        }
        $output .= '</table>';
    }
} else {
    $tbl_name = "contact_us";
    $filename = "OrbitStarServices_ContactExport_" . date('Ymd');
    
    $sql = $conn->prepare("SELECT * FROM `$tbl_name` WHERE id!='0' $searchStartDate $searchEndDate ORDER BY `id` DESC");
    $sql->execute();
    $count = $sql->rowCount();
    
    $output = '';
    if ($count > 0) {
        $output .= '<table class="table" style="border-style: double;">  
                    <tr> 
                    <th>S.No</th> 
                    <th>Name</th>  
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    </tr>';
        $k = 1;            
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '<tr>
            <td>' . $k++ . '</td>   
            <td>' . $row["name"] . '</td>  
            <td>' . $row["email"] . '</td>
            <td>' . $row["phone"] . '</td>
            <td>' . $row["subject"] . '</td>  
            <td>' . $row["message"] . '</td>
            <td>' . date('d-m-Y', strtotime($row['date'])) . '</td>
            </tr>';
        }
        $output .= '</table>';
    }
}

if (!empty($output)) {
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $output;
}
?>
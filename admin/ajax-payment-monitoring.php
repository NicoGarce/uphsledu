<?php
/**
 * AJAX endpoint for Payment Monitoring
 * Returns filtered payment data as JSON
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and is super admin only
if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Connect to payment database
require_once '../online_payment/dbconnect.php';

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$dateFrom = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$dateTo = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50;

// Build query with proper escaping
$whereConditions = [];

if (!empty($search)) {
    $searchEscaped = mysqli_real_escape_string($con, $search);
    $whereConditions[] = "(txnid LIKE '%{$searchEscaped}%' OR refno LIKE '%{$searchEscaped}%' OR message LIKE '%{$searchEscaped}%')";
}

if (!empty($statusFilter)) {
    $statusEscaped = mysqli_real_escape_string($con, $statusFilter);
    $whereConditions[] = "status = '{$statusEscaped}'";
}

if (!empty($dateFrom)) {
    $dateFromEscaped = mysqli_real_escape_string($con, $dateFrom);
    $whereConditions[] = "DATE(transdate) >= '{$dateFromEscaped}'";
}

if (!empty($dateTo)) {
    $dateToEscaped = mysqli_real_escape_string($con, $dateTo);
    $whereConditions[] = "DATE(transdate) <= '{$dateToEscaped}'";
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM return_data $whereClause";
$countResult = mysqli_query($con, $countQuery);
$totalRecords = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRecords / $perPage);
$offset = ($page - 1) * $perPage;

// Get data
$query = "SELECT txnid, refno, status, message, transdate, amount 
          FROM return_data 
          $whereClause 
          ORDER BY transdate DESC 
          LIMIT $perPage OFFSET $offset";
$result = mysqli_query($con, $query);

$payments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $payments[] = $row;
}

// Format response
$response = [
    'payments' => $payments,
    'pagination' => [
        'current_page' => $page,
        'total_pages' => $totalPages,
        'total_records' => $totalRecords,
        'per_page' => $perPage
    ]
];

header('Content-Type: application/json');
echo json_encode($response);
exit;


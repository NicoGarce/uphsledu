<?php
/**
 * UPHSL Online Payment Monitoring
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Payment monitoring system for super admin to view and manage payment transactions
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and is super admin only
if (!isLoggedIn() || !isSuperAdmin()) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Online Payment Monitoring';

// Connect to payment database using dbconnect from online_payment folder
require_once '../online_payment/dbconnect.php';

$error = '';
$success = '';

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
$dateFrom = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$dateTo = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';

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

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50;
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

// Handle export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Export all filtered data to CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="payment_monitoring_' . date('Y-m-d_His') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add BOM for UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Headers
    fputcsv($output, ['Transaction ID', 'Reference No.', 'Status', 'Description', 'Transaction Date', 'Amount']);
    
    // Get all data for export (no pagination)
    $exportQuery = "SELECT txnid, refno, status, message, transdate, amount 
                    FROM return_data 
                    $whereClause 
                    ORDER BY transdate DESC";
    $exportResult = mysqli_query($con, $exportQuery);
    
    while ($row = mysqli_fetch_assoc($exportResult)) {
        fputcsv($output, [
            $row['txnid'],
            $row['refno'],
            $row['status'],
            $row['message'],
            $row['transdate'],
            number_format($row['amount'], 2, '.', ',')
        ]);
    }
    
    fclose($output);
    exit;
}

// Get unique statuses for filter
$statusQuery = "SELECT DISTINCT status FROM return_data WHERE status IS NOT NULL AND status != '' ORDER BY status";
$statusResult = mysqli_query($con, $statusQuery);
$statuses = [];
while ($row = mysqli_fetch_assoc($statusResult)) {
    $statuses[] = $row['status'];
}
?>
<?php include '../app/includes/admin-header.php'; ?>

<style>
    .monitoring-container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .filters-container {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }
    
    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
    }
    
    .filter-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
        font-size: 0.9rem;
    }
    
    .filter-input, .filter-select {
        padding: 0.75rem;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
    }
    
    .filter-actions {
        display: flex;
        gap: 0.75rem;
        align-items: end;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(28, 77, 161, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-success:hover {
        background: #218838;
    }
    
    .data-table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .table-header {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th {
        background: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-dark);
        border-bottom: 2px solid #e1e5e9;
        font-size: 0.9rem;
    }
    
    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.9rem;
        color: var(--text-light);
    }
    
    .data-table tr:hover {
        background: #f8f9fa;
    }
    
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 1.5rem;
        background: white;
        border-top: 1px solid #e9ecef;
    }
    
    .pagination a, .pagination span {
        padding: 0.5rem 1rem;
        border: 1px solid #e1e5e9;
        border-radius: 6px;
        text-decoration: none;
        color: var(--text-dark);
        transition: all 0.3s ease;
    }
    
    .pagination a:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .pagination .current {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .no-data {
        padding: 3rem;
        text-align: center;
        color: var(--text-light);
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
        margin-top: 2rem;
    }
    
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .stat-card h4 {
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: 500;
    }
    
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .stat-card .btn {
        white-space: nowrap;
    }
    
    @media (max-width: 768px) {
        .monitoring-container {
            padding: 1rem;
        }
        
        .filters-row {
            grid-template-columns: 1fr;
        }
        
        .data-table {
            font-size: 0.8rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 0.75rem 0.5rem;
        }
        
        .stat-card {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .stat-card .btn {
            margin-top: 1rem;
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="monitoring-container">
    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <h4>Total Records</h4>
            <div class="stat-value"><?php echo number_format($totalRecords); ?></div>
        </div>
        <div class="stat-card" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4>Current Page</h4>
                <div class="stat-value"><?php echo $page; ?> / <?php echo $totalPages; ?></div>
            </div>
            <div>
                <a href="?<?php echo http_build_query(array_merge($_GET, ['export' => 'csv'])); ?>" class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="filters-container">
        <form method="GET" action="">
            <div class="filters-row">
                <div class="filter-group">
                    <label class="filter-label">Search</label>
                    <input type="text" name="search" class="filter-input" placeholder="Transaction ID, Reference No., or Description" value="<?php echo htmlspecialchars($search); ?>">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-select">
                        <option value="">All Statuses</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>" <?php echo $statusFilter === $status ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Date From</label>
                    <input type="date" name="date_from" class="filter-input" value="<?php echo htmlspecialchars($dateFrom); ?>">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Date To</label>
                    <input type="date" name="date_to" class="filter-input" value="<?php echo htmlspecialchars($dateTo); ?>">
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <a href="payment-monitoring.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
    
    <!-- Data Table -->
    <div class="data-table-container">
        <div class="table-header">
            <h3>Payment Transactions</h3>
        </div>
        
        <?php if (empty($payments)): ?>
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc; margin-bottom: 1rem;"></i>
                <p>No payment records found.</p>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Reference No.</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Transaction Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['txnid']); ?></td>
                            <td><?php echo htmlspecialchars($payment['refno']); ?></td>
                            <td>
                                <?php
                                $status = strtolower($payment['status']);
                                $badgeClass = 'status-pending';
                                if (strpos($status, 'success') !== false || strpos($status, 'approved') !== false || strpos($status, 'paid') !== false) {
                                    $badgeClass = 'status-success';
                                } elseif (strpos($status, 'fail') !== false || strpos($status, 'error') !== false || strpos($status, 'reject') !== false) {
                                    $badgeClass = 'status-failed';
                                }
                                ?>
                                <span class="status-badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($payment['status']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($payment['message']); ?></td>
                            <td><?php echo date('M d, Y H:i:s', strtotime($payment['transdate'])); ?></td>
                            <td><strong>₱<?php echo number_format($payment['amount'], 2, '.', ','); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                            <i class="fas fa-chevron-left"></i> Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <?php if ($i == $page): ?>
                            <span class="current"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                            Next <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include '../app/includes/admin-footer.php'; ?>


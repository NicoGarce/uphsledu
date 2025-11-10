<?php
/**
 * UPHSL Admin Database Export
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for exporting database backups
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
$page_title = 'Database Export';

// Get database connection
$pdo = getDBConnection();

$error = '';
$success = '';

// Handle export requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['export'])) {
    $exportType = $_GET['export'];
    $tableName = $_GET['table'] ?? '';
    
    if ($exportType === 'full_sql') {
        // Export full database as SQL
        $filename = 'uphsledu_backup_' . date('Y-m-d_His') . '.sql';
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Get all tables
        $tables = [];
        $stmt = $pdo->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        $output = "-- UPHSL Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Database: " . DB_NAME . "\n\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET time_zone = \"+00:00\";\n\n";
        
        foreach ($tables as $table) {
            // Get table structure
            $output .= "\n-- --------------------------------------------------------\n";
            $output .= "-- Table structure for table `$table`\n";
            $output .= "-- --------------------------------------------------------\n\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            
            $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
            $output .= $createTable['Create Table'] . ";\n\n";
            
            // Get table data
            $output .= "-- Dumping data for table `$table`\n\n";
            
            $stmt = $pdo->query("SELECT * FROM `$table`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($rows) > 0) {
                $columns = array_keys($rows[0]);
                $columnList = '`' . implode('`, `', $columns) . '`';
                
                foreach ($rows as $row) {
                    $values = [];
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $value = $pdo->quote($value);
                            $values[] = $value;
                        }
                    }
                    $output .= "INSERT INTO `$table` ($columnList) VALUES (" . implode(', ', $values) . ");\n";
                }
            }
            $output .= "\n";
        }
        
        echo $output;
        exit;
        
    } elseif ($exportType === 'table_sql' && $tableName) {
        // Export single table as SQL
        $filename = 'uphsledu_' . $tableName . '_' . date('Y-m-d_His') . '.sql';
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = "-- UPHSL Database Table Export\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Table: $tableName\n\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET time_zone = \"+00:00\";\n\n";
        
        // Get table structure
        $output .= "DROP TABLE IF EXISTS `$tableName`;\n";
        $stmt = $pdo->query("SHOW CREATE TABLE `$tableName`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $output .= $createTable['Create Table'] . ";\n\n";
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$tableName`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($rows) > 0) {
            $columns = array_keys($rows[0]);
            $columnList = '`' . implode('`, `', $columns) . '`';
            
            foreach ($rows as $row) {
                $values = [];
                foreach ($row as $value) {
                    if ($value === null) {
                        $values[] = 'NULL';
                    } else {
                        $value = $pdo->quote($value);
                        $values[] = $value;
                    }
                }
                $output .= "INSERT INTO `$tableName` ($columnList) VALUES (" . implode(', ', $values) . ");\n";
            }
        }
        
        echo $output;
        exit;
        
    } elseif ($exportType === 'table_csv' && $tableName) {
        // Export single table as CSV
        $filename = 'uphsledu_' . $tableName . '_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Get table data
        $stmt = $pdo->query("SELECT * FROM `$tableName`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($rows) > 0) {
            // Write headers
            $columns = array_keys($rows[0]);
            fputcsv($output, $columns);
            
            // Write data
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
}

// Get database information
$dbInfo = [];
$dbInfo['name'] = DB_NAME;
$dbInfo['host'] = DB_HOST;

// Get all tables with row counts
$tables = [];
$stmt = $pdo->query("SHOW TABLES");
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    $tableName = $row[0];
    $countStmt = $pdo->query("SELECT COUNT(*) as count FROM `$tableName`");
    $count = $countStmt->fetch()['count'];
    
    // Get table size
    $sizeStmt = $pdo->query("
        SELECT 
            ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
        FROM information_schema.TABLES 
        WHERE table_schema = '" . DB_NAME . "' 
        AND table_name = '$tableName'
    ");
    $size = $sizeStmt->fetch();
    $tableSize = $size ? $size['size_mb'] : 0;
    
    $tables[] = [
        'name' => $tableName,
        'rows' => $count,
        'size' => $tableSize
    ];
}

// Get total database size
$dbSizeStmt = $pdo->query("
    SELECT 
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
    FROM information_schema.TABLES 
    WHERE table_schema = '" . DB_NAME . "'
");
$dbSize = $dbSizeStmt->fetch()['size_mb'] ?? 0;
?>

<?php include '../app/includes/admin-header.php'; ?>

    <!-- Database Export Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-database"></i>
                Database Export
            </h1>
            <p class="dashboard-subtitle">
                Export and backup your database in various formats
            </p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Database Information -->
        <div class="dashboard-section">
            <h2 class="section-title">Database Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Database Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($dbInfo['name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Host</div>
                    <div class="info-value"><?php echo htmlspecialchars($dbInfo['host']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Tables</div>
                    <div class="info-value"><?php echo count($tables); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Size</div>
                    <div class="info-value"><?php echo number_format($dbSize, 2); ?> MB</div>
                </div>
            </div>
        </div>

        <!-- Full Database Export -->
        <div class="dashboard-section">
            <h2 class="section-title">Full Database Export</h2>
            <p class="section-description">
                Export the entire database as a SQL file. This includes all tables, structure, and data.
            </p>
            <div class="export-actions">
                <a href="?export=full_sql" class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    Export Full Database (SQL)
                </a>
            </div>
        </div>

        <!-- Individual Table Exports -->
        <div class="dashboard-section">
            <h2 class="section-title">Individual Table Exports</h2>
            <p class="section-description">
                Export individual tables in SQL or CSV format.
            </p>
            
            <div class="table-list">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Table Name</th>
                            <th>Rows</th>
                            <th>Size (MB)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tables as $table): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($table['name']); ?></strong>
                                </td>
                                <td><?php echo number_format($table['rows']); ?></td>
                                <td><?php echo number_format($table['size'], 2); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <a href="?export=table_sql&table=<?php echo urlencode($table['name']); ?>" 
                                           class="btn btn-sm btn-secondary" 
                                           title="Export as SQL">
                                            <i class="fas fa-file-code"></i> SQL
                                        </a>
                                        <a href="?export=table_csv&table=<?php echo urlencode($table['name']); ?>" 
                                           class="btn btn-sm btn-secondary" 
                                           title="Export as CSV">
                                            <i class="fas fa-file-csv"></i> CSV
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Information -->
        <div class="dashboard-section">
            <h2 class="section-title">Export Information</h2>
            <div class="info-box">
                <h3><i class="fas fa-info-circle"></i> Export Formats</h3>
                <ul>
                    <li><strong>SQL Format:</strong> Complete database structure and data. Can be imported using phpMyAdmin or MySQL command line.</li>
                    <li><strong>CSV Format:</strong> Table data only. Useful for importing into spreadsheet applications or other databases.</li>
                </ul>
                
                <h3 style="margin-top: 20px;"><i class="fas fa-shield-alt"></i> Security Notes</h3>
                <ul>
                    <li>Database exports contain sensitive information. Store backups securely.</li>
                    <li>Only Super Administrators can access this page.</li>
                    <li>Exports are generated on-demand and not stored on the server.</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 700;
            color: #212529;
        }

        .export-actions {
            margin-top: 20px;
        }

        .section-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .table-list {
            margin-top: 20px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .data-table thead {
            background: #f8f9fa;
        }

        .data-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .data-table tbody tr:hover {
            background: #f8f9fa;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .info-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .info-box h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #212529;
            font-size: 18px;
        }

        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }

        .info-box li {
            margin-bottom: 10px;
            line-height: 1.6;
            color: #495057;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .data-table {
                font-size: 14px;
            }

            .data-table th,
            .data-table td {
                padding: 10px;
            }

            .table-actions {
                flex-direction: column;
            }
        }
    </style>

<?php include '../app/includes/admin-footer.php'; ?>


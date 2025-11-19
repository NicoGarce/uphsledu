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

/**
 * Get connection to online payment database
 * Uses credentials from app/config/database.php
 */
function getOnlinePaymentDBConnection() {
    // Check if constants are defined
    if (!defined('ONLINE_PAYMENT_DB_HOST') || !defined('ONLINE_PAYMENT_DB_NAME') || 
        !defined('ONLINE_PAYMENT_DB_USER') || !defined('ONLINE_PAYMENT_DB_PASS')) {
        error_log("Online Payment DB Configuration Error: Constants not defined in app/config/database.php");
        return null;
    }
    
    try {
        $dsn = "mysql:host=" . ONLINE_PAYMENT_DB_HOST . ";dbname=" . ONLINE_PAYMENT_DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, ONLINE_PAYMENT_DB_USER, ONLINE_PAYMENT_DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Online Payment DB Connection Error: " . $e->getMessage());
        return null;
    }
}

$error = '';
$success = '';

// Handle export requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['export'])) {
    $exportType = $_GET['export'];
    $tableName = $_GET['table'] ?? '';
    $dbSource = $_GET['db'] ?? 'main'; // 'main' or 'online_payment'
    
    // Determine which database to use
    $pdo_export = ($dbSource === 'online_payment') ? getOnlinePaymentDBConnection() : $pdo;
    $dbName = ($dbSource === 'online_payment') ? ONLINE_PAYMENT_DB_NAME : DB_NAME;
    
    if (!$pdo_export) {
        header('Content-Type: text/html');
        die('Error: Could not connect to database. Please check database credentials.');
    }
    
    if ($exportType === 'full_sql') {
        // Export full database as SQL
        $prefix = ($dbSource === 'online_payment') ? 'online_payment_backup' : 'uphsledu_backup';
        $filename = $prefix . '_' . date('Y-m-d_His') . '.sql';
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = "-- UPHSL Database Backup\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Database: " . $dbName . "\n\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET time_zone = \"+00:00\";\n\n";
        
        // Get all tables
        $tables = [];
        $stmt = $pdo_export->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $tables[] = $row[0];
        }
        
        foreach ($tables as $table) {
            // Get table structure
            $output .= "\n-- --------------------------------------------------------\n";
            $output .= "-- Table structure for table `$table`\n";
            $output .= "-- --------------------------------------------------------\n\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            
            $stmt = $pdo_export->query("SHOW CREATE TABLE `$table`");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
            $output .= $createTable['Create Table'] . ";\n\n";
            
            // Get table data
            $output .= "-- Dumping data for table `$table`\n\n";
            
            $stmt = $pdo_export->query("SELECT * FROM `$table`");
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
                            $value = $pdo_export->quote($value);
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
        $prefix = ($dbSource === 'online_payment') ? 'online_payment' : 'uphsledu';
        $filename = $prefix . '_' . $tableName . '_' . date('Y-m-d_His') . '.sql';
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = "-- UPHSL Database Table Export\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Database: " . $dbName . "\n";
        $output .= "-- Table: $tableName\n\n";
        $output .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $output .= "SET time_zone = \"+00:00\";\n\n";
        
        // Get table structure
        $output .= "DROP TABLE IF EXISTS `$tableName`;\n";
        $stmt = $pdo_export->query("SHOW CREATE TABLE `$tableName`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $output .= $createTable['Create Table'] . ";\n\n";
        
        // Get table data
        $stmt = $pdo_export->query("SELECT * FROM `$tableName`");
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
                        $value = $pdo_export->quote($value);
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
        $prefix = ($dbSource === 'online_payment') ? 'online_payment' : 'uphsledu';
        $filename = $prefix . '_' . $tableName . '_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Get table data
        $stmt = $pdo_export->query("SELECT * FROM `$tableName`");
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

// Get main database information
$dbInfo = [];
$dbInfo['name'] = DB_NAME;
$dbInfo['host'] = DB_HOST;

// Get all tables with row counts for main database
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

// Get total database size for main database
$dbSizeStmt = $pdo->query("
    SELECT 
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
    FROM information_schema.TABLES 
    WHERE table_schema = '" . DB_NAME . "'
");
$dbSize = $dbSizeStmt->fetch()['size_mb'] ?? 0;

// Get online payment database information
$onlinePaymentDbInfo = [];
$onlinePaymentDbInfo['name'] = ONLINE_PAYMENT_DB_NAME;
$onlinePaymentDbInfo['host'] = ONLINE_PAYMENT_DB_HOST;
$onlinePaymentTables = [];
$onlinePaymentDbSize = 0;

$pdo_online_payment = getOnlinePaymentDBConnection();
if ($pdo_online_payment) {
    // Get all tables with row counts for online payment database
    $stmt = $pdo_online_payment->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $tableName = $row[0];
        $countStmt = $pdo_online_payment->query("SELECT COUNT(*) as count FROM `$tableName`");
        $count = $countStmt->fetch()['count'];
        
        // Get table size
        $sizeStmt = $pdo_online_payment->query("
            SELECT 
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
            FROM information_schema.TABLES 
            WHERE table_schema = '" . ONLINE_PAYMENT_DB_NAME . "' 
            AND table_name = '$tableName'
        ");
        $size = $sizeStmt->fetch();
        $tableSize = $size ? $size['size_mb'] : 0;
        
        $onlinePaymentTables[] = [
            'name' => $tableName,
            'rows' => $count,
            'size' => $tableSize
        ];
    }
    
    // Get total database size for online payment database
    $dbSizeStmt = $pdo_online_payment->query("
        SELECT 
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
        FROM information_schema.TABLES 
        WHERE table_schema = '" . ONLINE_PAYMENT_DB_NAME . "'
    ");
    $onlinePaymentDbSize = $dbSizeStmt->fetch()['size_mb'] ?? 0;
}
?>

<?php include '../app/includes/admin-header.php'; ?>

    <!-- Database Export Content -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-database"></i>
                Database Export
            </h1>
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
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Main Database Information
            </h2>
            <div class="info-grid" style="margin-bottom: 30px;">
                <div class="info-card">
                    <div class="info-card-icon" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="info-card-content">
                        <div class="info-label">Database Name</div>
                        <div class="info-value"><?php echo htmlspecialchars($dbInfo['name']); ?></div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon" style="background: linear-gradient(135deg, var(--tertiary-color), var(--primary-color));">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="info-card-content">
                        <div class="info-label">Host</div>
                        <div class="info-value"><?php echo htmlspecialchars($dbInfo['host']); ?></div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-table"></i>
                    </div>
                    <div class="info-card-content">
                        <div class="info-label">Total Tables</div>
                        <div class="info-value"><?php echo number_format(count($tables)); ?></div>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-card-icon" style="background: linear-gradient(135deg, var(--alt-color-1), var(--alt-color-2));">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <div class="info-card-content">
                        <div class="info-label">Total Size</div>
                        <div class="info-value"><?php echo number_format($dbSize, 2); ?> MB</div>
                    </div>
                </div>
            </div>

            <!-- Full Database Export -->
            <div class="export-card">
                <div class="export-card-content">
                    <div class="export-icon">
                        <i class="fas fa-file-archive"></i>
                    </div>
                    <div class="export-details">
                        <h3>Complete Database Backup</h3>
                    </div>
                </div>
                <a href="?export=full_sql&db=main" class="btn btn-primary btn-export">
                    <i class="fas fa-download"></i>
                    Export Now
                </a>
            </div>
        </div>

        <!-- Online Payment Database Export -->
        <div class="dashboard-section">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>
                Online Payment Database Export
            </h2>
            
            <?php if ($pdo_online_payment): ?>
                <!-- Online Payment Database Information -->
                <div class="info-grid" style="margin-bottom: 30px;">
                    <div class="info-card">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="info-card-content">
                            <div class="info-label">Database Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($onlinePaymentDbInfo['name']); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, var(--tertiary-color), var(--primary-color));">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="info-card-content">
                            <div class="info-label">Host</div>
                            <div class="info-value"><?php echo htmlspecialchars($onlinePaymentDbInfo['host']); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-table"></i>
                        </div>
                        <div class="info-card-content">
                            <div class="info-label">Total Tables</div>
                            <div class="info-value"><?php echo number_format(count($onlinePaymentTables)); ?></div>
                        </div>
                    </div>
                    <div class="info-card">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, var(--alt-color-1), var(--alt-color-2));">
                            <i class="fas fa-hdd"></i>
                        </div>
                        <div class="info-card-content">
                            <div class="info-label">Total Size</div>
                            <div class="info-value"><?php echo number_format($onlinePaymentDbSize, 2); ?> MB</div>
                        </div>
                    </div>
                </div>

                <!-- Full Online Payment Database Export -->
                <div class="export-card" style="margin-bottom: 30px;">
                    <div class="export-card-content">
                        <div class="export-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-file-archive"></i>
                        </div>
                        <div class="export-details">
                            <h3>Complete Payment Database Backup</h3>
                        </div>
                    </div>
                    <a href="?export=full_sql&db=online_payment" class="btn btn-primary btn-export">
                        <i class="fas fa-download"></i>
                        Export Now
                    </a>
                </div>

                <!-- Individual Table Exports for Online Payment -->
                <div>
                    <h2 class="section-title">
                        <i class="fas fa-table"></i>
                        Online Payment Database - Individual Table Exports
                    </h2>
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
                                <?php if (count($onlinePaymentTables) > 0): ?>
                                    <?php foreach ($onlinePaymentTables as $table): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($table['name']); ?></strong>
                                            </td>
                                            <td><?php echo number_format($table['rows']); ?></td>
                                            <td><?php echo number_format($table['size'], 2); ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="?export=table_sql&table=<?php echo urlencode($table['name']); ?>&db=online_payment" 
                                                       class="btn btn-sm btn-secondary" 
                                                       title="Export as SQL">
                                                        <i class="fas fa-file-code"></i> SQL
                                                    </a>
                                                    <a href="?export=table_csv&table=<?php echo urlencode($table['name']); ?>&db=online_payment" 
                                                       class="btn btn-sm btn-secondary" 
                                                       title="Export as CSV">
                                                        <i class="fas fa-file-csv"></i> CSV
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 20px; color: #6c757d;">
                                            No Tables Found In Online Payment Database
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Could Not Connect To Online Payment Database. Please Check Database Credentials In The Configuration.
                </div>
            <?php endif; ?>
        </div>

        <!-- Individual Table Exports -->
        <div class="dashboard-section">
            <h2 class="section-title">
                <i class="fas fa-table"></i>
                Main Database - Individual Table Exports
            </h2>
            
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
                                        <a href="?export=table_sql&table=<?php echo urlencode($table['name']); ?>&db=main" 
                                           class="btn btn-sm btn-secondary" 
                                           title="Export as SQL">
                                            <i class="fas fa-file-code"></i> SQL
                                        </a>
                                        <a href="?export=table_csv&table=<?php echo urlencode($table['name']); ?>&db=main" 
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
            <h2 class="section-title">
                <i class="fas fa-question-circle"></i>
                Export Information
            </h2>
            <div class="info-cards-grid">
                <div class="info-card-large">
                    <div class="info-card-large-header" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <i class="fas fa-file-code"></i>
                        <h3>Export Formats</h3>
                    </div>
                    <div class="info-card-large-body">
                        <div class="format-item">
                            <i class="fas fa-database" style="color: var(--primary-color);"></i>
                            <div>
                                <strong>SQL Format</strong>
                                <p>Complete database structure and data. Can be imported using phpMyAdmin or MySQL command line.</p>
                            </div>
                        </div>
                        <div class="format-item">
                            <i class="fas fa-file-csv" style="color: #10b981;"></i>
                            <div>
                                <strong>CSV Format</strong>
                                <p>Table data only. Useful for importing into spreadsheet applications or other databases.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="info-card-large">
                    <div class="info-card-large-header" style="background: linear-gradient(135deg, var(--alt-color-1), var(--alt-color-2));">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Security Notes</h3>
                    </div>
                    <div class="info-card-large-body">
                        <div class="security-item">
                            <i class="fas fa-lock" style="color: var(--alt-color-1);"></i>
                            <p>Database exports contain sensitive information. Store backups securely.</p>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-user-shield" style="color: var(--alt-color-1);"></i>
                            <p>Only Super Administrators can access this page.</p>
                        </div>
                        <div class="security-item">
                            <i class="fas fa-cloud-download-alt" style="color: var(--alt-color-1);"></i>
                            <p>Exports are generated on-demand and not stored on the server.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Enhanced Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .info-card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .info-card-content {
            flex: 1;
        }

        .info-label {
            font-size: 11px;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        /* Section Header Enhanced */
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .section-title i {
            color: var(--alt-color-1);
        }

        .subsection-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .subsection-title i {
            color: var(--alt-color-1);
        }

        /* Export Cards */
        .export-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .export-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .export-card-content {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
        }

        .export-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            flex-shrink: 0;
        }

        .export-details h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .btn-export {
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-export:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(28, 77, 161, 0.3);
        }

        /* Table Styling */
        .table-list {
            margin-top: 20px;
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-table thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .data-table th {
            padding: 18px 20px;
            text-align: left;
            font-weight: 700;
            color: white;
            font-family: 'Barlow Semi Condensed', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.85rem;
        }

        .data-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-dark);
        }

        .data-table tbody tr {
            transition: background 0.2s ease;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-actions {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        /* Info Cards Grid for Export Information */
        .info-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .info-card-large {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .info-card-large:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .info-card-large-header {
            padding: 20px 25px;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-card-large-header i {
            font-size: 1.3rem;
        }

        .info-card-large-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .info-card-large-body {
            padding: 25px;
        }

        .format-item, .security-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .format-item:last-child, .security-item:last-child {
            margin-bottom: 0;
        }

        .format-item i, .security-item i {
            font-size: 1.2rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .format-item strong, .security-item strong {
            display: block;
            color: var(--text-dark);
            margin-bottom: 5px;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .format-item p, .security-item p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Alert Styling */
        .alert-warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffc107;
            color: #856404;
            padding: 18px 20px;
            border-radius: 10px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .alert-warning i {
            font-size: 1.2rem;
            color: #f59e0b;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .info-cards-grid {
                grid-template-columns: 1fr;
            }

            .export-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
            }
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
                padding: 12px 15px;
            }

            .table-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn-sm {
                width: 100%;
                justify-content: center;
            }

            .export-card-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .export-icon {
                width: 50px;
                height: 50px;
                font-size: 1.3rem;
            }
        }
    </style>

<?php include '../app/includes/admin-footer.php'; ?>


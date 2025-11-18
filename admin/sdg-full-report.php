<?php
/**
 * UPHSL Admin SDG Full Report Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing SDG Full Report PDF
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Helper function to convert PHP ini size to bytes
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    switch($last) {
        case 'g': $val *= 1024;
        case 'm': $val *= 1024;
        case 'k': $val *= 1024;
    }
    return $val;
}

// Helper function to format bytes to human readable
function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    header('Location: ../auth/login.php');
    exit;
}

$pdo = getDBConnection();
$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'SDG Full Report Management';

$success = null;
$error = null;

// Handle setting PDF from existing file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'set_existing_pdf') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
    try {
        $selectedPdf = Validator::sanitize($_POST['selected_pdf'] ?? '', 'string');
        
        if (empty($selectedPdf)) {
            throw new Exception('No PDF file selected.');
        }
        
        // Validate that the file exists
        $pdfPath = 'assets/documents/pdfs/' . basename($selectedPdf);
        $fullPath = dirname(__DIR__) . '/' . $pdfPath;
        
        if (!file_exists($fullPath)) {
            throw new Exception('Selected PDF file does not exist.');
        }
        
        // Validate it's a PDF
        if (pathinfo($fullPath, PATHINFO_EXTENSION) !== 'pdf') {
            throw new Exception('Selected file is not a PDF.');
        }
        
        // Update database setting
        $result = setSetting('sdg_full_report_pdf', $pdfPath, 'file', 'SDG Full Report PDF', $_SESSION['user_id']);
        
        if (!$result) {
            throw new Exception('Failed to save PDF path to database.');
        }
        
        $success = 'SDG Full Report PDF set successfully!';
    } catch (Exception $e) {
        $error = $e->getMessage();
        }
    }
}

// Get current PDF path (no default - if not set, it's null)
$currentPdfPath = getSetting('sdg_full_report_pdf');
$currentPdfExists = false;
$fullPath = null;

// Debug: Check what's in the database
$pdo = getDBConnection();
$debugStmt = $pdo->prepare("SELECT * FROM settings WHERE setting_key = 'sdg_full_report_pdf'");
$debugStmt->execute();
$debugSetting = $debugStmt->fetch();

if ($currentPdfPath) {
    $fullPath = dirname(__DIR__) . '/' . $currentPdfPath;
    $currentPdfExists = file_exists($fullPath);
}

// Get list of available PDFs in the directory
$pdfDir = dirname(__DIR__) . '/assets/documents/pdfs/';
$availablePdfs = [];
if (is_dir($pdfDir)) {
    $files = scandir($pdfDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
            $availablePdfs[] = $file;
        }
    }
}

// Include header
include '../app/includes/admin-header.php';
?>



    <!-- SDG Full Report Management -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-file-pdf"></i>
                SDG Full Report Management
            </h1>
            <p class="dashboard-subtitle">Upload and manage the SDG Full Report PDF</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo XSS::clean($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo XSS::clean($error); ?>
            </div>
        <?php endif; ?>

        <!-- Set Existing PDF Form -->
        <?php if (!empty($availablePdfs)): ?>
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Select Existing PDF</h2>
            </div>
            
            <form method="POST" class="form-container">
                <?php echo CSRF::field(); ?>
                <input type="hidden" name="action" value="set_existing_pdf">
                
                <div class="form-group">
                    <label for="selected_pdf" class="form-label">
                        <i class="fas fa-file-pdf"></i>
                        Select PDF from Directory
                    </label>
                    <select name="selected_pdf" id="selected_pdf" class="form-control" required>
                        <option value="">-- Select a PDF file --</option>
                        <?php foreach ($availablePdfs as $pdf): ?>
                            <option value="<?php echo XSS::escapeAttr($pdf); ?>" <?php echo ($currentPdfPath && basename($currentPdfPath) === $pdf) ? 'selected' : ''; ?>>
                                <?php echo XSS::clean($pdf); ?>
                                <?php 
                                $pdfFullPath = $pdfDir . $pdf;
                                if (file_exists($pdfFullPath)) {
                                    echo ' (' . formatBytes(filesize($pdfFullPath)) . ')';
                                }
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text">Select an existing PDF file from the directory to set as the SDG Full Report.</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Set as SDG Full Report
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Current PDF Info -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Current SDG Full Report</h2>
            </div>
            
            <?php if ($currentPdfExists): ?>
                <div class="current-pdf-info">
                    <div class="pdf-info-item">
                        <strong>File Path:</strong>
                        <span><?php echo XSS::clean($currentPdfPath); ?></span>
                    </div>
                    <div class="pdf-info-item">
                        <strong>File Size:</strong>
                        <span><?php echo number_format(filesize(dirname(__DIR__) . '/' . $currentPdfPath) / 1024 / 1024, 2); ?> MB</span>
                    </div>
                    <div class="pdf-info-item">
                        <strong>Last Modified:</strong>
                        <span><?php echo date('F j, Y g:i A', filemtime(dirname(__DIR__) . '/' . $currentPdfPath)); ?></span>
                    </div>
                    <div class="pdf-actions">
                        <a href="../<?php echo XSS::escapeAttr($currentPdfPath); ?>" target="_blank" class="btn btn-secondary">
                            <i class="fas fa-eye"></i>
                            View PDF
                        </a>
                        <a href="../<?php echo XSS::escapeAttr($currentPdfPath); ?>" download class="btn btn-info">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php if ($currentPdfPath): ?>
                        <p><strong>No PDF file found at the specified path.</strong></p>
                        <p>Expected path: <code><?php echo XSS::clean($currentPdfPath); ?></code></p>
                        <p>Full path checked: <code><?php echo XSS::clean($fullPath); ?></code></p>
                        <p style="margin-top: 1rem;">Please upload a new PDF file to update the path.</p>
                    <?php else: ?>
                        <p><strong>No SDG Full Report PDF has been set yet.</strong></p>
                        <p style="margin-top: 1rem;">Please upload a PDF file to set it as the SDG Full Report.</p>
                    <?php endif; ?>
                    
                    <?php if ($debugSetting): ?>
                        <div style="margin-top: 1rem; padding: 1rem; background: #e9ecef; border-radius: 4px; font-size: 0.9rem;">
                            <strong>Debug Info (Database):</strong><br>
                            Setting Key: <?php echo XSS::clean($debugSetting['setting_key']); ?><br>
                            Setting Value: <code><?php echo XSS::clean($debugSetting['setting_value']); ?></code><br>
                            Last Updated: <?php echo XSS::clean($debugSetting['updated_at']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($availablePdfs)): ?>
                    <div class="available-pdfs" style="margin-top: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <strong>Available PDF files in directory:</strong>
                        <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                            <?php foreach ($availablePdfs as $pdf): ?>
                                <li><?php echo XSS::clean($pdf); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .current-pdf-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .pdf-info-item {
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .pdf-info-item:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .pdf-info-item strong {
            display: inline-block;
            width: 150px;
            color: #495057;
        }
        
        .pdf-info-item span {
            color: #212529;
        }
        
        .pdf-actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 1rem;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
            padding: 1rem;
            border-radius: 4px;
            border-left: 4px solid #ffc107;
        }
    </style>

<?php include '../app/includes/admin-footer.php'; ?>


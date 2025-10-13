<?php 	
session_start();
$_SESSION['isin'] = 'iamin';

// Password protection
$admin_password = "import_stud123#"; // Hidden password
$is_authenticated = isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;

// Handle login
if (isset($_POST['admin_login'])) {
    $entered_password = $_POST['admin_password'] ?? '';
    if ($entered_password === $admin_password) {
        $_SESSION['admin_authenticated'] = true;
        $is_authenticated = true;
    } else {
        $login_error = "Invalid password. Access denied.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_authenticated']);
    $is_authenticated = false;
    header("Location: excel-import.php");
    exit;
}

// If not authenticated, show login form
if (!$is_authenticated) {
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>UPHSL Admin - Login Required</title>
        <style>
            body { font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif; }
            .login-container { max-width: 400px; margin: 100px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; background-color: #f9f9f9; }
            .login-container h2 { text-align: center; color: #333; margin-bottom: 20px; }
            .form-group { margin-bottom: 15px; }
            .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
            .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
            .submit-btn { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
            .submit-btn:hover { background-color: #0056b3; }
            .error { color: red; text-align: center; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>🔒 Admin Access Required</h2>
            <p style="text-align: center; color: #666; margin-bottom: 20px;">Please enter the admin password to access the CSV import system.</p>
            
            <?php if (isset($login_error)) { ?>
                <div class="error"><?php echo $login_error; ?></div>
            <?php } ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="admin_password">Password:</label>
                    <input type="password" name="admin_password" id="admin_password" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="admin_login" class="submit-btn">Login</button>
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

include "dbconnect.php";
// Handle Post/Redirect/Get token to avoid double-submit on refresh
session_start();
if (!isset($_SESSION['import_csrf'])) {
    $_SESSION['import_csrf'] = bin2hex(random_bytes(16));
}

// Helper: clean a CSV/Excel field value
function cleanImportField($value, $fieldType = 'generic') {
    // Ensure it's a string
    if (!is_string($value)) { $value = strval($value); }
    // Remove UTF-8 BOM if present
    $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
    // Trim whitespace (including non-breaking spaces)
    $value = trim(str_replace("\xC2\xA0", ' ', $value));
    // Remove surrounding quotes only if both start and end are quotes
    if ((strlen($value) >= 2) && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
        $value = substr($value, 1, -1);
    }
    // Normalize internal whitespace to single spaces
    $value = preg_replace('/\s+/', ' ', $value);
    // Placeholder handling only for non-name fields; allow names like "NA" or "-" legitimately
    if ($fieldType === 'course' || $fieldType === 'generic') {
        $upper = strtoupper($value);
        // Consider only strong placeholders as empty; do NOT treat 'NA' or '-' as empty
        if ($upper === 'NULL' || $upper === 'N/A' || $upper === 'NONE') {
            $value = '';
        }
    }
    return $value;
}

// Function to map campus to table name
function mapCampusToTable($campid) {
    $map = [
        "UPHB" => "binan",
        "UPHMU" => "medical_university", 
        "UPHG" => "gma",
        "UPHM" => "manila",
        "PHCP" => "pangasinan"
    ];
    return isset($map[$campid]) ? $map[$campid] : null;
}

// Function to check if table exists
function tableExists($con, $tableName) {
    if (trim($tableName) === '') { return false; }
    $t = mysqli_real_escape_string($con, $tableName);
    $sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name='".$t."' LIMIT 1";
    $res = @mysqli_query($con, $sql);
    if ($res && mysqli_fetch_row($res)) { return true; }
    return false;
}

// Handle Excel file upload and import
if (isset($_POST["btnsubmit"]) && isset($_FILES["excel_file"])) {
    $campid = $_POST["campid"];
    $table = mapCampusToTable($campid);
    
    echo "<script>console.log('Import request - Campus: $campid, Table: $table');</script>";
    
    if ($table === null) {
        $err = "Invalid campus selected.";
    } else if (!tableExists($con, $table)) {
        $err = "Campus database table not available.";
    } else {
        $file = $_FILES["excel_file"];
        
        // Check if file was uploaded successfully
        if ($file["error"] == UPLOAD_ERR_OK) {
            $fileType = pathinfo($file["name"], PATHINFO_EXTENSION);
            
            // Check if file is CSV format
            if (strtolower($fileType) == 'csv') {
                // Move uploaded file to temporary location
                $uploadDir = "uploads/";
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = $uploadDir . uniqid() . "_" . $file["name"];
                if (move_uploaded_file($file["tmp_name"], $fileName)) {
                    // Process CSV file
                    $result = processExcelFile($con, $fileName, $table);
                    
                    if ($result['success']) {
                        $success = "Successfully imported " . $result['imported'] . " student records to " . strtoupper($table) . " table.";
                        if ($result['skipped'] > 0) {
                            $success .= " " . $result['skipped'] . " records were skipped (duplicate student numbers already exist).";
                            
                            // Create detailed message for browser dialog
                            $detailedMessage = "IMPORT SUMMARY:\n\n";
                            $detailedMessage .= "✅ Successfully imported: " . $result['imported'] . " new records\n";
                            $detailedMessage .= "⚠️ Skipped (duplicates): " . $result['skipped'] . " records\n\n";
                            $detailedMessage .= "NOTE: Records with existing student numbers were ignored to prevent duplicates.";
                            
                            // Add detailed skip information if there are skipped records
                            if (!empty($result['skippedRecords'])) {
                                $detailedMessage .= "\n\nSKIPPED RECORDS DETAILS:\n";
                                $detailedMessage .= "─────────────────────────\n";
                                
                                // Show only first 20 skipped records to prevent overwhelming the dialog
                                $recordsToShow = array_slice($result['skippedRecords'], 0, 20);
                                foreach ($recordsToShow as $skipReason) {
                                    $detailedMessage .= "• " . $skipReason . "\n";
                                }
                                
                                // Show count if there are more records
                                if (count($result['skippedRecords']) > 20) {
                                    $remaining = count($result['skippedRecords']) - 20;
                                    $detailedMessage .= "\n... and {$remaining} more skipped records (showing first 20)";
                                }
                            }
                            
                            // Store detailed message for JavaScript alert
                            $success .= "<script>setTimeout(function(){ alert(" . json_encode($detailedMessage) . "); }, 1000);</script>";
                        }
                    } else {
                        $err = "Error processing CSV file: " . $result['message'];
                    }
                    
                    
                    // Clean up uploaded file
                    unlink($fileName);
                } else {
                    $err = "Failed to upload file.";
                }
            } else {
                $err = "Please upload a CSV file (.csv).";
            }
        } else {
            $err = "Error uploading file: " . $file["error"];
        }
    }
}


// Function to update progress
function updateProgress($progress, $status, $details = '') {
    // Store progress in session for now (simpler approach)
    session_start();
    $_SESSION['import_progress'] = $progress;
    $_SESSION['import_status'] = $status;
    $_SESSION['import_details'] = $details;
}

// Function to process Excel file with specific format
function processExcelFile($con, $filePath, $table) {
    // Reset progress
    updateProgress(0, 'Starting import...', 'Initializing...');
    
    // Optimize memory usage for large files
    ini_set('memory_limit', '512M');
    ini_set('max_execution_time', 300); // 5 minutes for large files
    
    // For now, we'll process as CSV. For full Excel support, consider installing PhpSpreadsheet
    $handle = fopen($filePath, "r");
    if (!$handle) {
        updateProgress(0, 'Error', 'Could not open file');
        return ['success' => false, 'message' => 'Could not open file'];
    }
    
    // Count total lines first for progress calculation
    $totalLines = 0;
    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
        $totalLines++;
    }
    rewind($handle);
    
    updateProgress(5, 'Reading Excel file...', "Found {$totalLines} rows to process");
    
    $imported = 0;
    $skipped = 0;
    $lineNumber = 0;
    $batchSize = 200; // Process in larger batches for better performance with large files
    $batchData = [];
    $skippedRecords = []; // Track skipped records with reasons
    
    // Create table if it doesn't exist
    updateProgress(10, 'Setting up database...', 'Creating table structure');
    $createTableSQL = "CREATE TABLE IF NOT EXISTS `{$table}` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `stud_num` varchar(50) NOT NULL,
        `lname` varchar(255) NOT NULL,
        `fname` varchar(255) NOT NULL,
        `course` varchar(255) DEFAULT NULL,
        `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `stud_num` (`stud_num`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    if (!mysqli_query($con, $createTableSQL)) {
        fclose($handle);
        updateProgress(0, 'Error', 'Could not create table');
        return ['success' => false, 'message' => 'Could not create table: ' . mysqli_error($con)];
    }
    
    // Start transaction for better performance
    mysqli_autocommit($con, FALSE);
    updateProgress(15, 'Validating data format...', 'Checking column structure');
    
    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
        $lineNumber++;
        
        // Update progress every 500 rows
        // Update progress every 1000 rows for large files (optimized for 6000+ records)
        if ($lineNumber % 1000 == 0) {
            $progress = 15 + (($lineNumber / $totalLines) * 75); // 15% to 90%
            $remaining = $totalLines - $lineNumber;
            $processed = $lineNumber;
            $timeEstimate = $remaining > 0 ? " (~" . round($remaining / 1000) . "k rows remaining)" : "";
            updateProgress($progress, 'Processing student records...', "Processed {$processed} of {$totalLines} rows{$timeEstimate}");
        }
        
    // No header skipping: every row is a data row
        
    // We need at least 3 columns: stud_num, lname, fname (course optional at the end)
    $colCount = count($data);
    if ($colCount < 3) {
            $skippedRecords[] = "Row {$lineNumber}: Insufficient columns (found " . count($data) . ", expected 4)";
            $skipped++;
            continue;
        }
        
    // Map columns with cleaning. Robust to variable extra name columns:
    // Assumption: Column 1 = stud_num, Column 2 = lname, Last column = course (optional),
    // fname = concatenate columns 3 .. last-1 (or just column 3 if only 3-4 cols)
    $studNum = cleanImportField($data[0], 'generic');
    $lname = cleanImportField($data[1], 'name');
    if ($colCount == 3) {
        $fname = cleanImportField($data[2], 'name');
        $course = '';
    } else {
        $course = cleanImportField($data[$colCount - 1], 'course');
        $fnameParts = [];
        for ($i = 2; $i < $colCount - 1; $i++) {
            $p = cleanImportField($data[$i], 'name');
            if ($p !== '') { $fnameParts[] = $p; }
        }
        $fname = trim(implode(' ', $fnameParts));
    }

    // No auto-recovery: rely strictly on provided columns
        
    // Skip empty rows
    if (empty($studNum) || empty($lname) || empty($fname)) {
        $emptyFields = [];
        if (empty($studNum)) $emptyFields[] = "Student Number";
        if (empty($lname)) $emptyFields[] = "Last Name";
        if (empty($fname)) $emptyFields[] = "First Name";
            $skippedRecords[] = "Row {$lineNumber}: Empty fields - " . implode(", ", $emptyFields);
            $skipped++;
            continue;
        }

    // Normalize casing for names and course for better display/storage
    // Preserve case-sensitive short names like "NA" or "-" by only Title-Casing if length > 2
    if (mb_strlen($lname, 'UTF-8') > 2) {
        $lname = mb_convert_case($lname, MB_CASE_TITLE, 'UTF-8');
    }
    if (mb_strlen($fname, 'UTF-8') > 2) {
        $fname = mb_convert_case($fname, MB_CASE_TITLE, 'UTF-8');
    }
    // Normalize course spacing if present
    $course = preg_replace('/\s+/', ' ', $course);
        
    // Escape data
    $studNum = mysqli_real_escape_string($con, $studNum);
    $lname = mysqli_real_escape_string($con, $lname);
    $fname = mysqli_real_escape_string($con, $fname);
    $course = mysqli_real_escape_string($con, $course);
        
    // Add to batch
    $batchData[] = "('{$studNum}', '{$lname}', '{$fname}', '{$course}')";
        
        // Process batch when it reaches batch size
        if (count($batchData) >= $batchSize) {
            $result = processBatch($con, $table, $batchData);
            $imported += $result['imported'];
            $skipped += $result['skipped'];
            $batchData = []; // Reset batch
        }
    }
    
    // Process remaining batch
    if (!empty($batchData)) {
        $result = processBatch($con, $table, $batchData);
        $imported += $result['imported'];
        $skipped += $result['skipped'];
    }
    
    updateProgress(95, 'Finalizing import...', 'Saving to database');
    
    // Commit transaction
    if (mysqli_commit($con)) {
        $success = true;
        updateProgress(100, 'Import completed!', "Successfully imported {$imported} records, {$skipped} skipped");
    } else {
        mysqli_rollback($con);
        $success = false;
        updateProgress(0, 'Error', 'Database transaction failed');
    }
    
    mysqli_autocommit($con, TRUE);
    fclose($handle);
    
    return [
        'success' => $success,
        'imported' => $imported,
        'skipped' => $skipped,
        'skippedRecords' => $skippedRecords
    ];
}

// Function to process batch of records
function processBatch($con, $table, $batchData) {
    $imported = 0;
    $skipped = 0;
    
    if (empty($batchData)) {
        return ['imported' => 0, 'skipped' => 0];
    }
    
    $values = implode(',', $batchData);
    $sql = "INSERT IGNORE INTO `{$table}` (`stud_num`, `lname`, `fname`, `course`) 
            VALUES {$values}";
    
    if (mysqli_query($con, $sql)) {
        $imported = mysqli_affected_rows($con);
        $skipped += (count($batchData) - $imported);
    } else {
        // If batch fails, try individual inserts
        foreach ($batchData as $record) {
            $sql = "INSERT IGNORE INTO `{$table}` (`stud_num`, `lname`, `fname`, `course`) 
                    VALUES {$record}";
            
            if (mysqli_query($con, $sql)) {
                if (mysqli_affected_rows($con) > 0) {
                    $imported++;
                } else {
                    $skipped++;
                }
            } else {
                $skipped++;
            }
        }
    }
    
    return ['imported' => $imported, 'skipped' => $skipped];
}
?>	

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>UPHSL Excel Import - Student Data</title>

<style>
@keyframes progressAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.progress-bar-animated {
    background: linear-gradient(-45deg, #4CAF50, #45a049, #4CAF50, #45a049);
    background-size: 400% 400%;
    animation: progressAnimation 2s ease infinite;
}

.progress-container {
    transition: all 0.3s ease;
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.navigation {
    background-color: #f8f9fa;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    text-align: center;
}

.navigation a {
    display: inline-block;
    margin: 0 10px;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.3s;
}

.navigation a:hover {
    background-color: #0056b3;
}

.navigation a.active {
    background-color: #28a745;
}
</style>

<script>
function checkCampus(campval) {
    var fileInput = document.getElementById("excel_file");
    var submitBtn = document.getElementById("btnsubmit");
    
    if (campval === "") {
        fileInput.disabled = true;
        submitBtn.disabled = true;
    } else {
        fileInput.disabled = false;
        submitBtn.disabled = false;
        // Re-validate file if one is already selected
        validateFile();
    }
}

function validateFile() {
    var fileInput = document.getElementById("excel_file");
    var submitBtn = document.getElementById("btnsubmit");
    var campid = document.getElementById("campid").value;
    
    if (fileInput.files.length > 0 && campid !== "") {
        var fileName = fileInput.files[0].name;
        var fileExt = fileName.split('.').pop().toLowerCase();
        
        if (fileExt === 'csv') {
            submitBtn.disabled = false;
            document.getElementById("file-info").innerHTML = 
                '<div style="color: green; font-size: 12px; margin-top: 5px;">✓ Valid CSV file selected</div>';
        } else {
            submitBtn.disabled = true;
            document.getElementById("file-info").innerHTML = 
                '<div style="color: red; font-size: 12px; margin-top: 5px;">✗ Please select a CSV file (.csv)</div>';
        }
    } else {
        submitBtn.disabled = true;
        document.getElementById("file-info").innerHTML = '';
    }
}

function showProgress() {
    document.getElementById("progress-container").style.display = "block";
    document.getElementById("btnsubmit").value = "Processing...";
    document.getElementById("btnsubmit").disabled = true;
    
    // Start real-time progress tracking
    startRealTimeProgress();
}

function startRealTimeProgress() {
    // Since the separate progress tracker is having issues, use simulation
    // This will provide a good user experience while the import happens
    simulateProgress();
}

function simulateProgress() {
    let progress = 0;
    let steps = [
        { percent: 10, text: "Reading Excel file...", details: "Analyzing file structure" },
        { percent: 25, text: "Validating data format...", details: "Checking column structure" },
        { percent: 40, text: "Processing student records...", details: "Importing data in batches" },
        { percent: 60, text: "Processing student records...", details: "Continuing import..." },
        { percent: 80, text: "Processing student records...", details: "Almost complete..." },
        { percent: 95, text: "Finalizing import...", details: "Saving to database" },
        { percent: 100, text: "Import completed!", details: "Processing finished" }
    ];
    
    let currentStep = 0;
    
    function updateProgress() {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            progress = step.percent;
            
            document.getElementById("progress-bar").style.width = progress + "%";
            document.getElementById("progress-bar").textContent = progress + "%";
            document.getElementById("progress-text").textContent = step.text;
            document.getElementById("progress-details").textContent = step.details;
            
            currentStep++;
            
            // Vary the timing based on the step
            let delay = currentStep < 3 ? 1000 : (currentStep < 6 ? 2000 : 1500);
            setTimeout(updateProgress, delay);
        } else {
            // Re-enable form when simulation completes
            setTimeout(() => {
                document.getElementById("btnsubmit").value = "Import Data";
                document.getElementById("btnsubmit").disabled = false;
                document.getElementById("progress-container").style.display = "none";
            }, 2000);
        }
    }
    
    updateProgress();
}
</script>

</head>

<body style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif' ">

<div class="navigation">
    <a href="excel-import.php" class="active">📊 CSV Import</a>
    <a href="guestold_student.php">💳 Payment Portal</a>
    <a href="?logout=1" style="background-color: #dc3545; margin-left: 20px;">🚪 Logout</a>
</div>

<?php if (isset($err)) { ?>
<script>
alert(<?php echo json_encode($err); ?>);
</script>
<?php } ?>

<?php if (isset($success)) { ?>
<script>
alert(<?php echo json_encode($success); ?>);
</script>
<?php } ?>

	<form method="post" enctype="multipart/form-data">
	<div align="center">
	<h2>University of Perpetual Help System</h2>	
	<h2 style="color: #12199C">CSV IMPORT - STUDENT DATA</h2><br>	
	<h1 style="color: green">Import Student Records</h1>	
	<strong>Select the UPHSL Campus where you want to import student data</strong><br><br>
		<select name="campid" id="campid" style="font-size: 14px; padding: 10px;" onchange="checkCampus(this.value)">
			<option value="">Select Campus</option>
			<option value="UPHB">Binan</option>
			<option value="UPHMU">Medical University</option>
			<option value="UPHG">GMA</option>
			<option value="UPHM">Manila</option>
			<option value="PHCP">Pangasinan</option>
		</select>
	</div>
	<div>&nbsp;</div>

	<div align="center">
		<strong>Select CSV File to Import:</strong><br><br>
		<input type="file" name="excel_file" id="excel_file" accept=".csv" style="font-size: 14px; padding: 5px;" onchange="validateFile()" disabled>
		<div id="file-info"></div>
	</div>
	<div>&nbsp;</div>

	<div align="center" style="background-color: #f0f8ff; padding: 15px; margin: 20px auto; max-width: 600px; border-radius: 5px;">
		<h3 style="color: #333; margin-top: 0; text-align: center;">File Format Requirements:</h3>
		<div style="text-align: center; margin: 10px 0;">
		<strong>File Structure:</strong><br>
		• <strong>No header rows.</strong> Each row represents one student record.<br>
		• <strong>Data Columns (in order):</strong><br>
		&nbsp;&nbsp;- Column 1: <strong>Student Number</strong> (must be unique)<br>
		&nbsp;&nbsp;- Column 2: <strong>Last Name</strong><br>
		&nbsp;&nbsp;- Column 3: <strong>First Name</strong><br>
		&nbsp;&nbsp;- Column 4: <strong>Course</strong><br><br>
		<strong>Supported format:</strong> .csv only<br>
		<strong>Performance:</strong> Optimized for large files (6000+ rows)<br>
		<strong>Note:</strong> Duplicate student numbers will be ignored. Empty rows are automatically skipped.<br><br>
		<strong style="color: #007bff;">📋 Get CSV file from Sir Arnold</strong>
		</div>
	</div>

	<?php if (isset($err)) { ?>
	<div align="center" style="color:#FFFFFF; background-color:#FF0000; padding:10px; font-weight:bold"><?php echo $err; ?></div>
	<div>&nbsp;</div>
	<?php } ?>

	<?php if (isset($success)) { ?>
	<div align="center" style="color:#FFFFFF; background-color:#28a745; padding:10px; font-weight:bold"><?php echo $success; ?></div>
	<div>&nbsp;</div>
	<?php } ?>



	<div align="center" style="padding: 20px;">
		<input type="submit" value="Import Data" id="btnsubmit" name="btnsubmit" style="padding: 10px; border-radius: 5px; background-color:green; color: white; width: 150px; font-size: 14px;" onMouseOver="javascript:this.style.backgroundColor = '#ED822F';" onMouseOut="javascript:this.style.backgroundColor = '#008000';" disabled>
		<div id="progress-container" class="progress-container fade-in" style="display: none; margin-top: 15px; max-width: 500px; margin-left: auto; margin-right: auto;">
			<div style="background-color: #f0f0f0; border-radius: 10px; padding: 3px; margin-bottom: 10px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
				<div id="progress-bar" class="progress-bar-animated" style="height: 20px; border-radius: 8px; width: 0%; transition: width 0.3s ease; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
					0%
				</div>
			</div>
			<div id="progress-text" style="text-align: center; color: #666; font-size: 12px; font-weight: 500;">
				Preparing to process file...
			</div>
			<div id="progress-details" style="text-align: center; color: #888; font-size: 11px; margin-top: 5px; font-style: italic;">
				<!-- Progress details will be shown here -->
			</div>
		</div>
	</div>
	</form>
</body>
</html>
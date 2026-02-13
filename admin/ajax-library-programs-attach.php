<?php
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'error'=>'Method not allowed']); exit; }
if (!isLoggedIn() || !isSuperAdmin()) { http_response_code(403); echo json_encode(['success'=>false,'error'=>'Unauthorized']); exit; }
if (!CSRF::verify()) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'Invalid CSRF token']); exit; }

$program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
$files = isset($_POST['files']) ? $_POST['files'] : [];
if ($program_id <= 0 || !is_array($files) || empty($files)) { echo json_encode(['success'=>false,'error'=>'program_id and files required']); exit; }

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id FROM library_programs WHERE id = :id');
$stmt->execute([':id'=>$program_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) { echo json_encode(['success'=>false,'error'=>'Program not found']); exit; }

// Use flat library directory
$baseDir = __DIR__ . '/../assets/documents/library/';
$added = []; $skipped = []; $errors = [];
foreach ($files as $fname) {
    $fname = basename($fname);
    $full = $baseDir . $fname;
    if (!is_file($full)) { $errors[] = $fname . ': not found'; continue; }
    // check if already in DB
    $st = $pdo->prepare('SELECT id FROM library_program_pdfs WHERE program_id = :pid AND filename = :fn');
    $st->execute([':pid'=>$program_id,':fn'=>$fname]);
    if ($st->fetch()) { $skipped[] = $fname; continue; }
    // insert
    try {
        $rel = 'assets/documents/library/'.$fname;
        $ins = $pdo->prepare('INSERT INTO library_program_pdfs (program_id, filename, path, uploaded_at) VALUES (:pid,:fn,:path,NOW())');
        $ins->execute([':pid'=>$program_id,':fn'=>$fname,':path'=>$rel]);
        $added[] = $fname;
    } catch (Exception $e) { $errors[] = $fname.': db error'; }

    // Ensure server thumbnail exists for instant availability: generate now if missing
    try {
        $thumbFull = $baseDir . pathinfo($fname, PATHINFO_FILENAME) . '.jpg';
        if (!is_file($thumbFull)) {
            // Attempt fast in-PHP generation using Imagick if available
            if (class_exists('Imagick')) {
                try {
                    $im = new Imagick();
                    $pdfFull = $full;
                    $im->setResolution(100,100);
                    $im->readImage($pdfFull . '[0]');
                    $im->setImageFormat('jpeg');
                    $im->setImageCompressionQuality(82);
                    $im->thumbnailImage(1000, 0);
                    $im->writeImage($thumbFull);
                    $im->clear(); $im->destroy();
                    @chmod($thumbFull, 0644);
                } catch (Exception $e) {
                    // fallback to ghostscript below
                }
            }

            // If Imagick not available or failed, try Ghostscript command synchronously
            if (!is_file($thumbFull)) {
                $gsOut = escapeshellarg($thumbFull);
                $gsIn = escapeshellarg($full);
                $cmd = "gs -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -r100 -dNOPAUSE -dBATCH -sOutputFile={$gsOut} {$gsIn} 2>&1";
                @exec($cmd, $o, $rc);
                if (isset($rc) && $rc === 0 && file_exists($thumbFull)) {
                    @chmod($thumbFull, 0644);
                }
            }

            // Final fallback: create a tiny GD placeholder so client shows something immediately
            if (!is_file($thumbFull) && function_exists('imagecreatetruecolor')) {
                $w = 640; $h = 360;
                $img = imagecreatetruecolor($w, $h);
                $bg = imagecolorallocate($img, 240, 240, 240);
                imagefill($img, 0, 0, $bg);
                $txtcol = imagecolorallocate($img, 120, 120, 120);
                imagestring($img, 3, 12, 12, 'Preview', $txtcol);
                imagejpeg($img, $thumbFull, 72);
                imagedestroy($img);
                @chmod($thumbFull, 0644);
            }
        }
    } catch (Exception $e) {
        error_log('attach-thumb-gen error: '.$e->getMessage());
    }
}

echo json_encode(['success'=>count($added)>0,'added'=>$added,'skipped'=>$skipped,'errors'=>$errors]);

?>

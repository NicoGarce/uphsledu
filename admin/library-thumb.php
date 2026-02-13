<?php
// Lightweight thumbnail proxy for library PDFs.
// Usage: /admin/library-thumb.php?f=filename.pdf
require_once __DIR__ . '/..//app/includes/functions.php';

// sanitize input
$f = isset($_GET['f']) ? $_GET['f'] : '';
$f = basename($f);
if ($f === '') { http_response_code(400); exit; }

$baseDir = __DIR__ . '/../assets/documents/library/';

// Determine requested type and map to PDF and thumbnail paths
$ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
$pdfFull = '';
$thumbFull = '';
if ($ext === 'jpg' || $ext === 'jpeg' || $ext === 'png') {
    // client requested a thumb filename; serve it directly if present
    $thumbFull = $baseDir . $f;
    $pdfBase = pathinfo($f, PATHINFO_FILENAME);
    $pdfFull = $baseDir . $pdfBase . '.pdf';
} elseif ($ext === 'pdf') {
    $pdfFull = $baseDir . $f;
    $thumbFull = $baseDir . pathinfo($f, PATHINFO_FILENAME) . '.jpg';
} else {
    // no extension: assume base name for pdf
    $pdfFull = $baseDir . $f . '.pdf';
    $thumbFull = $baseDir . $f . '.jpg';
}

// If thumb exists, serve it with caching
if (is_file($thumbFull)) {
    $lm = gmdate('D, d M Y H:i:s', filemtime($thumbFull)) . ' GMT';
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($thumbFull));
    header('Last-Modified: ' . $lm);
    header('Cache-Control: public, max-age=86400');
    readfile($thumbFull);
    exit;
}

// If the PDF itself exists, spawn background generation for the proper paths
if (is_file($pdfFull)) {
    try {
        $phpBinary = defined('PHP_BINARY') ? PHP_BINARY : 'php';
        $script = __DIR__ . '/generate-pdf-thumb.php';
        $cmd = escapeshellarg($phpBinary) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($pdfFull) . ' ' . escapeshellarg($thumbFull);
        if (stripos(PHP_OS, 'WIN') === 0) {
            $winCmd = 'start "" /B ' . $cmd . ' > NUL 2>&1';
            @pclose(@popen($winCmd, 'r'));
        } else {
            @exec($cmd . ' > /dev/null 2>&1 &');
        }
    } catch (Exception $e) { /* ignore */ }
}

// Output a small inline JPEG placeholder so clients see something instantly
if (function_exists('imagecreatetruecolor')) {
    $w = 320; $h = 180;
    $img = imagecreatetruecolor($w, $h);
    $bg = imagecolorallocate($img, 245, 245, 245);
    imagefill($img, 0, 0, $bg);
    $txt = is_file($pdfFull) ? 'Generating...' : 'Not available';
    $txtcol = imagecolorallocate($img, 140, 140, 140);
    imagestring($img, 3, 12, 12, $txt, $txtcol);
    header('Content-Type: image/jpeg');
    header('Cache-Control: no-cache, must-revalidate, max-age=5');
    imagejpeg($img, null, 70);
    imagedestroy($img);
    exit;
}

// fallback tiny 1x1 gif
header('Content-Type: image/gif');
header('Cache-Control: no-cache, must-revalidate, max-age=5');
echo base64_decode('R0lGODlhAQABAIABAP///wAAACwAAAAAAQABAAACAkQBADs=');
exit;

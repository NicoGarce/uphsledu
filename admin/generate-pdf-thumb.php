<?php
/**
 * CLI helper to generate a JPEG thumbnail for the first page of a PDF.
 * Usage: php generate-pdf-thumb.php "/full/path/file.pdf" "/full/path/thumb.jpg"
 * This script is executed in background by the upload endpoint.
 */

if (php_sapi_name() !== 'cli') exit(0);

$in = $argv[1] ?? '';
$out = $argv[2] ?? '';
if (empty($in) || empty($out)) exit(0);

// normalize
$in = str_replace(['\\'], ['\\\\'], $in);
$outDir = dirname($out);
if (!is_dir($outDir)) @mkdir($outDir, 0755, true);

try {
    if (class_exists('Imagick')) {
        $im = new Imagick();
        $im->setResolution(100,100);
        $im->readImage($in . '[0]');
        $im->setImageFormat('jpeg');
        $im->setImageCompressionQuality(82);
        $im->thumbnailImage(1000, 0);
        $im->writeImage($out);
        $im->clear(); $im->destroy();
        @chmod($out, 0644);
        exit(0);
    }

    // Ghostscript fallback
    $gsOut = escapeshellarg($out);
    $gsIn = escapeshellarg($in);
    // use lower dpi for speed
    $cmd = "gs -sDEVICE=jpeg -dFirstPage=1 -dLastPage=1 -r100 -dNOPAUSE -dBATCH -sOutputFile={$gsOut} {$gsIn} 2>&1";
    @exec($cmd, $o, $rc);
    if (isset($rc) && $rc === 0 && file_exists($out)) {
        @chmod($out, 0644);
        exit(0);
    }
} catch (Exception $e) {
    // swallow errors; upload already saved the PDF
    error_log('generate-pdf-thumb error: '.$e->getMessage());
}

// If all else fails, create a tiny placeholder JPEG so client has something to show
if (!file_exists($out)) {
    if (function_exists('imagecreatetruecolor')) {
        $w = 640; $h = 360;
        $img = imagecreatetruecolor($w, $h);
        $bg = imagecolorallocate($img, 240, 240, 240);
        imagefill($img, 0, 0, $bg);
        $txtcol = imagecolorallocate($img, 120, 120, 120);
        imagestring($img, 3, 12, 12, 'Preview', $txtcol);
        imagejpeg($img, $out, 72);
        imagedestroy($img);
        @chmod($out, 0644);
    }
}

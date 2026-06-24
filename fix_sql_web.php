<?php
/**
 * Fix SQL File Encoding via Web
 * Access this file in browser: http://localhost/uphsledu/fix_sql_web.php
 */

$inputFile = 'online_payment_backup_2026-06-24_084901.sql';
$outputFile = 'online_payment_backup_2026-06-24_084901_fixed.sql';

// Read the SQL file
$content = file_get_contents($inputFile);
if ($content === false) {
    die("Failed to read input file");
}

// Common double-encoded UTF-8 patterns (UTF-8 bytes interpreted as Latin-1)
$replacements = [
    'Ã±' => 'ñ',
    'Ã¡' => 'á',
    'Ã©' => 'é',
    'Ã­' => 'í',
    'Ã³' => 'ó',
    'Ãº' => 'ú',
    'Ã\x81' => 'Á',
    'Ã\x89' => 'É',
    'Ã\x8d' => 'Í',
    'Ã\x93' => 'Ó',
    'Ã\x9a' => 'Ú',
    'Ã\x91' => 'Ñ',
    'Ã¼' => 'ü',
    'Ã\x9c' => 'Ü',
    'Ã\xbf' => '¿',
];

// Apply replacements
$fixedContent = str_replace(array_keys($replacements), array_values($replacements), $content);

// Write the fixed content
$result = file_put_contents($outputFile, $fixedContent);
if ($result === false) {
    die("Failed to write output file");
}

echo "<h1>SQL Encoding Fix Complete</h1>";
echo "<p>Fixed SQL file saved as: <strong>$outputFile</strong></p>";
echo "<p>Original file size: " . strlen($content) . " bytes</p>";
echo "<p>Fixed file size: " . strlen($fixedContent) . " bytes</p>";

// Show some examples of what was fixed
$changes = [];
foreach ($replacements as $from => $to) {
    if (strpos($content, $from) !== false) {
        $count = substr_count($content, $from);
        $changes[] = "$from → $to ($count occurrences)";
    }
}

if (!empty($changes)) {
    echo "<h2>Changes made:</h2>";
    echo "<ul>";
    foreach ($changes as $change) {
        echo "<li>$change</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No encoding issues found.</p>";
}

// Show before/after example
echo "<h2>Before/After Example:</h2>";
echo "<p><strong>Before:</strong> CerdeÃ±a</p>";
echo "<p><strong>After:</strong> Cerdeña</p>";
?>

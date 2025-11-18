<?php
/**
 * PDF Browser AJAX Endpoint
 * Returns list of PDF files in assets/documents/pdfs directory
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get search parameter
$search = $_GET['search'] ?? '';
$folder = $_GET['folder'] ?? '';

// Sanitize folder parameter to prevent path traversal
$folder = preg_replace('/[^a-zA-Z0-9_\-\.\/]/', '', $folder);
if (strpos($folder, '..') !== false) {
    $folder = '';
}

// Base path for PDFs
$basePath = realpath(__DIR__ . '/../assets/documents/pdfs/');
if ($basePath === false) {
    http_response_code(500);
    echo json_encode(['error' => 'PDF directory not found']);
    exit;
}
$basePath .= '/';
$webBasePath = '../assets/documents/pdfs/';

// Function to recursively get all PDF files
function getPDFFiles($dir, $webPath, $search = '', $relativePath = '') {
    $files = [];
    
    if (!is_dir($dir)) {
        return $files;
    }
    
    $items = scandir($dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $fullPath = $dir . '/' . $item;
        $currentRelativePath = $relativePath ? $relativePath . '/' . $item : $item;
        
        if (is_dir($fullPath)) {
            // Recursively get files from subdirectories
            $subFiles = getPDFFiles($fullPath, $webPath, $search, $currentRelativePath);
            $files = array_merge($files, $subFiles);
        } elseif (strtolower(pathinfo($item, PATHINFO_EXTENSION)) === 'pdf') {
            // Check if matches search
            if (empty($search) || stripos($item, $search) !== false) {
                $files[] = [
                    'name' => $item,
                    'path' => $currentRelativePath,
                    'fullPath' => $webPath . $currentRelativePath,
                    'size' => filesize($fullPath),
                    'modified' => filemtime($fullPath)
                ];
            }
        }
    }
    
    return $files;
}

// Get all PDF files
$allFiles = getPDFFiles($basePath, $webBasePath, $search);

// Sort by name
usort($allFiles, function($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});

// Format response
$response = [
    'success' => true,
    'files' => $allFiles,
    'count' => count($allFiles)
];

header('Content-Type: application/json');
echo json_encode($response);


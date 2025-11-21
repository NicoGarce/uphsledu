<?php
/**
 * PDF Upload AJAX Endpoint
 * Handles PDF file uploads to assets/documents/pdfs directory
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Only POST requests are accepted.']);
    exit;
}

// Check if user is logged in
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['pdf'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false, 
        'error' => 'No file uploaded. Request method: ' . $_SERVER['REQUEST_METHOD'],
        'debug' => [
            'files' => isset($_FILES) ? array_keys($_FILES) : [],
            'post' => isset($_POST) ? array_keys($_POST) : []
        ]
    ]);
    exit;
}

$uploadError = $_FILES['pdf']['error'];
if ($uploadError !== UPLOAD_ERR_OK) {
    $errorMessage = 'File upload error';
    switch ($uploadError) {
        case UPLOAD_ERR_INI_SIZE:
            $errorMessage = 'File size exceeds PHP upload_max_filesize limit';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $errorMessage = 'File size exceeds form MAX_FILE_SIZE limit';
            break;
        case UPLOAD_ERR_PARTIAL:
            $errorMessage = 'File upload was incomplete';
            break;
        case UPLOAD_ERR_NO_FILE:
            $errorMessage = 'No file was uploaded';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $errorMessage = 'Missing temporary folder';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $errorMessage = 'Failed to write file to disk';
            break;
        case UPLOAD_ERR_EXTENSION:
            $errorMessage = 'File upload stopped by PHP extension';
            break;
        default:
            $errorMessage = 'Unknown upload error: ' . $uploadError;
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $errorMessage, 'error_code' => $uploadError]);
    exit;
}

$file = $_FILES['pdf'];

// Validate file type
$allowedTypes = ['application/pdf'];
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file['type'], $allowedTypes) && $fileExtension !== 'pdf') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only PDF files are allowed.']);
    exit;
}

// Validate file size (max 50MB)
$maxSize = 50 * 1024 * 1024; // 50MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File size exceeds 50MB limit.']);
    exit;
}

// Base path for PDFs
$basePath = realpath(__DIR__ . '/../assets/documents/pdfs/');
if ($basePath === false) {
    // Try to create directory if it doesn't exist
    $basePath = __DIR__ . '/../assets/documents/pdfs/';
    if (!is_dir($basePath)) {
        if (!mkdir($basePath, 0755, true)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'PDF directory not found and could not be created']);
            exit;
        }
    }
    $basePath = realpath($basePath);
}
$basePath .= '/';

// Sanitize filename
$originalName = $file['name'];
$filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
$extension = pathinfo($originalName, PATHINFO_EXTENSION);
$finalFilename = $filename . '.' . $extension;

// Check if file already exists, append number if needed
$counter = 1;
$finalPath = $basePath . $finalFilename;
while (file_exists($finalPath)) {
    $finalFilename = $filename . '_' . $counter . '.' . $extension;
    $finalPath = $basePath . $finalFilename;
    $counter++;
}

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $finalPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save file']);
    exit;
}

// Set proper permissions
chmod($finalPath, 0644);

// Return success response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'filename' => $finalFilename,
    'message' => 'PDF uploaded successfully'
]);


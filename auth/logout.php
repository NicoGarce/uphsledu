<?php
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Set logout message
session_start();
setFlashMessage('success', 'You have been successfully logged out.');

// Redirect to login page
header("Location: login.php");
exit();
?>


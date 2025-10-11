<?php
/**
 * UPHSL [Service Name] Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description [Description of the service]
 */

$base_path = '../';
$page_title = "[Service Name]";

// Add base tag for clean URLs to fix asset paths
if (strpos($_SERVER['REQUEST_URI'], '.php') === false) {
    echo '<base href="../">';
}

include '../app/includes/header.php';
?>

<!-- Your content here -->

<?php
include '../app/includes/footer.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];

    // Decode the JSON content
    $contentArray = json_decode($content, true);

    // Convert the array to HTML (or handle it as needed)
    $htmlContent = '';

    if (isset($contentArray['ops'])) {
        foreach ($contentArray['ops'] as $op) {
            if (isset($op['insert']['image'])) {
                $htmlContent .= '<img src="' . htmlspecialchars($op['insert']['image']) . '">';
            } elseif (isset($op['insert'])) {
                $htmlContent .= htmlspecialchars($op['insert']);
            }
        }
    }

    // Save the content to a file or database
    file_put_contents('saved_content.html', $htmlContent);

    echo "Content saved successfully!";
}
?>

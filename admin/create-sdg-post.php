<?php
/**
 * UPHSL Admin Create SDG Initiative Post
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for creating new SDG initiative posts
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in and has appropriate role
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Create SDG Initiative Post';

$error = '';
$success = '';
$isEdit = false;
$post = null;

// SDG Initiatives data
$sdgGoals = [
    1 => 'No Poverty',
    2 => 'Zero Hunger',
    3 => 'Good Health and Well-being',
    4 => 'Quality Education',
    5 => 'Gender Equality',
    6 => 'Clean Water and Sanitation',
    7 => 'Affordable and Clean Energy',
    8 => 'Decent Work and Economic Growth',
    9 => 'Industry, Innovation and Infrastructure',
    10 => 'Reduced Inequalities',
    11 => 'Sustainable Cities and Communities',
    12 => 'Responsible Consumption and Production',
    13 => 'Climate Action',
    14 => 'Life Below Water',
    15 => 'Life on Land',
    16 => 'Peace, Justice and Strong Institutions',
    17 => 'Partnerships for the Goals'
];

// Check if this is an edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $isEdit = true;
    $postId = (int)$_GET['edit'];
    
    // Get the post data
    $pdo = getDBConnection();
    
    // Different query based on user role
    if (isAuthor()) {
        // Authors can only edit their own posts
        $stmt = $pdo->prepare("SELECT * FROM sdg_initiatives_posts WHERE id = ? AND author_id = ?");
        $stmt->execute([$postId, $_SESSION['user_id']]);
    } else {
        // Admins and Super Admins can edit any post
        $stmt = $pdo->prepare("SELECT * FROM sdg_initiatives_posts WHERE id = ?");
        $stmt->execute([$postId]);
    }
    
    $post = $stmt->fetch();
    
    if (!$post) {
        $error = 'Post not found or you do not have permission to edit it';
        $isEdit = false;
    } else {
        $page_title = 'Edit SDG Initiative Post';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
    error_log("SDG Form submitted - POST data: " . print_r($_POST, true));
    error_log("SDG Form submitted - FILES data: " . print_r($_FILES, true));
    
        $title = Validator::sanitize($_POST['title'], 'string');
        $content = $_POST['content']; // Rich text content - sanitized on output
        $status = Validator::sanitize($_POST['status'] ?? 'draft', 'string');
        $excerpt = Validator::sanitize($_POST['excerpt'] ?? '', 'string');
        $publishedDate = Validator::sanitize($_POST['published_date'] ?? null, 'string');
    $sdgNumber = (int)$_POST['sdg_number'];
    $sdgTitle = $sdgGoals[$sdgNumber] ?? '';
    $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if (empty($title) || empty($content) || $sdgNumber < 1 || $sdgNumber > 17) {
        $error = 'Please fill in all required fields and select a valid SDG goal';
    } else {
        $pdo = getDBConnection();
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            error_log("SDG Transaction started");
            
            if ($isEdit && $postId > 0) {
                // Update existing post - different query based on user role
                if (isAuthor()) {
                    // Authors can only update their own posts
                    $stmt = $pdo->prepare("
                        UPDATE sdg_initiatives_posts 
                        SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?, sdg_number = ?, sdg_title = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ? AND author_id = ?
                    ");
                    $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $sdgNumber, $sdgTitle, $postId, $_SESSION['user_id']]);
                } else {
                    // Admins and Super Admins can update any post
                    $stmt = $pdo->prepare("
                        UPDATE sdg_initiatives_posts 
                        SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?, sdg_number = ?, sdg_title = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $sdgNumber, $sdgTitle, $postId]);
                }
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Post not found or you do not have permission to edit it');
                }
            } else {
                // Create new post
                $slug = generateUniqueSlug($title, 'sdg_initiatives_posts');
                $stmt = $pdo->prepare("
                    INSERT INTO sdg_initiatives_posts (title, slug, content, excerpt, status, published_at, sdg_number, sdg_title, author_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $content, $excerpt, $status, $publishedDate, $sdgNumber, $sdgTitle, $_SESSION['user_id']]);
                $postId = $pdo->lastInsertId();
            }
            
            // Handle deletion of existing images
            if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $imageId) {
                    // Get image path before deleting
                    $stmt = $pdo->prepare("SELECT image_path FROM sdg_initiatives_images WHERE id = ? AND post_id = ?");
                    $stmt->execute([$imageId, $postId]);
                    $image = $stmt->fetch();
                    
                    if ($image) {
                        // Delete from database
                        $stmt = $pdo->prepare("DELETE FROM sdg_initiatives_images WHERE id = ?");
                        $stmt->execute([$imageId]);
                        
                        // Delete file from server
                        if (file_exists($image['image_path'])) {
                            unlink($image['image_path']);
                        }
                    }
                }
            }
            
            // Handle image uploads
            error_log("SDG Image upload debug - FILES array: " . print_r($_FILES, true));
            if (!empty($_FILES['images']['name'][0])) {
                error_log("SDG Image upload: Processing " . count($_FILES['images']['name']) . " files");
                $uploadDir = dirname(__DIR__) . '/uploads/';
                error_log("Current working directory: " . getcwd());
                error_log("Upload directory path: " . $uploadDir);
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                    error_log("Created upload directory: $uploadDir");
                }
                
                $uploadedImages = [];
                $imageCount = count($_FILES['images']['name']);
                
                for ($i = 0; $i < $imageCount; $i++) {
                    error_log("Processing SDG image $i: " . $_FILES['images']['name'][$i] . " (error: " . $_FILES['images']['error'][$i] . ")");
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = $_FILES['images']['name'][$i];
                        $fileTmpName = $_FILES['images']['tmp_name'][$i];
                        $fileSize = $_FILES['images']['size'][$i];
                        $fileType = $_FILES['images']['type'][$i];
                        
                        // Validate file extension
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!in_array($fileExtension, $allowedExtensions)) {
                            throw new Exception("Invalid file extension for $fileName. Only JPEG, PNG, GIF, and WebP are allowed.");
                        }
                        
                        // Validate file size (10MB max)
                        if ($fileSize > 10 * 1024 * 1024) {
                            throw new Exception("File $fileName is too large. Maximum size is 10MB.");
                        }
                        
                        // Verify actual file content using getimagesize (more secure than trusting MIME type)
                        $imageInfo = @getimagesize($fileTmpName);
                        if ($imageInfo === false) {
                            throw new Exception("File $fileName is not a valid image.");
                        }
                        
                        // Verify MIME type matches actual file content
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        $detectedMime = $imageInfo['mime'];
                        if (!in_array($detectedMime, $allowedTypes)) {
                            throw new Exception("Invalid file type detected for $fileName. Only JPEG, PNG, GIF, and WebP are allowed.");
                        }
                        
                        // Generate unique filename
                        $uniqueFileName = 'sdg_' . uniqid() . '_' . time() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $uniqueFileName;
                        
                        // Ensure filename doesn't contain path traversal
                        $uploadPath = realpath($uploadDir) . '/' . basename($uniqueFileName);
                        
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            error_log("SDG Image uploaded successfully: $uploadPath");
                            // Optimize image for better performance
                            optimizeImage($uploadPath, $detectedMime);
                            // Store relative path in database (from root directory)
                            $relativePath = 'uploads/' . $uniqueFileName;
                            // Insert image record
                            $imageStmt = $pdo->prepare("
                                INSERT INTO sdg_initiatives_images (post_id, image_path, sort_order) 
                                VALUES (?, ?, ?)
                            ");
                            $imageStmt->execute([$postId, $relativePath, $i]);
                            $uploadedImages[] = $relativePath;
                            error_log("SDG Image record inserted into database with path: $relativePath");
                        } else {
                            error_log("Failed to move uploaded SDG file: $fileTmpName to $uploadPath");
                        }
                    }
                }
                
                // Set featured image (first uploaded image)
                if (!empty($uploadedImages)) {
                    $featuredStmt = $pdo->prepare("
                        UPDATE sdg_initiatives_posts SET featured_image = ? WHERE id = ?
                    ");
                    $featuredStmt->execute([$uploadedImages[0], $postId]);
                }
            }
            
            // Commit transaction
            $pdo->commit();
            error_log("SDG Transaction committed successfully");
            
            // Set success message and redirect to SDG initiatives management
            if ($isEdit) {
                $successMsg = urlencode('SDG Initiative post updated successfully!');
            } else {
                $successMsg = urlencode('SDG Initiative post created successfully!');
            }
            
            // Redirect to SDG initiatives management with success message
            header('Location: sdg-initiatives.php?success=' . $successMsg);
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Exception in SDG post creation: " . $e->getMessage());
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("PDO Exception in SDG post creation: " . $e->getMessage());
            $error = 'Failed to create SDG initiative post. Please try again.';
        }
        }
    }
}
?>

<?php 
// For create-sdg-post, we need to include editor.css as well
$additional_css = '<link rel="stylesheet" href="../assets/css/editor.css">';
?>
<?php include '../app/includes/admin-header.php'; ?>

    <!-- Editor Content -->
    <div class="editor-container">
        <div class="editor-header">
            <h1 class="editor-title">
                <i class="fas fa-globe-americas"></i>
                <?php echo $isEdit ? 'Edit SDG Initiative Post' : 'Create New SDG Initiative Post'; ?>
            </h1>
            <p class="editor-subtitle"><?php echo $isEdit ? 'Update your SDG initiative content' : 'Share our sustainable development initiatives'; ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="editor-form" enctype="multipart/form-data">
            <?php echo CSRF::field(); ?>
            <?php if ($isEdit): ?>
                <input type="hidden" name="is_edit" value="1">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fas fa-heading"></i>
                    Post Title
                </label>
                <input type="text" id="title" name="title" class="form-input" 
                       value="<?php echo $isEdit ? htmlspecialchars($post['title']) : (isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''); ?>" 
                       placeholder="Enter your SDG initiative post title..." required>
            </div>

            <div class="form-group">
                <label for="sdg_number" class="form-label">
                    <i class="fas fa-globe"></i>
                    SDG Initiative
                </label>
                <select id="sdg_number" name="sdg_number" class="form-input" required onchange="updateSdgTitle()">
                    <option value="">Select an SDG Initiative</option>
                    <?php foreach ($sdgGoals as $number => $title): ?>
                        <option value="<?php echo $number; ?>" 
                                <?php echo ($isEdit && $post['sdg_number'] == $number) || (isset($_POST['sdg_number']) && $_POST['sdg_number'] == $number) ? 'selected' : ''; ?>>
                            SDG <?php echo $number; ?>: <?php echo $title; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="excerpt" class="form-label">
                    <i class="fas fa-quote-left"></i>
                    Excerpt (Optional)
                </label>
                <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3"
                          placeholder="Write a brief summary of your SDG initiative..."><?php echo $isEdit ? htmlspecialchars($post['excerpt']) : (isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Content
                </label>
                <!-- Quill Editor Container -->
                <div id="content-editor"></div>
                <!-- Hidden textarea for form submission -->
                <textarea id="content" name="content" class="form-textarea" required><?php 
                    // For Quill, we need to decode HTML entities to show the actual HTML
                    if ($isEdit && isset($post['content'])) {
                        // Decode HTML entities so Quill can properly display the formatted content
                        echo html_entity_decode($post['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    } elseif (isset($_POST['content'])) {
                        echo html_entity_decode($_POST['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    }
                ?></textarea>
                <small class="form-help" style="display: block; margin-top: 8px; color: #6b7280; font-size: 0.875rem;">
                    <i class="fas fa-info-circle"></i> Use the formatting toolbar above to add <strong>bold</strong>, <em>italic</em>, and other text formatting.
                </small>
            </div>

            <div class="form-group">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Attach Images (Optional)
                </label>
                <div class="image-upload-container">
                    <input type="file" id="images" name="images[]" class="image-input" 
                           multiple accept="image/*" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="image-upload-area" onclick="document.getElementById('images').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to select images or drag and drop</p>
                        <small>Supported formats: JPEG, PNG, GIF, WebP (Max 10MB each)</small>
                    </div>
                    <div id="image-preview" class="image-preview">
                        <?php if ($isEdit && $post): ?>
                            <?php
                            // Get existing images for this post
                            $stmt = $pdo->prepare("SELECT * FROM sdg_initiatives_images WHERE post_id = ?");
                            $stmt->execute([$post['id']]);
                            $existingImages = $stmt->fetchAll();
                            ?>
                            <?php foreach ($existingImages as $image): ?>
                                <div class="image-preview-item existing-image">
                                    <img src="../<?php echo htmlspecialchars($image['image_path']); ?>" alt="Existing image">
                                    <button type="button" class="remove-image" onclick="removeExistingImage(<?php echo $image['id']; ?>)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <div class="image-info">
                                        <?php echo htmlspecialchars(basename($image['image_path'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-eye"></i>
                        Status
                    </label>
                    <select id="status" name="status" class="form-input">
                        <option value="draft" <?php echo ($isEdit && $post['status'] === 'draft') || (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo ($isEdit && $post['status'] === 'published') || (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="published_date" class="form-label">
                        <i class="fas fa-calendar"></i>
                        Publish Date
                    </label>
                    <input type="datetime-local" id="published_date" name="published_date" class="form-input"
                           value="<?php 
                               if ($isEdit && $post['published_at']) {
                                   echo date('Y-m-d\TH:i', strtotime($post['published_at']));
                               } elseif (isset($_POST['published_date'])) {
                                   echo htmlspecialchars($_POST['published_date']);
                               } else {
                                   echo date('Y-m-d\TH:i');
                               }
                           ?>">
                </div>
            </div>

            <div class="form-actions">
                <a href="sdg-initiatives.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $isEdit ? 'Update SDG Post' : 'Create SDG Post'; ?>
                </button>
            </div>
        </form>
    </div>

    <script src="../assets/js/script.js"></script>
    <!-- Quill Rich Text Editor (Free, No API Key Required) -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <style>
        #content-editor {
            height: 400px;
            margin-bottom: 20px;
        }
        .ql-editor {
            min-height: 350px;
            font-family: -apple-system, BlinkMacSystemFont, 'San Francisco', 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            font-size: 14px;
        }
        /* Normalize paragraph spacing in Quill editor */
        .ql-editor p {
            margin-bottom: 1rem;
            margin-top: 0;
        }
        .ql-editor p:empty {
            display: none;
            margin: 0;
            padding: 0;
        }
        /* Hide the original textarea, we'll sync with it */
        #content {
            display: none;
        }
    </style>
    <script>
        // Initialize Quill Editor when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Quill Editor
            var quill = new Quill('#content-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link', 'blockquote', 'code-block'],
                        ['clean']
                    ]
                },
                placeholder: 'Write your SDG initiative content here...'
            });

            // Get initial content from textarea
            var textarea = document.getElementById('content');
            if (textarea && textarea.value) {
                quill.root.innerHTML = textarea.value;
            }

            // Function to normalize text spacing and remove irregular whitespace
            function normalizeTextSpacing(text) {
                // Replace non-breaking spaces (and other unicode spaces) with regular spaces
                text = text.replace(/[\u00A0\u2000-\u200B\u202F\u205F\u3000]/g, ' ');
                // Replace tabs with spaces
                text = text.replace(/\t/g, ' ');
                // Replace single line breaks (not double line breaks) with spaces
                // This converts line breaks within sentences to spaces
                text = text.replace(/([^\n])\n([^\n])/g, '$1 $2');
                // Replace multiple consecutive spaces with single space
                text = text.replace(/[ ]{2,}/g, ' ');
                // Remove spaces at start/end
                text = text.trim();
                return text;
            }

            // Function to normalize paragraph spacing in HTML
            function normalizeParagraphSpacing(html) {
                // Replace non-breaking spaces with regular spaces
                html = html.replace(/\u00A0/g, ' ');
                // Replace tabs with spaces
                html = html.replace(/\t/g, ' ');
                // Remove empty paragraphs
                html = html.replace(/<p[^>]*>\s*<\/p>/gi, '');
                // Remove multiple consecutive <br> tags
                html = html.replace(/(<br\s*\/?>){2,}/gi, '<br>');
                // Remove <br> tags at the start or end of paragraphs
                html = html.replace(/<p[^>]*>(\s*<br\s*\/?>\s*)+/gi, '<p>');
                html = html.replace(/(\s*<br\s*\/?>\s*)+<\/p>/gi, '</p>');
                // Normalize whitespace in text nodes - replace multiple spaces with single space
                // BUT preserve single spaces (don't trim them)
                html = html.replace(/(?<=>)([^<]+)(?=<)/g, function(match) {
                    // Normalize all types of whitespace
                    match = match.replace(/[\u00A0\u2000-\u200B\u202F\u205F\u3000]/g, ' '); // Unicode spaces
                    match = match.replace(/\t/g, ' '); // Tabs
                    match = match.replace(/[ ]{2,}/g, ' '); // Multiple spaces to single space
                    // DON'T trim - preserve leading/trailing single spaces
                    return match;
                });
                // Normalize whitespace in paragraphs - only remove excessive whitespace
                html = html.replace(/<p[^>]*>(\s{2,})/gi, '<p>');
                html = html.replace(/(\s{2,})<\/p>/gi, '</p>');
                return html;
            }

            // Simple paste handler - intercept paste, normalize, and insert clean text
            quill.root.addEventListener('paste', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get plain text from clipboard
                var clipboardData = e.clipboardData || window.clipboardData;
                var pastedText = clipboardData.getData('text/plain');
                
                if (pastedText) {
                    // Normalize spacing - remove extra spaces
                    pastedText = normalizeTextSpacing(pastedText);
                    
                    // Get current selection
                    var selection = quill.getSelection(true);
                    if (!selection) {
                        selection = { index: quill.getLength(), length: 0 };
                    }
                    
                    // Delete selected text if any
                    if (selection.length > 0) {
                        quill.deleteText(selection.index, selection.length, 'user');
                    }
                    
                    // Insert normalized text as plain text (no formatting)
                    // Use null format to ensure it uses default editor formatting
                    quill.insertText(selection.index, pastedText, null, 'user');
                    
                    // Remove any formatting that might have been applied
                    quill.formatText(selection.index, pastedText.length, {
                        color: false,
                        background: false,
                        font: false,
                        size: false
                    }, 'user');
                    
                    // Move cursor to end of inserted text
                    quill.setSelection(selection.index + pastedText.length, 0, 'user');
                    
                    // Sync to textarea
                    textarea.value = quill.root.innerHTML;
                }
            });

            // Sync Quill content to textarea before form submission
            var form = document.querySelector('.editor-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Get HTML content from Quill
                    var htmlContent = quill.root.innerHTML;
                    // Normalize paragraph spacing
                    htmlContent = normalizeParagraphSpacing(htmlContent);
                    // Update the hidden textarea with the normalized HTML content
                    textarea.value = htmlContent;
                });
            }

            // Sync on content change
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
        });
        
        // Update SDG title when number changes
        function updateSdgTitle() {
            const sdgNumber = document.getElementById('sdg_number').value;
            const sdgGoals = <?php echo json_encode($sdgGoals); ?>;
            if (sdgNumber && sdgGoals[sdgNumber]) {
                // You can add logic here to update any display elements if needed
                console.log('Selected SDG:', sdgNumber, sdgGoals[sdgNumber]);
            }
        }
        
        // Image upload and preview functionality (same as create-post.php)
        function initImageUpload() {
            const imageInput = document.getElementById('images');
            const previewContainer = document.getElementById('image-preview');
            
            if (!imageInput || !previewContainer) {
                console.error('Image input or preview container not found');
                return;
            }
            
            imageInput.addEventListener('change', function(e) {
                console.log('SDG Image input changed');
                const files = Array.from(e.target.files);
                console.log('Files selected:', files.length);
                
                // Clear existing previews (but keep existing images)
                const existingImages = previewContainer.querySelectorAll('.existing-image');
                previewContainer.innerHTML = '';
                
                // Re-add existing images
                existingImages.forEach(img => previewContainer.appendChild(img));
                
                if (files.length === 0) {
                    return;
                }
                
                files.forEach((file, index) => {
                    console.log('Processing SDG file:', file.name, file.type);
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('SDG File read successfully:', file.name);
                            const previewItem = document.createElement('div');
                            previewItem.className = 'image-preview-item';
                            previewItem.innerHTML = `
                                <img src="${e.target.result}" alt="Preview">
                                <button type="button" class="remove-image" onclick="removeImage(${index})">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="image-info">
                                    ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)
                                </div>
                            `;
                            previewContainer.appendChild(previewItem);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        console.log('File is not an image:', file.name);
                    }
                });
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initImageUpload);
        } else {
            initImageUpload();
        }
        
        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.querySelector('.image-upload-area');
            if (uploadArea) {
                uploadArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#2563eb';
                    uploadArea.style.background = '#eff6ff';
                });
                
                uploadArea.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.background = '#f8fafc';
                });
                
                uploadArea.addEventListener('drop', function(e) {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#cbd5e1';
                    uploadArea.style.background = '#f8fafc';
                    
                    const files = Array.from(e.dataTransfer.files);
                    const fileInput = document.getElementById('images');
                    
                    if (fileInput) {
                        // Create a new FileList
                        const dt = new DataTransfer();
                        files.forEach(file => dt.items.add(file));
                        fileInput.files = dt.files;
                        
                        // Trigger change event
                        fileInput.dispatchEvent(new Event('change'));
                    }
                });
            }
        });
        
        function removeImage(index) {
            const fileInput = document.getElementById('images');
            const dt = new DataTransfer();
            
            // Recreate FileList without the removed file
            Array.from(fileInput.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            fileInput.files = dt.files;
            
            // Refresh preview
            fileInput.dispatchEvent(new Event('change'));
        }
        
        function removeExistingImage(imageId) {
            if (confirm('Are you sure you want to remove this image?')) {
                // Create a hidden input to mark this image for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_images[]';
                deleteInput.value = imageId;
                document.querySelector('form').appendChild(deleteInput);
                
                // Remove the preview item
                const previewItem = event.target.closest('.image-preview-item');
                previewItem.remove();
            }
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>


<?php
/**
 * UPHSL Admin Create Post
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for creating new blog posts and news articles
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and has appropriate role
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Create Post';

$error = '';
$success = '';
$isEdit = false;
$post = null;

// Get all categories from database
$pdo = getDBConnection();
$allCategories = getAllCategories();

// Organize categories by type for display
$programCategories = [];
$supportServiceCategories = [];

$programNames = [
    'Senior High School', 'Junior High School', 'Grade School',
    'Aviation', 'Arts & Sciences', 'Business & Accountancy', 'Computer Studies',
    'Criminology', 'Education', 'Engineering & Architecture',
    'International Hospitality Management', 'Maritime', 'Law/Juris Doctor', 'Graduate School'
];

$supportServiceNames = [
    'Careers', 'University Clinic', 'Community Outreach Department',
    'International & External Affairs', 'Student Personnel Services',
    'Library', 'Quality Assurance', 'Research'
];

// Use category name as key to prevent duplicates
$programCategoriesMap = [];
$supportServiceCategoriesMap = [];

foreach ($allCategories as $category) {
    $categoryName = $category['name'];
    if (in_array($categoryName, $programNames)) {
        // Only add if we haven't seen this category name before
        if (!isset($programCategoriesMap[$categoryName])) {
            $programCategoriesMap[$categoryName] = $category;
        }
    } elseif (in_array($categoryName, $supportServiceNames)) {
        // Only add if we haven't seen this category name before
        if (!isset($supportServiceCategoriesMap[$categoryName])) {
            $supportServiceCategoriesMap[$categoryName] = $category;
        }
    }
}

// Convert maps back to arrays
$programCategories = array_values($programCategoriesMap);
$supportServiceCategories = array_values($supportServiceCategoriesMap);

// Check if this is an edit request
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $isEdit = true;
    $postId = (int)$_GET['edit'];
    
    // Get the post data
    $pdo = getDBConnection();
    
    // Different query based on user role
    if (isAuthor()) {
        // Authors can only edit their own posts
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND author_id = ?");
        $stmt->execute([$postId, $_SESSION['user_id']]);
    } else {
        // Admins and Super Admins can edit any post
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$postId]);
    }
    
    $post = $stmt->fetch();
    
    if (!$post) {
        $error = 'Post not found or you do not have permission to edit it';
        $isEdit = false;
    } else {
        $page_title = 'Edit Post';
        // category_id is already in the post data from the SELECT query
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form submitted - POST data: " . print_r($_POST, true));
    error_log("Form submitted - FILES data: " . print_r($_FILES, true));
    
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content'];
    $status = $_POST['status'];
    $excerpt = sanitizeInput($_POST['excerpt'] ?? '');
    $publishedDate = $_POST['published_date'] ?? null;
    $categoryId = isset($_POST['category']) && is_numeric($_POST['category']) ? (int)$_POST['category'] : null;
    $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';
    $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields';
    } else {
        $pdo = getDBConnection();
        
        // Check if images are provided (for new posts) or exist (for edited posts)
        $hasImages = false;
        if ($isEdit && $postId > 0) {
            // For edits, check if post already has images
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_images WHERE post_id = ?");
            $stmt->execute([$postId]);
            $result = $stmt->fetch();
            $hasImages = ($result['count'] > 0) || !empty($_FILES['images']['name'][0]);
        } else {
            // For new posts, images must be uploaded
            $hasImages = !empty($_FILES['images']['name'][0]);
        }
        
        if (!$hasImages) {
            $error = 'Please attach at least one image. Images are required for posts.';
        } else {
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            error_log("Transaction started");
            
            if ($isEdit && $postId > 0) {
                // Update existing post - different query based on user role
                if (isAuthor()) {
                    // Authors can only update their own posts
                    $stmt = $pdo->prepare("
                        UPDATE posts 
                        SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ? AND author_id = ?
                    ");
                    $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $categoryId, $postId, $_SESSION['user_id']]);
                } else {
                    // Admins and Super Admins can update any post
                    $stmt = $pdo->prepare("
                        UPDATE posts 
                        SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?, category_id = ?, updated_at = CURRENT_TIMESTAMP 
                        WHERE id = ?
                    ");
                    $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $categoryId, $postId]);
                }
                
                if ($stmt->rowCount() === 0) {
                    throw new Exception('Post not found or you do not have permission to edit it');
                }
            } else {
                // Create new post
                $slug = generateUniqueSlug($title);
                $stmt = $pdo->prepare("
                    INSERT INTO posts (title, slug, content, excerpt, status, published_at, category_id, author_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$title, $slug, $content, $excerpt, $status, $publishedDate, $categoryId, $_SESSION['user_id']]);
                $postId = $pdo->lastInsertId();
            }
            
            // Check current image count before deletion
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_images WHERE post_id = ?");
            $stmt->execute([$postId]);
            $currentImageCount = $stmt->fetch()['count'];
            $deletingCount = isset($_POST['delete_images']) && is_array($_POST['delete_images']) ? count($_POST['delete_images']) : 0;
            $uploadingCount = !empty($_FILES['images']['name'][0]) ? count(array_filter($_FILES['images']['name'])) : 0;
            
            // Validate that at least one image will remain after deletion
            if ($isEdit && ($currentImageCount - $deletingCount + $uploadingCount) < 1) {
                throw new Exception('At least one image is required. Please keep existing images or upload new ones.');
            }
            
            // Handle deletion of existing images
            if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
                foreach ($_POST['delete_images'] as $imageId) {
                    // Get image path before deleting
                    $stmt = $pdo->prepare("SELECT image_path FROM post_images WHERE id = ? AND post_id = ?");
                    $stmt->execute([$imageId, $postId]);
                    $image = $stmt->fetch();
                    
                    if ($image) {
                        // Delete from database
                        $stmt = $pdo->prepare("DELETE FROM post_images WHERE id = ?");
                        $stmt->execute([$imageId]);
                        
                        // Delete file from server
                        if (file_exists($image['image_path'])) {
                            unlink($image['image_path']);
                        }
                    }
                }
            }
            
            // Handle image uploads
            error_log("Image upload debug - FILES array: " . print_r($_FILES, true));
            if (!empty($_FILES['images']['name'][0])) {
                error_log("Image upload: Processing " . count($_FILES['images']['name']) . " files");
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
                    error_log("Processing image $i: " . $_FILES['images']['name'][$i] . " (error: " . $_FILES['images']['error'][$i] . ")");
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileName = $_FILES['images']['name'][$i];
                        $fileTmpName = $_FILES['images']['tmp_name'][$i];
                        $fileSize = $_FILES['images']['size'][$i];
                        $fileType = $_FILES['images']['type'][$i];
                        
                        // Validate file type
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                        if (!in_array($fileType, $allowedTypes)) {
                            throw new Exception("Invalid file type for $fileName. Only JPEG, PNG, GIF, and WebP are allowed.");
                        }
                        
                        // Validate file size (10MB max)
                        if ($fileSize > 10 * 1024 * 1024) {
                            throw new Exception("File $fileName is too large. Maximum size is 10MB.");
                        }
                        
                        // Generate unique filename
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $uniqueFileName;
                        
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            error_log("Image uploaded successfully: $uploadPath");
                            // Optimize image for better performance
                            optimizeImage($uploadPath, $fileType);
                            // Store relative path in database (from root directory)
                            $relativePath = 'uploads/' . $uniqueFileName;
                            // Insert image record
                            $imageStmt = $pdo->prepare("
                                INSERT INTO post_images (post_id, image_path, sort_order) 
                                VALUES (?, ?, ?)
                            ");
                            $imageStmt->execute([$postId, $relativePath, $i]);
                            $uploadedImages[] = $relativePath;
                            error_log("Image record inserted into database with path: $relativePath");
                        } else {
                            error_log("Failed to move uploaded file: $fileTmpName to $uploadPath");
                        }
                    }
                }
                
                // Set featured image (first uploaded image)
                if (!empty($uploadedImages)) {
                    $featuredStmt = $pdo->prepare("
                        UPDATE posts SET featured_image = ? WHERE id = ?
                    ");
                    $featuredStmt->execute([$uploadedImages[0], $postId]);
                }
            }
            
            // Commit transaction
            $pdo->commit();
            error_log("Transaction committed successfully");
            
            if ($isEdit) {
                setFlashMessage('success', 'Post updated successfully!');
            } else {
                setFlashMessage('success', 'Post created successfully!');
            }
            
            // Redirect based on user role
            if (isAuthor()) {
                redirect('author-dashboard.php');
            } else {
                redirect('dashboard.php');
            }
            
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Exception in post creation: " . $e->getMessage());
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("PDO Exception in post creation: " . $e->getMessage());
            $error = 'Failed to create post. Please try again.';
            }
        }
    }
}
?>

<?php 
// For create-post, we need to include editor.css as well
$additional_css = '<link rel="stylesheet" href="../assets/css/editor.css">';
?>
<?php include '../app/includes/admin-header.php'; ?>

    <!-- Editor Content -->
    <div class="editor-container">
        <div class="editor-header">
            <h1 class="editor-title">
                <i class="fas fa-edit"></i>
                <?php echo $isEdit ? 'Edit Post' : 'Create New Post'; ?>
            </h1>
            <p class="editor-subtitle"><?php echo $isEdit ? 'Update your post content' : 'Share your thoughts with the world'; ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="editor-form" enctype="multipart/form-data">
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
                       placeholder="Enter your post title..." required>
            </div>

            <div class="form-group">
                <label for="category" class="form-label">
                    <i class="fas fa-tags"></i>
                    Category (Program/Support Service)
                </label>
                <select id="category" name="category" class="form-input">
                    <option value="">Select a Category (Optional)</option>
                    <?php
                    // Get the current category ID for edit mode
                    $currentCategoryId = null;
                    if ($isEdit && isset($post['category_id']) && !empty($post['category_id'])) {
                        $currentCategoryId = (int)$post['category_id'];
                    } elseif (isset($_POST['category']) && is_numeric($_POST['category'])) {
                        $currentCategoryId = (int)$_POST['category'];
                    }
                    ?>
                    <optgroup label="Programs - Basic Education">
                        <?php foreach ($programCategories as $cat): 
                            if (in_array($cat['name'], ['Senior High School', 'Junior High School', 'Grade School'])): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($currentCategoryId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endif;
                        endforeach; ?>
                    </optgroup>
                    <optgroup label="Programs - Other">
                        <?php foreach ($programCategories as $cat): 
                            if (!in_array($cat['name'], ['Senior High School', 'Junior High School', 'Grade School'])): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($currentCategoryId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endif;
                        endforeach; ?>
                    </optgroup>
                    <optgroup label="Support Services">
                        <?php foreach ($supportServiceCategories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($currentCategoryId === (int)$cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
                <small class="form-help">Select the program or support service this post relates to. Posts will be displayed on their respective pages.</small>
            </div>

            <div class="form-group">
                <label for="excerpt" class="form-label">
                    <i class="fas fa-quote-left"></i>
                    Excerpt (Optional)
                </label>
                <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3"
                          placeholder="Write a brief summary of your post..."><?php echo $isEdit ? htmlspecialchars($post['excerpt']) : (isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Content
                </label>
                <textarea id="content" name="content" class="form-textarea" 
                          placeholder="Write your post content here..." required><?php echo $isEdit ? htmlspecialchars($post['content']) : (isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="images" class="form-label">
                    <i class="fas fa-images"></i>
                    Attach Images <span style="color: red;">*</span>
                </label>
                <div class="image-upload-container">
                    <input type="file" id="images" name="images[]" class="image-input" 
                           multiple accept="image/*" accept="image/jpeg,image/png,image/gif,image/webp" <?php echo !$isEdit ? 'required' : ''; ?>>
                    <div class="image-upload-area" onclick="document.getElementById('images').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to select images or drag and drop</p>
                        <small>Supported formats: JPEG, PNG, GIF, WebP (Max 10MB each). <strong>At least one image is required.</strong></small>
                    </div>
                    <div id="image-preview" class="image-preview">
                        <?php if ($isEdit && $post): ?>
                            <?php
                            // Get existing images for this post
                            $stmt = $pdo->prepare("SELECT * FROM post_images WHERE post_id = ?");
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
                <a href="<?php echo isAuthor() ? 'author-dashboard.php' : 'dashboard.php'; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <?php echo $isEdit ? 'Update Post' : 'Create Post'; ?>
                </button>
            </div>
        </form>
    </div>

        <script src="../assets/js/script.js"></script>
    <script>
        // Form validation - ensure images are provided
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.editor-form');
            const imageInput = document.getElementById('images');
            const imagePreview = document.getElementById('image-preview');
            const isEdit = <?php echo $isEdit ? 'true' : 'false'; ?>;
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    const hasExistingImages = imagePreview && imagePreview.querySelectorAll('.existing-image').length > 0;
                    const hasNewImages = imageInput && imageInput.files && imageInput.files.length > 0;
                    
                    if (!hasExistingImages && !hasNewImages) {
                        e.preventDefault();
                        alert('Please attach at least one image. Images are required for posts.');
                        imageInput.focus();
                        return false;
                    }
                });
            }
        });
        
        // Image upload and preview functionality
        function initImageUpload() {
            const imageInput = document.getElementById('images');
            const previewContainer = document.getElementById('image-preview');
            
            if (!imageInput) {
                console.error('Image input not found');
                return;
            }
            
            if (!previewContainer) {
                console.error('Preview container not found');
                return;
            }
            
            imageInput.addEventListener('change', function(e) {
                console.log('Image input changed');
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
                    console.log('Processing file:', file.name, file.type);
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('File read successfully:', file.name);
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
            const imagePreview = document.getElementById('image-preview');
            const imageInput = document.getElementById('images');
            const existingImages = imagePreview.querySelectorAll('.existing-image');
            const newImages = imageInput && imageInput.files && imageInput.files.length > 0;
            
            // Check if removing this image would leave no images
            if (existingImages.length === 1 && !newImages) {
                alert('Cannot remove the last image. At least one image is required for posts. Please upload a new image before removing this one.');
                return;
            }
            
            if (confirm('Are you sure you want to remove this image?')) {
                // Create a hidden input to mark this image for deletion
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_images[]';
                deleteInput.value = imageId;
                document.querySelector('form').appendChild(deleteInput);
                
                // Remove the preview item
                const previewItem = event.target.closest('.image-preview-item');
                if (previewItem) {
                    previewItem.remove();
                }
            }
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>


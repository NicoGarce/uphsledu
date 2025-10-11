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

// Check if user is logged in and is author or admin
if (!isLoggedIn() || !isAuthor()) {
    redirect('../auth/login.php');
}

$error = '';
$success = '';

// Set base path for assets
$base_path = '../';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = $_POST['content'];
    $status = $_POST['status'];
    $excerpt = sanitizeInput($_POST['excerpt'] ?? '');
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields';
    } else {
        $pdo = getDBConnection();
        $slug = generateUniqueSlug($title);
        
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Insert the post
            $stmt = $pdo->prepare("
                INSERT INTO posts (title, slug, content, excerpt, status, author_id) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $slug, $content, $excerpt, $status, $_SESSION['user_id']]);
            $postId = $pdo->lastInsertId();
            
            // Handle image uploads
            if (!empty($_FILES['images']['name'][0])) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $uploadedImages = [];
                $imageCount = count($_FILES['images']['name']);
                
                for ($i = 0; $i < $imageCount; $i++) {
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
                        
                        // Validate file size (5MB max)
                        if ($fileSize > 5 * 1024 * 1024) {
                            throw new Exception("File $fileName is too large. Maximum size is 5MB.");
                        }
                        
                        // Generate unique filename
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $uniqueFileName = uniqid() . '_' . time() . '.' . $fileExtension;
                        $uploadPath = $uploadDir . $uniqueFileName;
                        
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            // Insert image record
                            $imageStmt = $pdo->prepare("
                                INSERT INTO post_images (post_id, image_path, sort_order) 
                                VALUES (?, ?, ?)
                            ");
                            $imageStmt->execute([$postId, $uploadPath, $i]);
                            $uploadedImages[] = $uploadPath;
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
            
            setFlashMessage('success', 'Post created successfully!');
            redirect('dashboard.php');
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Failed to create post. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - My Blog</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/editor.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <a href="../">
                        <img src="../assets/images/logos/logo.png" alt="University of Perpetual Help System" class="logo-img">
                </a>
            </div>
            <div class="nav-menu">
                <a href="../" class="nav-link">Home</a>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="create-post.php" class="nav-link active">Create Post</a>
                <?php if (isAdmin()): ?>
                    <a href="users.php" class="nav-link">Users</a>
                <?php endif; ?>
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="nav-link">Account Management</a>
                <?php endif; ?>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?></span>
                <a href="../auth/logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Editor Content -->
    <div class="editor-container">
        <div class="editor-header">
            <h1 class="editor-title">
                <i class="fas fa-edit"></i>
                Create New Post
            </h1>
            <p class="editor-subtitle">Share your thoughts with the world</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="editor-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title" class="form-label">
                    <i class="fas fa-heading"></i>
                    Post Title
                </label>
                <input type="text" id="title" name="title" class="form-input" 
                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" 
                       placeholder="Enter your post title..." required>
            </div>

            <div class="form-group">
                <label for="excerpt" class="form-label">
                    <i class="fas fa-quote-left"></i>
                    Excerpt (Optional)
                </label>
                <textarea id="excerpt" name="excerpt" class="form-textarea" rows="3"
                          placeholder="Write a brief summary of your post..."><?php echo isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="content" class="form-label">
                    <i class="fas fa-align-left"></i>
                    Content
                </label>
                <textarea id="content" name="content" class="form-textarea" 
                          placeholder="Write your post content here..." required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
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
                        <small>Supported formats: JPEG, PNG, GIF, WebP (Max 5MB each)</small>
                    </div>
                    <div id="image-preview" class="image-preview"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="status" class="form-label">
                        <i class="fas fa-eye"></i>
                        Status
                    </label>
                    <select id="status" name="status" class="form-input">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Create Post
                </button>
            </div>
        </form>
    </div>

        <script src="../assets/js/script.js"></script>
    <script>
        // Image upload and preview functionality
        document.getElementById('images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const previewContainer = document.getElementById('image-preview');
            
            // Clear existing previews
            previewContainer.innerHTML = '';
            
            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
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
                }
            });
        });
        
        // Drag and drop functionality
        const uploadArea = document.querySelector('.image-upload-area');
        
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
            
            // Create a new FileList
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            
            // Trigger change event
            fileInput.dispatchEvent(new Event('change'));
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
    </script>
</body>
</html>


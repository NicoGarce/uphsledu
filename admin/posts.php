<?php
/**
 * UPHSL Admin Posts Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing blog posts and news articles
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if user is logged in and is super admin
if (!isLoggedIn() || !isSuperAdmin()) {
    header('Location: ../auth/login.php');
    exit;
}

$pdo = getDBConnection();
$user = $_SESSION;

// Handle post updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_post') {
        $postId = $_POST['post_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = $_POST['excerpt'];
        $status = $_POST['status'];
        $publishedDate = $_POST['published_date'];
        
        try {
            $stmt = $pdo->prepare("
                UPDATE posts 
                SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $postId]);
            $success = "Post updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating post: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_post') {
        $postId = $_POST['post_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $success = "Post deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting post: " . $e->getMessage();
        }
    }
}

// Get all posts with author information
$stmt = $pdo->prepare("
    SELECT p.*, u.first_name, u.last_name, 
           CONCAT(u.first_name, ' ', u.last_name) as author_name
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    ORDER BY p.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Posts - University of Perpetual Help System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/logos/logo.png">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.png">
    <link rel="apple-touch-icon" href="../assets/images/logos/logo.png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
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
                <a href="../public/dashboard.php" class="nav-link">Dashboard</a>
                <?php if (isAuthor() || isSuperAdmin()): ?>
                    <a href="../public/create-post.php" class="nav-link">Create Post</a>
                <?php endif; ?>
                <?php if (isAdmin()): ?>
                    <a href="users.php" class="nav-link">Users</a>
                <?php endif; ?>
                <?php if (isSuperAdmin()): ?>
                    <a href="accounts.php" class="nav-link">Account Management</a>
                <?php endif; ?>
                <a href="posts.php" class="nav-link active">Edit Posts</a>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($user['first_name']); ?></span>
                <a href="../auth/logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Posts Management -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-edit"></i>
                Edit Posts
            </h1>
            <p class="dashboard-subtitle">Manage all posts and their publication dates</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Posts Table -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">All Posts</h2>
                <a href="create-post.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New Post
                </a>
            </div>
            
            <div class="posts-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <div class="post-title">
                                        <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                        <?php if ($post['excerpt']): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($post['excerpt']); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <?php if ($post['published_at']): ?>
                                        <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not published</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-primary" onclick="editPost(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deletePost(<?php echo $post['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Post</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="editForm" method="POST">
                <input type="hidden" name="action" value="update_post">
                <input type="hidden" name="post_id" id="edit_post_id">
                
                <div class="form-group">
                    <label for="edit_title">Title</label>
                    <input type="text" id="edit_title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_excerpt">Excerpt</label>
                    <textarea id="edit_excerpt" name="excerpt" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_content">Content</label>
                    <textarea id="edit_content" name="content" rows="10" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select id="edit_status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_published_date">Published Date</label>
                        <input type="datetime-local" id="edit_published_date" name="published_date">
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete Post</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post? This action cannot be undone.</p>
            </div>
            <form id="deleteForm" method="POST">
                <input type="hidden" name="action" value="delete_post">
                <input type="hidden" name="post_id" id="delete_post_id">
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Post</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get post data for editing
        const posts = <?php echo json_encode($posts); ?>;
        
        function editPost(postId) {
            const post = posts.find(p => p.id == postId);
            if (post) {
                document.getElementById('edit_post_id').value = post.id;
                document.getElementById('edit_title').value = post.title;
                document.getElementById('edit_excerpt').value = post.excerpt || '';
                document.getElementById('edit_content').value = post.content;
                document.getElementById('edit_status').value = post.status;
                
                if (post.published_at) {
                    const publishedDate = new Date(post.published_at);
                    document.getElementById('edit_published_date').value = publishedDate.toISOString().slice(0, 16);
                }
                
                document.getElementById('editModal').style.display = 'block';
            }
        }
        
        function deletePost(postId) {
            document.getElementById('delete_post_id').value = postId;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === editModal) {
                closeModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>
</body>
</html>
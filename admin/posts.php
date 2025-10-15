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

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || (!isAuthor() && !isAdmin() && !isSuperAdmin())) {
    header('Location: ../auth/login.php');
    exit;
}

$pdo = getDBConnection();
$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Post Management';

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
    
    if ($_POST['action'] === 'share_to_facebook') {
        $postId = $_POST['post_id'];
        
        // Get Facebook credentials from database
        $stmt = $pdo->prepare("SELECT * FROM facebook_tokens WHERE id = 1");
        $stmt->execute();
        $facebookData = $stmt->fetch();
        
        if (!$facebookData) {
            $error = "Facebook integration not configured. Please run setup_facebook_auto_renewal.php first.";
        } else {
            $appId = $facebookData['app_id'];
            $appSecret = $facebookData['app_secret'];
            $pageAccessToken = $facebookData['page_access_token'];
            $pageId = $facebookData['page_id'];
            
            try {
            // Get post data
            $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $post = $stmt->fetch();
            
            if ($post) {
                // Build post URL
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                $postUrl = $baseUrl . '/post.php?slug=' . $post['slug'];
                
                // Build message
                $message = $post['title'] . "\n\n";
                $excerpt = $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 200) . '...';
                $message .= $excerpt . "\n\n";
                $message .= "Read more: " . $postUrl . "\n\n";
                $message .= "#UPHSL #UniversityNews";
                
                // Post to Facebook
                $url = "https://graph.facebook.com/v18.0/{$pageId}/feed";
                $data = [
                    'message' => $message,
                    'link' => $postUrl,
                    'access_token' => $pageAccessToken
                ];
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                $result = json_decode($response, true);
                
                if ($httpCode == 200 && isset($result['id'])) {
                    $success = "Post shared to Facebook successfully! <a href='https://facebook.com/{$result['id']}' target='_blank'>View on Facebook</a>";
                } else {
                    $error = "Failed to share to Facebook: " . ($result['error']['message'] ?? 'Unknown error');
                }
            } else {
                $error = "Post not found";
            }
            } catch (Exception $e) {
                $error = "Error sharing to Facebook: " . $e->getMessage();
            }
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

<?php include '../app/includes/admin-header.php'; ?>

    <!-- Posts Management -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-edit"></i>
                Post Management
            </h1>
            <p class="dashboard-subtitle">Create, edit, and manage all posts</p>
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
                                <td>University of Perpetual Help System Laguna</td>
                                <td>
                                    <span class="status-badge status-<?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($post['published_at'] ?: $post['created_at'])); ?></td>
                                <td>
                                    <?php if ($post['published_at']): ?>
                                        <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not published</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="create-post.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary" title="Edit Post">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($post['status'] === 'published'): ?>
                                        <form method="POST" style="display: inline-block;" onsubmit="return confirm('Share this post to Facebook?')">
                                            <input type="hidden" name="action" value="share_to_facebook">
                                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-facebook" title="Share to Facebook">
                                                <i class="fab fa-facebook"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-danger" onclick="deletePost(<?php echo $post['id']; ?>)" title="Delete Post">
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
        
        function deletePost(postId) {
            document.getElementById('delete_post_id').value = postId;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>
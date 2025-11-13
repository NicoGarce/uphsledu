<?php
/**
 * UPHSL Admin SDG Initiatives Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing SDG initiatives posts
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
$page_title = 'SDG Initiatives Management';

// Initialize success and error variables
$success = '';
$error = '';

// Get success message from URL
if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}

// Handle post updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_post') {
        $postId = $_POST['post_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $excerpt = $_POST['excerpt'];
        $status = $_POST['status'];
        $publishedDate = $_POST['published_date'];
        $sdgNumber = $_POST['sdg_number'];
        $sdgTitle = $_POST['sdg_title'];
        
        try {
            $stmt = $pdo->prepare("
                UPDATE sdg_initiatives_posts 
                SET title = ?, content = ?, excerpt = ?, status = ?, published_at = ?, sdg_number = ?, sdg_title = ?
                WHERE id = ?
            ");
            $stmt->execute([$title, $content, $excerpt, $status, $publishedDate, $sdgNumber, $sdgTitle, $postId]);
            $success = "SDG Initiative post updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating post: " . $e->getMessage();
        }
    }
    
    if ($_POST['action'] === 'delete_post') {
        $postId = $_POST['post_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM sdg_initiatives_posts WHERE id = ?");
            $stmt->execute([$postId]);
            $success = "SDG Initiative post deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting post: " . $e->getMessage();
        }
    }
}

// Get all SDG posts with author information
$stmt = $pdo->prepare("
    SELECT p.*, u.first_name, u.last_name 
    FROM sdg_initiatives_posts p 
    JOIN users u ON p.author_id = u.id 
    ORDER BY p.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();

// SDG Goals data
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
?>

<?php include '../app/includes/admin-header.php'; ?>

    <!-- SDG Initiatives Management -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-globe-americas"></i>
                SDG Initiatives Management
            </h1>
            <p class="dashboard-subtitle">Create, edit, and manage SDG initiatives posts</p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- SDG Posts Table -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">All SDG Initiative Posts</h2>
                <a href="create-sdg-post.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New SDG Post
                </a>
            </div>
            
            <div class="posts-list">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post-item">
                            <div class="post-info">
                                <h3 class="post-title">
                                    <a href="create-sdg-post.php?edit=<?php echo $post['id']; ?>">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </a>
                                </h3>
                                <div class="post-meta">
                                    <span class="post-status status-<?php echo $post['status']; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                    <span class="post-sdg">
                                        SDG <?php echo $post['sdg_number']; ?>: <?php echo htmlspecialchars($post['sdg_title']); ?>
                                    </span>
                                    <span class="post-date">
                                        Created: <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                    </span>
                                    <?php if ($post['published_at']): ?>
                                        <span class="post-date">
                                            Published: <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="post-date text-muted">
                                            Not published
                                        </span>
                                    <?php endif; ?>
                                    <span class="post-author">
                                        by <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <a href="create-sdg-post.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-secondary" title="Edit Post">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($post['status'] === 'published'): ?>
                                <button class="btn btn-sm btn-info" onclick="copyPostLink('<?php echo $post['slug']; ?>')" title="Copy Post Link">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <?php endif; ?>
                                <button class="btn btn-sm btn-danger" onclick="deletePost(<?php echo $post['id']; ?>)" title="Delete Post">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-globe-americas"></i>
                        <h3>No SDG Initiative posts yet</h3>
                        <p>Start creating content about our sustainable development initiatives!</p>
                        <a href="create-sdg-post.php" class="btn btn-primary">Create Your First SDG Post</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete SDG Initiative Post</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this SDG initiative post? This action cannot be undone.</p>
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
        
        // Copy SDG post link function
        function copyPostLink(slug) {
            // Build site base URL (root of app, not /admin) and ensure no trailing slash
            const baseUrl = '<?php 
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $appRoot = $protocol . $_SERVER['HTTP_HOST'] . dirname(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
                echo rtrim($appRoot, '/');
            ?>';
            const postUrl = `${baseUrl}/sdg-post?slug=${slug}`;
            
            // Create a temporary textarea element
            const textarea = document.createElement('textarea');
            textarea.value = postUrl;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                // Copy the text
                document.execCommand('copy');
                
                // Show success message
                showNotification('SDG post link copied to clipboard!', 'success');
            } catch (err) {
                // Fallback for modern browsers
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(postUrl).then(function() {
                        showNotification('SDG post link copied to clipboard!', 'success');
                    }).catch(function() {
                        showNotification('Failed to copy link', 'error');
                    });
                } else {
                    showNotification('Failed to copy link', 'error');
                }
            }
            
            // Remove the temporary textarea
            document.body.removeChild(textarea);
        }

        // Show notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Style the notification
            notification.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                padding: 14px 22px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 2000;
                opacity: 0;
                transform: translate(-50%, -60%);
                transition: all 0.25s ease;
                max-width: 80vw;
                word-wrap: break-word;
                text-align: center;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                pointer-events: none;
            `;
            
            // Set background color based on type
            if (type === 'success') {
                notification.style.backgroundColor = '#28a745';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#dc3545';
            } else {
                notification.style.backgroundColor = '#007bff';
            }
            
            // Add to page
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translate(-50%, -50%)';
            }, 50);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translate(-50%, -60%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 1500);
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        }
        
        // Auto-refresh page if redirected after creating/editing a post to show new post immediately
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            // Remove success parameter and reload to show the newly created post
            // Use a small delay to ensure the success message is visible first
            setTimeout(function() {
                urlParams.delete('success');
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                // Force a hard reload to bypass cache
                window.location.href = newUrl;
            }, 500);
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>

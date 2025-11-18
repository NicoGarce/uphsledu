<?php
/**
 * UPHSL Admin SDG Initiatives Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing SDG initiatives posts
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

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
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } elseif ($_POST['action'] === 'update_post') {
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
    } elseif ($_POST['action'] === 'delete_post') {
        $postId = $_POST['post_id'];
        $password = $_POST['password'] ?? '';
        
        // Verify password
        if (empty($password) || !verifyUserPassword($_SESSION['user_id'], $password)) {
            $error = "Invalid password. Please try again.";
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM sdg_initiatives_posts WHERE id = ?");
                $stmt->execute([$postId]);
                $success = "SDG Initiative post deleted successfully!";
            } catch (PDOException $e) {
                $error = "Error deleting post: " . $e->getMessage();
            }
        }
    } elseif ($_POST['action'] === 'bulk_action') {
        $selectedIds = $_POST['selected_ids'] ?? '';
        $bulkAction = $_POST['bulk_action_type'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Verify password
        if (empty($password) || !verifyUserPassword($_SESSION['user_id'], $password)) {
            $error = "Invalid password. Please try again.";
        } elseif (empty($selectedIds)) {
            $error = "No items selected.";
        } elseif (empty($bulkAction)) {
            $error = "No action selected.";
        } else {
            try {
                $pdo->beginTransaction();
                // Handle JSON array from JavaScript
                if (is_string($selectedIds)) {
                    $decoded = json_decode($selectedIds, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $selectedIds = $decoded;
                    } else {
                        $error = "Invalid selection data.";
                        $pdo->rollBack();
                    }
                }
                if (!is_array($selectedIds) || empty($selectedIds)) {
                    if (empty($error)) {
                        $error = "No valid items selected.";
                    }
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                } else {
                    $ids = array_map('intval', $selectedIds);
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    
                    switch ($bulkAction) {
                        case 'delete':
                            $stmt = $pdo->prepare("DELETE FROM sdg_initiatives_posts WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " SDG post(s) deleted successfully!";
                            break;
                        case 'draft':
                            $stmt = $pdo->prepare("UPDATE sdg_initiatives_posts SET status = 'draft', published_at = NULL WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " SDG post(s) moved to draft successfully!";
                            break;
                        case 'publish':
                            $now = date('Y-m-d H:i:s');
                            $stmt = $pdo->prepare("UPDATE sdg_initiatives_posts SET status = 'published', published_at = COALESCE(published_at, ?) WHERE id IN ($placeholders)");
                            $stmt->execute(array_merge([$now], $ids));
                            $success = count($ids) . " SDG post(s) published successfully!";
                            break;
                        default:
                            $error = "Invalid action selected.";
                    }
                    
                    $pdo->commit();
                }
            } catch (PDOException $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $error = "Error performing bulk action: " . $e->getMessage();
            }
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
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="checkbox" id="selectAll" style="width: 18px; height: 18px; cursor: pointer;" title="Select All">
                    <label for="selectAll" style="margin: 0; cursor: pointer; font-weight: 500;">Select All</label>
                    <span id="selectedCount" style="margin-left: 0.5rem; color: var(--primary-color); font-weight: 600; display: none;">0 selected</span>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                    <select id="bulkActionSelect" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; display: none;">
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish</option>
                        <option value="draft">Move to Draft</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="button" class="btn btn-secondary" id="applyBulkAction" style="display: none;">
                        <i class="fas fa-check"></i>
                        Apply
                    </button>
                    <a href="create-sdg-post.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create New SDG Post
                    </a>
                </div>
            </div>
            
            <div class="posts-list">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post-item">
                            <div style="display: flex; align-items: flex-start; gap: 1rem; width: 100%;">
                                <input type="checkbox" class="sdg-checkbox" value="<?php echo $post['id']; ?>" style="width: 18px; height: 18px; cursor: pointer; margin-top: 0.5rem; flex-shrink: 0;">
                                <div style="flex: 1;">
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

    <!-- Bulk Action Password Modal -->
    <div id="bulkActionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="bulkActionTitle">Bulk Action</h3>
                <span class="close" onclick="closeBulkActionModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="bulkActionMessage">Please enter your password to confirm this action.</p>
                <form id="bulkActionForm" method="POST">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="bulk_action">
                    <input type="hidden" name="bulk_action_type" id="bulkActionType">
                    <input type="hidden" name="selected_ids" id="bulkSelectedIds">
                    <div style="margin-bottom: 1rem;">
                        <label for="bulkPassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                        <input type="password" id="bulkPassword" name="password" required 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                               placeholder="Enter your password">
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeBulkActionModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
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
                <p>Please enter your password to confirm this action.</p>
                <form id="deleteForm" method="POST">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="delete_post">
                    <input type="hidden" name="post_id" id="delete_post_id">
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="deletePassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                        <input type="password" id="deletePassword" name="password" required 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                               placeholder="Enter your password" autocomplete="current-password">
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Bulk Actions
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const sdgCheckboxes = document.querySelectorAll('.sdg-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            const selectedCountSpan = document.getElementById('selectedCount');
            
            function updateBulkActionUI() {
                const selected = Array.from(sdgCheckboxes).filter(cb => cb.checked);
                const count = selected.length;
                
                if (count > 0) {
                    selectedCountSpan.textContent = count + ' selected';
                    selectedCountSpan.style.display = 'inline';
                    bulkActionSelect.style.display = 'inline-block';
                    applyBulkActionBtn.style.display = 'inline-block';
                } else {
                    selectedCountSpan.style.display = 'none';
                    bulkActionSelect.style.display = 'none';
                    applyBulkActionBtn.style.display = 'none';
                    bulkActionSelect.value = '';
                }
            }
            
            // Select All functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    sdgCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActionUI();
                });
            }
            
            // Individual checkbox change
            sdgCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = Array.from(sdgCheckboxes).every(cb => cb.checked);
                    }
                    updateBulkActionUI();
                });
            });
            
            // Apply bulk action
            if (applyBulkActionBtn) {
                applyBulkActionBtn.addEventListener('click', function() {
                    const selected = Array.from(sdgCheckboxes).filter(cb => cb.checked);
                    const action = bulkActionSelect.value;
                    
                    if (selected.length === 0) {
                        alert('Please select at least one SDG post.');
                        return;
                    }
                    
                    if (!action) {
                        alert('Please select an action.');
                        return;
                    }
                    
                    const selectedIds = selected.map(cb => cb.value);
                    const actionNames = {
                        'delete': 'Delete',
                        'draft': 'Move to Draft',
                        'publish': 'Publish'
                    };
                    
                    document.getElementById('bulkActionType').value = action;
                    document.getElementById('bulkSelectedIds').value = JSON.stringify(selectedIds);
                    document.getElementById('bulkActionTitle').textContent = actionNames[action] + ' SDG Posts';
                    document.getElementById('bulkActionMessage').textContent = 
                        `You are about to ${actionNames[action].toLowerCase()} ${selectedIds.length} SDG post(s). Please enter your password to confirm.`;
                    document.getElementById('bulkPassword').value = '';
                    document.getElementById('bulkActionModal').style.display = 'block';
                });
            }
            
            function closeBulkActionModal() {
                document.getElementById('bulkActionModal').style.display = 'none';
                document.getElementById('bulkPassword').value = '';
            }
            
            window.closeBulkActionModal = closeBulkActionModal;
            
            // Close modal when clicking outside
            window.onclick = function(event) {
                const bulkModal = document.getElementById('bulkActionModal');
                if (event.target === bulkModal) {
                    closeBulkActionModal();
                }
            }
        });
    </script>

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

    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 0;
            border: 1px solid #888;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 500px;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1c4da1;
            border-radius: 8px 8px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover,
        .close:focus {
            color: #f0f0f0;
            opacity: 0.8;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
    </style>

<?php include '../app/includes/admin-footer.php'; ?>

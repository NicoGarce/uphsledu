<?php
/**
 * UPHSL Admin Posts Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing blog posts and news articles
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
$page_title = 'Post Management';

// Initialize success and error variables
$success = '';
$error = '';

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
    } elseif ($_POST['action'] === 'delete_post') {
        $postId = $_POST['post_id'];
        
        try {
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $success = "Post deleted successfully!";
        } catch (PDOException $e) {
            $error = "Error deleting post: " . $e->getMessage();
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
                            $stmt = $pdo->prepare("DELETE FROM posts WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " post(s) deleted successfully!";
                            break;
                        case 'draft':
                            $stmt = $pdo->prepare("UPDATE posts SET status = 'draft', published_at = NULL WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " post(s) moved to draft successfully!";
                            break;
                        case 'publish':
                            $now = date('Y-m-d H:i:s');
                            $stmt = $pdo->prepare("UPDATE posts SET status = 'published', published_at = COALESCE(published_at, ?) WHERE id IN ($placeholders)");
                            $stmt->execute(array_merge([$now], $ids));
                            $success = count($ids) . " post(s) published successfully!";
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

// Get filter parameters
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$dateRange = $_GET['date_range'] ?? '';

// Get success message from URL
if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}

// Build query with filters
$sql = "
    SELECT p.id, p.title, p.slug, p.status, p.created_at, p.published_at, p.category_id,
           u.first_name, u.last_name, 
           CONCAT(u.first_name, ' ', u.last_name) as author_name,
           COALESCE(c.name, 'University News') as category_name
    FROM posts p 
    JOIN users u ON p.author_id = u.id 
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE 1=1
";

$params = [];

// Filter by author if user is an author
if (isAuthor()) {
    $sql .= " AND p.author_id = ?";
    $params[] = $_SESSION['user_id'];
}

// Search filter
if (!empty($search)) {
    $sql .= " AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Status filter
if (!empty($statusFilter) && in_array($statusFilter, ['draft', 'published', 'archived'])) {
    $sql .= " AND p.status = ?";
    $params[] = $statusFilter;
}

// Category filter
if (!empty($categoryFilter)) {
    if ($categoryFilter === 'university-news') {
        // Filter for posts with null category (University News)
        $sql .= " AND p.category_id IS NULL";
    } elseif (is_numeric($categoryFilter)) {
        $sql .= " AND p.category_id = ?";
        $params[] = (int)$categoryFilter;
    } else {
        $cat = getCategoryByName($categoryFilter);
        if ($cat) {
            $sql .= " AND p.category_id = ?";
            $params[] = $cat['id'];
        }
    }
}

// Date range filter
if (!empty($dateRange)) {
    $today = date('Y-m-d');
    switch ($dateRange) {
        case 'today':
            $sql .= " AND DATE(p.created_at) = ?";
            $params[] = $today;
            break;
        case 'week':
            $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 WEEK)";
            $params[] = $today;
            break;
        case 'month':
            $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 MONTH)";
            $params[] = $today;
            break;
        case 'year':
            $sql .= " AND p.created_at >= DATE_SUB(?, INTERVAL 1 YEAR)";
            $params[] = $today;
            break;
    }
}

$sql .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$postsRaw = $stmt->fetchAll();

// Remove duplicates by post ID
$posts = [];
$seenIds = [];
foreach ($postsRaw as $post) {
    if (!in_array($post['id'], $seenIds)) {
        $posts[] = $post;
        $seenIds[] = $post['id'];
    }
}

// Get all categories for filter dropdown (remove duplicates)
$allCategoriesRaw = getAllCategories();
$allCategories = [];
$seenNames = [];
foreach ($allCategoriesRaw as $cat) {
    if (!in_array($cat['name'], $seenNames)) {
        $allCategories[] = $cat;
        $seenNames[] = $cat['name'];
    }
}
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

        <!-- Search and Filters -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Search & Filters</h2>
            </div>
            <form method="GET" class="filter-form" id="filterForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" id="search" 
                                   placeholder="Search posts by title or content..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="">All Statuses</option>
                            <option value="draft" <?php echo $statusFilter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $statusFilter === 'published' ? 'selected' : ''; ?>>Published</option>
                            <option value="archived" <?php echo $statusFilter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="category">Category</label>
                        <select name="category" id="category">
                            <option value="">All Categories</option>
                            <option value="university-news" <?php echo ($categoryFilter === 'university-news') ? 'selected' : ''; ?>>
                                University News
                            </option>
                            <?php foreach ($allCategories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                        <?php echo ($categoryFilter == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_range">Date Range</label>
                        <select name="date_range" id="date_range">
                            <option value="">All Time</option>
                            <option value="today" <?php echo $dateRange === 'today' ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo $dateRange === 'week' ? 'selected' : ''; ?>>Last Week</option>
                            <option value="month" <?php echo $dateRange === 'month' ? 'selected' : ''; ?>>Last Month</option>
                            <option value="year" <?php echo $dateRange === 'year' ? 'selected' : ''; ?>>Last Year</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="button" class="btn btn-secondary" id="clearFilters">
                        <i class="fas fa-times"></i>
                        Clear Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">All Posts (<span id="postCount"><?php echo count($posts); ?></span>)</h2>
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
                    <button type="button" class="btn btn-info" id="openPdfBrowser" title="Browse PDF Files">
                        <i class="fas fa-file-pdf"></i>
                        Browse PDFs
                    </button>
                    <a href="create-post.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create New Post
                    </a>
                </div>
            </div>
            
        <div class="posts-list" id="postsList">
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <div style="display: flex; align-items: flex-start; gap: 1rem; width: 100%;">
                        <input type="checkbox" class="post-checkbox" value="<?php echo $post['id']; ?>" style="width: 18px; height: 18px; cursor: pointer; margin-top: 0.5rem; flex-shrink: 0;">
                        <div style="flex: 1;">
                    <div class="post-info">
                        <h3 class="post-title">
                            <a href="create-post.php?edit=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h3>
                        <div class="post-meta">
                            <span class="post-status status-<?php echo $post['status']; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                            <span class="post-category">
                                <i class="fas fa-folder"></i>
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </span>
                            <span class="post-date">
                                <i class="fas fa-calendar"></i>
                                Created: <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                            </span>
                            <?php if ($post['published_at']): ?>
                                <span class="post-date">
                                    <i class="fas fa-clock"></i>
                                    Published: <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                                </span>
                            <?php else: ?>
                                <span class="post-date text-muted">
                                    <i class="fas fa-clock"></i>
                                    Not published
                                </span>
                            <?php endif; ?>
                            <span class="post-author">
                                <i class="fas fa-user"></i>
                                by <?php echo htmlspecialchars($post['author_name']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="post-actions">
                        <a href="create-post.php?edit=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary" title="Edit Post">
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
                <h3>Delete Post</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post? This action cannot be undone.</p>
            </div>
            <form id="deleteForm" method="POST">
                <?php echo CSRF::field(); ?>
                <input type="hidden" name="action" value="delete_post">
                <input type="hidden" name="post_id" id="delete_post_id">
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Post</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PDF Browser Modal -->
    <div id="pdfBrowserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-file-pdf"></i>
                    PDF Browser
                </h3>
                <span class="close" onclick="closePdfBrowser()">&times;</span>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div style="margin-bottom: 1.5rem;">
                    <div class="search-input-wrapper" style="margin-bottom: 0;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="pdfSearch" placeholder="Search PDF files..." style="width: 100%;">
                    </div>
                </div>
                <div id="pdfListContainer" style="max-height: 60vh; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 1rem;">
                    <div style="text-align: center; padding: 2rem; color: #666;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <p>Loading PDFs...</p>
                    </div>
                </div>
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #ddd; color: #666; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i>
                    <span id="pdfCount">0</span> PDF file(s) found
                </div>
            </div>
        </div>
    </div>

    <script>
        // AJAX Search and Filter
        let isLoading = false;
        let searchTimeout;
        
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const categorySelect = document.getElementById('category');
        const dateRangeSelect = document.getElementById('date_range');
        const postsList = document.getElementById('postsList');
        const postCount = document.getElementById('postCount');
        
        // Function to load posts via AJAX
        function loadPosts() {
            if (isLoading) return;
            
            isLoading = true;
            postsList.style.opacity = '0.6';
            postsList.style.pointerEvents = 'none';
            
            const params = new URLSearchParams({
                search: searchInput.value,
                status: statusSelect.value,
                category: categorySelect.value,
                date_range: dateRangeSelect.value
            });
            
            fetch(`ajax-posts.php?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPosts(data.posts);
                        postCount.textContent = data.count;
                    } else {
                        console.error('Error:', data.error);
                        showNotification('Error loading posts: ' + (data.error || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error loading posts', 'error');
                })
                .finally(() => {
                    isLoading = false;
                    postsList.style.opacity = '1';
                    postsList.style.pointerEvents = 'auto';
                });
        }
        
        // Function to render posts
        function renderPosts(posts) {
            if (posts.length === 0) {
                postsList.innerHTML = '<div class="no-posts"><p>No posts found matching your filters.</p></div>';
                return;
            }
            
            let html = '';
            posts.forEach(post => {
                const publishedDate = post.published_date ? 
                    `<span class="post-date"><i class="fas fa-clock"></i> Published: ${post.published_date}</span>` :
                    `<span class="post-date text-muted"><i class="fas fa-clock"></i> Not published</span>`;
                
                const copyButton = post.status === 'published' ?
                    `<button class="btn btn-sm btn-info" onclick="copyPostLink('${post.slug}')" title="Copy Post Link"><i class="fas fa-copy"></i></button>` :
                    '';
                
                html += `
                    <div class="post-item">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; width: 100%;">
                            <input type="checkbox" class="post-checkbox" value="${post.id}" style="width: 18px; height: 18px; cursor: pointer; margin-top: 0.5rem; flex-shrink: 0;">
                            <div style="flex: 1;">
                                <div class="post-info">
                                    <h3 class="post-title">
                                        <a href="create-post.php?edit=${post.id}">${escapeHtml(post.title)}</a>
                                    </h3>
                                    <div class="post-meta">
                                        <span class="post-status status-${post.status}">${capitalizeFirst(post.status)}</span>
                                        <span class="post-category"><i class="fas fa-folder"></i> ${escapeHtml(post.category_name)}</span>
                                        <span class="post-date"><i class="fas fa-calendar"></i> Created: ${post.created_date}</span>
                                        ${publishedDate}
                                        <span class="post-author"><i class="fas fa-user"></i> by ${escapeHtml(post.author_name)}</span>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <a href="create-post.php?edit=${post.id}" class="btn btn-sm btn-primary" title="Edit Post">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    ${copyButton}
                                    <button class="btn btn-sm btn-danger" onclick="deletePost(${post.id})" title="Delete Post">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            postsList.innerHTML = html;
            
            // Re-initialize checkboxes after content is loaded
            initializeCheckboxes();
        }
        
        // Function to initialize checkbox event listeners
        function initializeCheckboxes() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const postCheckboxes = document.querySelectorAll('.post-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            const selectedCountSpan = document.getElementById('selectedCount');
            
            function updateBulkActionUI() {
                const selected = Array.from(postCheckboxes).filter(cb => cb.checked);
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
                // Remove old event listeners by cloning
                const newSelectAll = selectAllCheckbox.cloneNode(true);
                selectAllCheckbox.parentNode.replaceChild(newSelectAll, selectAllCheckbox);
                
                newSelectAll.addEventListener('change', function() {
                    postCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActionUI();
                });
            }
            
            // Individual checkbox change
            postCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) {
                        selectAll.checked = Array.from(postCheckboxes).every(cb => cb.checked);
                    }
                    updateBulkActionUI();
                });
            });
            
            // Update bulk action button handler
            if (applyBulkActionBtn) {
                // Remove old event listeners
                const newApplyBtn = applyBulkActionBtn.cloneNode(true);
                applyBulkActionBtn.parentNode.replaceChild(newApplyBtn, applyBulkActionBtn);
                
                newApplyBtn.addEventListener('click', function() {
                    const selected = Array.from(postCheckboxes).filter(cb => cb.checked);
                    const action = bulkActionSelect.value;
                    
                    if (selected.length === 0) {
                        alert('Please select at least one post.');
                        return;
                    }
                    
                    if (!action) {
                        alert('Please select an action.');
                        return;
                    }
                    
                    const selectedIds = selected.map(cb => cb.value);
                    const actionNames = {
                        'publish': 'Publish',
                        'draft': 'Move to Draft',
                        'delete': 'Delete'
                    };
                    
                    document.getElementById('bulkActionType').value = action;
                    document.getElementById('bulkSelectedIds').value = JSON.stringify(selectedIds);
                    document.getElementById('bulkActionTitle').textContent = actionNames[action] + ' Posts';
                    document.getElementById('bulkActionMessage').textContent = 
                        `You are about to ${actionNames[action].toLowerCase()} ${selectedIds.length} post(s). Please enter your password to confirm.`;
                    document.getElementById('bulkPassword').value = '';
                    document.getElementById('bulkActionModal').style.display = 'block';
                });
            }
        }
        
        // Helper functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        // Clear filters function
        function clearFilters() {
            searchInput.value = '';
            statusSelect.value = '';
            categorySelect.value = '';
            dateRangeSelect.value = '';
            loadPosts();
        }
        
        // Event listeners
        // Prevent form submission (filters work automatically)
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });
        
        // Clear filters button
        document.getElementById('clearFilters').addEventListener('click', clearFilters);
        
        // Debounced search
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadPosts, 500);
        });
        
        // Immediate filter on select change
        statusSelect.addEventListener('change', loadPosts);
        categorySelect.addEventListener('change', loadPosts);
        dateRangeSelect.addEventListener('change', loadPosts);
        
        // Auto-refresh posts list if redirected after creating/editing a post
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            // Refresh the posts list to show the newly created/edited post
            loadPosts();
        }
        
        // Initialize checkboxes on page load
        initializeCheckboxes();
        
        // Apply bulk action (keep existing handler for initial load)
        const applyBulkActionBtn = document.getElementById('applyBulkAction');
        if (applyBulkActionBtn && !applyBulkActionBtn.hasAttribute('data-initialized')) {
            applyBulkActionBtn.setAttribute('data-initialized', 'true');
            applyBulkActionBtn.addEventListener('click', function() {
                const postCheckboxes = document.querySelectorAll('.post-checkbox');
                const selected = Array.from(postCheckboxes).filter(cb => cb.checked);
                const action = bulkActionSelect.value;
                
                if (selected.length === 0) {
                    alert('Please select at least one post.');
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
                document.getElementById('bulkActionTitle').textContent = actionNames[action] + ' Posts';
                document.getElementById('bulkActionMessage').textContent = 
                    `You are about to ${actionNames[action].toLowerCase()} ${selectedIds.length} post(s). Please enter your password to confirm.`;
                document.getElementById('bulkPassword').value = '';
                document.getElementById('bulkActionModal').style.display = 'block';
            });
        }
        
        function closeBulkActionModal() {
            document.getElementById('bulkActionModal').style.display = 'none';
            document.getElementById('bulkPassword').value = '';
        }
        
        function deletePost(postId) {
            document.getElementById('delete_post_id').value = postId;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Copy post link function
        function copyPostLink(slug) {
            // Build site base URL (root of app, not /admin) and ensure no trailing slash
            const baseUrl = '<?php 
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $appRoot = $protocol . $_SERVER['HTTP_HOST'] . dirname(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
                echo rtrim($appRoot, '/');
            ?>';
            const postUrl = `${baseUrl}/post?slug=${slug}`;
            
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
                showNotification('Post link copied to clipboard!', 'success');
            } catch (err) {
                // Fallback for modern browsers
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(postUrl).then(function() {
                        showNotification('Post link copied to clipboard!', 'success');
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
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const bulkModal = document.getElementById('bulkActionModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === bulkModal) {
                closeBulkActionModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // PDF Browser Functions
        let pdfSearchTimeout;
        const pdfBrowserModal = document.getElementById('pdfBrowserModal');
        const pdfSearchInput = document.getElementById('pdfSearch');
        const pdfListContainer = document.getElementById('pdfListContainer');
        const pdfCount = document.getElementById('pdfCount');
        const openPdfBrowserBtn = document.getElementById('openPdfBrowser');

        // Open PDF Browser
        if (openPdfBrowserBtn) {
            openPdfBrowserBtn.addEventListener('click', function() {
                pdfBrowserModal.style.display = 'block';
                loadPDFs();
            });
        }

        // Close PDF Browser
        function closePdfBrowser() {
            pdfBrowserModal.style.display = 'none';
        }

        // Close modal when clicking outside
        if (pdfBrowserModal) {
            window.addEventListener('click', function(event) {
                if (event.target === pdfBrowserModal) {
                    closePdfBrowser();
                }
            });
        }

        // Search PDFs
        if (pdfSearchInput) {
            pdfSearchInput.addEventListener('input', function() {
                clearTimeout(pdfSearchTimeout);
                pdfSearchTimeout = setTimeout(() => {
                    loadPDFs(pdfSearchInput.value);
                }, 300);
            });
        }

        // Load PDFs
        function loadPDFs(search = '') {
            if (!pdfListContainer) return;
            
            pdfListContainer.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Loading PDFs...</p>
                </div>
            `;

            const url = 'ajax-pdf-browser.php' + (search ? '?search=' + encodeURIComponent(search) : '');
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPDFs(data.files);
                        if (pdfCount) pdfCount.textContent = data.count;
                    } else {
                        pdfListContainer.innerHTML = `
                            <div style="text-align: center; padding: 2rem; color: #dc3545;">
                                <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                <p>Error loading PDFs: ${data.error || 'Unknown error'}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    pdfListContainer.innerHTML = `
                        <div style="text-align: center; padding: 2rem; color: #dc3545;">
                            <i class="fas fa-exclamation-circle" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                            <p>Error loading PDFs: ${error.message}</p>
                        </div>
                    `;
                });
        }

        // Render PDFs
        function renderPDFs(files) {
            if (!pdfListContainer) return;
            
            if (files.length === 0) {
                pdfListContainer.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: #666;">
                        <i class="fas fa-file-pdf" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <p>No PDF files found</p>
                    </div>
                `;
                return;
            }

            let html = '<div style="display: grid; gap: 0.5rem;">';
            
            files.forEach(file => {
                const fileSize = formatFileSize(file.size);
                const modifiedDate = new Date(file.modified * 1000).toLocaleDateString();
                const fullPath = file.fullPath.replace(/\\/g, '/');
                
                html += `
                    <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 0.6rem 0.75rem; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s;">
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 0.4rem; margin-bottom: 0.3rem;">
                                <i class="fas fa-file-pdf" style="color: #dc3545; font-size: 0.9rem;"></i>
                                <strong style="color: #212529; font-size: 0.85rem; word-break: break-word;">${escapeHtml(file.name)}</strong>
                            </div>
                            <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 0.2rem;">
                                <i class="fas fa-folder" style="font-size: 0.7rem;"></i> ${escapeHtml(file.path.replace(/\\/g, '/'))}
                            </div>
                            <div style="font-size: 0.7rem; color: #adb5bd; display: flex; gap: 0.75rem;">
                                <span><i class="fas fa-hdd" style="font-size: 0.65rem;"></i> ${fileSize}</span>
                                <span><i class="fas fa-calendar" style="font-size: 0.65rem;"></i> ${modifiedDate}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.4rem; flex-shrink: 0;">
                            <button class="btn btn-sm btn-primary" onclick="copyPdfLink('${escapeHtml(fullPath)}', '${escapeHtml(file.name)}')" title="Copy Link" style="padding: 0.35rem 0.5rem; font-size: 0.75rem;">
                                <i class="fas fa-copy" style="font-size: 0.75rem;"></i>
                            </button>
                            <a href="${escapeHtml(fullPath)}" target="_blank" class="btn btn-sm btn-info" title="Open PDF" style="padding: 0.35rem 0.5rem; font-size: 0.75rem;">
                                <i class="fas fa-external-link-alt" style="font-size: 0.75rem;"></i>
                            </a>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            pdfListContainer.innerHTML = html;
        }

        // Copy PDF Link
        function copyPdfLink(path, fileName) {
            // Remove '../' from the beginning if present
            const cleanPath = path.replace(/^\.\.\//, '');
            const baseUrl = '<?php 
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $appRoot = $protocol . $_SERVER['HTTP_HOST'] . dirname(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
                echo rtrim($appRoot, '/');
            ?>';
            const fullUrl = baseUrl + '/' + cleanPath;
            
            // Create a temporary textarea element
            const textarea = document.createElement('textarea');
            textarea.value = fullUrl;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                showNotification('PDF link copied to clipboard!', 'success');
            } catch (err) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(fullUrl).then(function() {
                        showNotification('PDF link copied to clipboard!', 'success');
                    }).catch(function() {
                        showNotification('Failed to copy link', 'error');
                    });
                } else {
                    showNotification('Failed to copy link', 'error');
                }
            }
            
            document.body.removeChild(textarea);
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
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

        /* Filter Form Styles */
        .filter-form {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
        }

        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-input-wrapper input {
            padding-left: 40px;
            width: 100%;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .post-category {
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .post-category i {
            font-size: 12px;
        }

        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            margin-top: 10px;
        }

        .post-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--text-light);
        }

        .post-meta span i {
            font-size: 12px;
        }

        .no-posts {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .no-posts p {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .filter-actions .btn {
                width: 100%;
            }
        }
    </style>

<?php include '../app/includes/admin-footer.php'; ?>
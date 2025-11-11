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
    
}

// Get filter parameters
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$dateRange = $_GET['date_range'] ?? '';

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
                <div style="display: flex; gap: 0.5rem;">
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
            <?php endforeach; ?>
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
                `;
            });
            
            postsList.innerHTML = html;
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
            const deleteModal = document.getElementById('deleteModal');
            
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
            max-width: 900px;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            max-height: 90vh;
            display: flex;
            flex-direction: column;
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
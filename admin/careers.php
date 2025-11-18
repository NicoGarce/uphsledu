<?php
/**
 * UPHSL Admin Careers Management
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing career postings
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatically initialized by security.php

// Check if user is logged in and has appropriate permissions
if (!isLoggedIn() || (!isHR() && !isAdmin() && !isSuperAdmin())) {
    header('Location: ../auth/login.php');
    exit;
}

$pdo = getDBConnection();
$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'Careers Management';

// Initialize success and error variables
$success = '';
$error = '';

// Handle career posting updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } elseif ($_POST['action'] === 'delete_career') {
        $careerId = $_POST['career_id'];
        $password = $_POST['password'] ?? '';
        
        // Verify password
        if (empty($password) || !verifyUserPassword($_SESSION['user_id'], $password)) {
            $error = "Invalid password. Please try again.";
        } else {
            try {
                // HR, Admins, and Super Admins can delete any posting
                $stmt = $pdo->prepare("DELETE FROM careers_postings WHERE id = ?");
                $stmt->execute([$careerId]);
                
                if ($stmt->rowCount() > 0) {
                    $success = "Career posting deleted successfully!";
                } else {
                    $error = "Career posting not found or you do not have permission to delete it";
                }
            } catch (PDOException $e) {
                $error = "Error deleting career posting: " . $e->getMessage();
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
                            $stmt = $pdo->prepare("DELETE FROM careers_postings WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " career posting(s) deleted successfully!";
                            break;
                        case 'archive':
                            $stmt = $pdo->prepare("UPDATE careers_postings SET status = 'archived' WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " career posting(s) archived successfully!";
                            break;
                        case 'draft':
                            $stmt = $pdo->prepare("UPDATE careers_postings SET status = 'draft', published_at = NULL WHERE id IN ($placeholders)");
                            $stmt->execute($ids);
                            $success = count($ids) . " career posting(s) moved to draft successfully!";
                            break;
                        case 'publish':
                            $now = date('Y-m-d H:i:s');
                            $stmt = $pdo->prepare("UPDATE careers_postings SET status = 'published', published_at = COALESCE(published_at, ?) WHERE id IN ($placeholders)");
                            $stmt->execute(array_merge([$now], $ids));
                            $success = count($ids) . " career posting(s) published successfully!";
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
$dateRange = $_GET['date_range'] ?? '';

// Get success message from URL
if (isset($_GET['success'])) {
    $success = urldecode($_GET['success']);
}

// Build query with filters
$sql = "
    SELECT cp.id, cp.position, cp.slug, cp.location, cp.employment_type, cp.status, cp.created_at, cp.published_at,
           u.first_name, u.last_name, 
           CONCAT(u.first_name, ' ', u.last_name) as author_name
    FROM careers_postings cp 
    JOIN users u ON cp.author_id = u.id 
    WHERE 1=1
";

$params = [];

// HR accounts can see all career postings (no filter needed)
// Only filter if needed for other roles in the future

// Search filter
if (!empty($search)) {
    $sql .= " AND (cp.position LIKE ? OR cp.location LIKE ? OR cp.job_description LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Status filter
if (!empty($statusFilter) && in_array($statusFilter, ['draft', 'published', 'archived'])) {
    $sql .= " AND cp.status = ?";
    $params[] = $statusFilter;
}

// Date range filter
if (!empty($dateRange)) {
    $today = date('Y-m-d');
    switch ($dateRange) {
        case 'today':
            $sql .= " AND DATE(cp.created_at) = ?";
            $params[] = $today;
            break;
        case 'week':
            $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 WEEK)";
            $params[] = $today;
            break;
        case 'month':
            $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 MONTH)";
            $params[] = $today;
            break;
        case 'year':
            $sql .= " AND cp.created_at >= DATE_SUB(?, INTERVAL 1 YEAR)";
            $params[] = $today;
            break;
    }
}

$sql .= " ORDER BY cp.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$careers = $stmt->fetchAll();
?>

<?php include '../app/includes/admin-header.php'; ?>

    <!-- Careers Management -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-briefcase"></i>
                Careers Management
            </h1>
            <p class="dashboard-subtitle">Create, edit, and manage career postings</p>
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
                                   placeholder="Search by position, location..." 
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

        <!-- Careers Table -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">All Career Postings (<span id="careerCount"><?php echo count($careers); ?></span>)</h2>
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
                        <option value="archive">Archive</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="button" class="btn btn-secondary" id="applyBulkAction" style="display: none;">
                        <i class="fas fa-check"></i>
                        Apply
                    </button>
                    <a href="create-career.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create New Posting
                    </a>
                </div>
            </div>
            
            <div class="posts-list" id="careersList">
                <?php foreach ($careers as $career): ?>
                    <div class="post-item">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; width: 100%;">
                            <input type="checkbox" class="career-checkbox" value="<?php echo $career['id']; ?>" style="width: 18px; height: 18px; cursor: pointer; margin-top: 0.5rem; flex-shrink: 0;">
                            <div style="flex: 1;">
                                <div class="post-info">
                                    <h3 class="post-title">
                                        <a href="create-career.php?edit=<?php echo $career['id']; ?>">
                                            <?php echo htmlspecialchars($career['position']); ?>
                                        </a>
                                    </h3>
                                    <div class="post-meta">
                                        <span class="post-status status-<?php echo $career['status']; ?>">
                                            <?php echo ucfirst($career['status']); ?>
                                        </span>
                                        <span class="post-category">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($career['location']); ?>
                                        </span>
                                        <span class="post-category">
                                            <i class="fas fa-clock"></i>
                                            <?php echo htmlspecialchars($career['employment_type']); ?>
                                        </span>
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            Created: <?php echo date('M j, Y', strtotime($career['created_at'])); ?>
                                        </span>
                                        <?php if ($career['published_at']): ?>
                                            <span class="post-date">
                                                <i class="fas fa-clock"></i>
                                                Published: <?php echo date('M j, Y', strtotime($career['published_at'])); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="post-date text-muted">
                                                <i class="fas fa-clock"></i>
                                                Not published
                                            </span>
                                        <?php endif; ?>
                                        <span class="post-author">
                                            <i class="fas fa-user"></i>
                                            by <?php echo htmlspecialchars($career['author_name']); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <a href="create-career.php?edit=<?php echo $career['id']; ?>" class="btn btn-sm btn-primary" title="Edit Posting">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($career['status'] === 'published'): ?>
                                    <button class="btn btn-sm btn-info" onclick="copyCareerLink('<?php echo $career['slug']; ?>')" title="Copy Link">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-danger" onclick="deleteCareer(<?php echo $career['id']; ?>)" title="Delete Posting">
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
                <h3>Delete Career Posting</h3>
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Please enter your password to confirm this action.</p>
                <form id="deleteForm" method="POST">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="delete_career">
                    <input type="hidden" name="career_id" id="delete_career_id">
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="deletePassword" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Password:</label>
                        <input type="password" id="deletePassword" name="password" required 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;"
                               placeholder="Enter your password" autocomplete="current-password">
                    </div>
                    
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Posting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
        // AJAX Search and Filter
        let isLoading = false;
        let searchTimeout;
        
        const filterForm = document.getElementById('filterForm');
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const dateRangeSelect = document.getElementById('date_range');
        const careersList = document.getElementById('careersList');
        const careerCount = document.getElementById('careerCount');
        
        // Check if elements exist
        if (!filterForm || !searchInput || !statusSelect || !dateRangeSelect || !careersList || !careerCount) {
            console.error('Required elements not found');
            return;
        }
        
        // Global function to update bulk action UI
        function updateBulkActionUI() {
            const careerCheckboxes = document.querySelectorAll('.career-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            const selectedCountSpan = document.getElementById('selectedCount');
            
            if (!bulkActionSelect || !applyBulkActionBtn || !selectedCountSpan) {
                return;
            }
            
            const selected = Array.from(careerCheckboxes).filter(cb => cb.checked);
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
        
        // Function to initialize checkbox event listeners
        function initializeCareerCheckboxes() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const careerCheckboxes = document.querySelectorAll('.career-checkbox');
            const bulkActionSelect = document.getElementById('bulkActionSelect');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            
            // Select All functionality
            if (selectAllCheckbox) {
                // Remove old event listeners by cloning
                const newSelectAll = selectAllCheckbox.cloneNode(true);
                selectAllCheckbox.parentNode.replaceChild(newSelectAll, selectAllCheckbox);
                
                newSelectAll.addEventListener('change', function() {
                    careerCheckboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkActionUI();
                });
            }
            
            // Individual checkbox change
            careerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) {
                        selectAll.checked = Array.from(careerCheckboxes).every(cb => cb.checked);
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
                    const selected = Array.from(careerCheckboxes).filter(cb => cb.checked);
                    const action = bulkActionSelect.value;
                    
                    if (selected.length === 0) {
                        alert('Please select at least one career posting.');
                        return;
                    }
                    
                    if (!action) {
                        alert('Please select an action.');
                        return;
                    }
                    
                    const selectedIds = selected.map(cb => cb.value);
                    const actionNames = {
                        'delete': 'Delete',
                        'archive': 'Archive',
                        'draft': 'Move to Draft',
                        'publish': 'Publish'
                    };
                    
                    document.getElementById('bulkActionType').value = action;
                    document.getElementById('bulkSelectedIds').value = JSON.stringify(selectedIds);
                    document.getElementById('bulkActionTitle').textContent = actionNames[action] + ' Career Postings';
                    document.getElementById('bulkActionMessage').textContent = 
                        `You are about to ${actionNames[action].toLowerCase()} ${selectedIds.length} career posting(s). Please enter your password to confirm.`;
                    document.getElementById('bulkPassword').value = '';
                    document.getElementById('bulkActionModal').style.display = 'block';
                });
            }
        }
        
        // Function to load careers via AJAX
        function loadCareers() {
            if (isLoading) return;
            
            isLoading = true;
            careersList.style.opacity = '0.6';
            careersList.style.pointerEvents = 'none';
            
            const params = new URLSearchParams({
                search: searchInput.value,
                status: statusSelect.value,
                date_range: dateRangeSelect.value
            });
            
            fetch(`ajax-careers.php?${params}`)
                .then(response => {
                    // Check if response is OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    // Check content type
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('Server returned non-JSON response');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        renderCareers(data.careers);
                        careerCount.textContent = data.count;
                    } else {
                        console.error('Error:', data.error);
                        showNotification('Error loading careers: ' + (data.error || 'Unknown error'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    showNotification('Error loading careers: ' + error.message, 'error');
                })
                .finally(() => {
                    isLoading = false;
                    careersList.style.opacity = '1';
                    careersList.style.pointerEvents = 'auto';
                });
        }
        
        // Function to render careers
        function renderCareers(careers) {
            if (careers.length === 0) {
                careersList.innerHTML = '<div class="no-posts"><p>No career postings found matching your filters.</p></div>';
                return;
            }
            
            let html = '';
            careers.forEach(career => {
                const publishedDate = career.published_date ? 
                    `<span class="post-date"><i class="fas fa-clock"></i> Published: ${career.published_date}</span>` :
                    `<span class="post-date text-muted"><i class="fas fa-clock"></i> Not published</span>`;
                
                const copyButton = career.status === 'published' ?
                    `<button class="btn btn-sm btn-info" onclick="copyCareerLink('${career.slug}')" title="Copy Link"><i class="fas fa-copy"></i></button>` :
                    '';
                
                html += `
                    <div class="post-item">
                        <div style="display: flex; align-items: flex-start; gap: 1rem; width: 100%;">
                            <input type="checkbox" class="career-checkbox" value="${career.id}" style="width: 18px; height: 18px; cursor: pointer; margin-top: 0.5rem; flex-shrink: 0;">
                            <div style="flex: 1; display: flex; align-items: center; justify-content: space-between;">
                                <div class="post-info">
                                    <h3 class="post-title">
                                        <a href="create-career.php?edit=${career.id}">${escapeHtml(career.position)}</a>
                                    </h3>
                                    <div class="post-meta">
                                        <span class="post-status status-${career.status}">${capitalizeFirst(career.status)}</span>
                                        <span class="post-category"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(career.location)}</span>
                                        <span class="post-category"><i class="fas fa-clock"></i> ${escapeHtml(career.employment_type)}</span>
                                        <span class="post-date"><i class="fas fa-calendar"></i> Created: ${career.created_date}</span>
                                        ${publishedDate}
                                        <span class="post-author"><i class="fas fa-user"></i> by ${escapeHtml(career.author_name)}</span>
                                    </div>
                                </div>
                                <div class="post-actions">
                                    <a href="create-career.php?edit=${career.id}" class="btn btn-sm btn-primary" title="Edit Posting">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    ${copyButton}
                                    <button class="btn btn-sm btn-danger" onclick="deleteCareer(${career.id})" title="Delete Posting">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            careersList.innerHTML = html;
            
            // Re-initialize checkboxes after content is loaded
            initializeCareerCheckboxes();
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
            dateRangeSelect.value = '';
            loadCareers();
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
            searchTimeout = setTimeout(loadCareers, 500);
        });
        
        // Immediate filter on select change
        statusSelect.addEventListener('change', loadCareers);
        dateRangeSelect.addEventListener('change', loadCareers);
        
        // Auto-refresh careers list if redirected after creating/editing a career
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            // Small delay to ensure everything is ready before making AJAX call
            setTimeout(function() {
                // Refresh the careers list to show the newly created/edited career
                loadCareers();
            }, 100);
        }
        
        // Initialize checkboxes on page load
        initializeCareerCheckboxes();
        }); // End DOMContentLoaded
        
        // Global functions (called from HTML onclick handlers)
        function closeBulkActionModal() {
            document.getElementById('bulkActionModal').style.display = 'none';
            document.getElementById('bulkPassword').value = '';
        }
        
        function deleteCareer(careerId) {
            document.getElementById('delete_career_id').value = careerId;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Copy career link function
        function copyCareerLink(slug) {
            const baseUrl = '<?php 
                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
                $appRoot = $protocol . $_SERVER['HTTP_HOST'] . dirname(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));
                echo rtrim($appRoot, '/');
            ?>';
            const careerUrl = `${baseUrl}/career.php?slug=${slug}`;
            
            const textarea = document.createElement('textarea');
            textarea.value = careerUrl;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            
            try {
                document.execCommand('copy');
                showNotification('Career posting link copied to clipboard!', 'success');
            } catch (err) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(careerUrl).then(function() {
                        showNotification('Career posting link copied to clipboard!', 'success');
                    }).catch(function() {
                        showNotification('Failed to copy link', 'error');
                    });
                } else {
                    showNotification('Failed to copy link', 'error');
                }
            }
            
            document.body.removeChild(textarea);
        }
        
        // Show notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
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
            
            if (type === 'success') {
                notification.style.backgroundColor = '#28a745';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#dc3545';
            } else {
                notification.style.backgroundColor = '#007bff';
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translate(-50%, -50%)';
            }, 50);
            
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
            const bulkModal = document.getElementById('bulkActionModal');
            const deleteModal = document.getElementById('deleteModal');
            if (event.target === bulkModal) {
                closeBulkActionModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }
    </script>

    <style>
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


<?php
/**
 * UPHSL Posts Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays all published posts and news articles for the UPHSL website
 */

session_start();
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Check if Posts section is in maintenance
if (isSectionInMaintenance('posts')) {
    $page_title = "Posts - Maintenance";
    $base_path = '';
    include 'app/includes/header.php';
    $maintenance_message = getSectionMaintenanceMessage('posts');
    ?>
    <main class="main-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 4rem 2rem;">
        <div class="maintenance-message" style="text-align: center; max-width: 600px; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <i class="fas fa-tools" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1.5rem;"></i>
            <h1 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;">Under Maintenance</h1>
            <p style="font-size: 1.1rem; color: #666; line-height: 1.6; margin-bottom: 2rem;"><?php echo htmlspecialchars($maintenance_message); ?></p>
            <a href="<?php echo $base_path; ?>index.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: var(--primary-color); color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-home"></i>
                Go to Homepage
            </a>
        </div>
    </main>
    <?php include 'app/includes/footer.php'; ?>
    <?php exit; }

// Get current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Get filter parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$dateRange = $_GET['date_range'] ?? '';
$specificDate = $_GET['specific_date'] ?? '';

// Normalize category to integer if it's numeric (for consistent comparison)
$categoryId = null;
if (!empty($category)) {
    if (is_numeric($category)) {
        $categoryId = (int)$category;
    }
}

// Get posts for current page with filters
$posts_per_page = (int)getSetting('posts_per_page', '12');
$posts = getPublishedPostsWithFilters($page, $posts_per_page, $search, $category, $dateRange, $specificDate);
$totalPosts = getPublishedPostsCountWithFilters($search, $category, $dateRange, $specificDate);
$totalPages = ceil($totalPosts / $posts_per_page);

// Get all categories from database for filter dropdown
$allCategories = getAllCategories();

// Get category name for display
$categoryName = '';
if (!empty($category)) {
    if (is_numeric($category)) {
        $cat = getCategoryById($category);
        $categoryName = $cat ? $cat['name'] : '';
    } else {
        $cat = getCategoryByName($category);
        $categoryName = $cat ? $cat['name'] : $category;
    }
}

// Set page title
if (!empty($categoryName)) {
    $page_title = $categoryName . " Posts - University of Perpetual Help System";
} else {
    $page_title = "All Posts - University of Perpetual Help System";
}

// Set base path for assets
$base_path = '';

// Add posts-specific CSS
$additional_css = ['assets/css/posts.css'];

// Include header
include 'app/includes/header.php';
?>

<!-- Posts Content -->
    <div class="posts-container">
        <div class="posts-header">
            <h1 class="posts-title">
                <i class="fas fa-newspaper"></i>
                <?php if (!empty($categoryName)): ?>
                    <?php echo htmlspecialchars($categoryName); ?> News & Announcements
                <?php else: ?>
                    University News & Announcements
                <?php endif; ?>
            </h1>
            <p class="posts-subtitle">
                <?php if (!empty($categoryName)): ?>
                    Stay updated with the latest news and announcements from <?php echo htmlspecialchars($categoryName); ?>.
                <?php else: ?>
                    Stay updated with the latest news and announcements from the University of Perpetual Help System Laguna.
                <?php endif; ?>
            </p>
        </div>


        <!-- Search and Filter System -->
        <div class="posts-filter">
            <form method="GET" class="filter-form" id="filterForm">
                <!-- Desktop: All in one row, Mobile: Search separate, filters collapsible -->
                <div class="filter-row-main">
                    <!-- Search Bar - Always Visible -->
                    <div class="search-group">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" id="searchInput" 
                                   placeholder="Search posts..." 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <!-- Filter Toggle Button (Mobile Only) -->
                    <button type="button" class="filter-toggle-btn" id="filterToggleBtn" style="display: none; width: 100%; padding: 0.5rem; background: #f8f9fa; border: 2px solid #e0e0e0; border-radius: 6px; font-weight: 600; cursor: pointer; margin-bottom: 0.75rem; font-size: 0.8125rem; color: var(--text-dark); font-family: 'Barlow Semi Condensed', sans-serif;">
                        <i class="fas fa-filter"></i>
                        <span class="filter-toggle-text">Show Filters</span>
                        <i class="fas fa-chevron-down filter-toggle-icon" style="float: right; transition: transform 0.3s ease;"></i>
                    </button>
                    
                    <!-- Filter Row - Collapsible on Mobile -->
                    <div class="filter-row filter-row-collapsible">
                    <div class="filter-group">
                        <select name="category" id="categoryFilter">
                            <option value="">All Categories</option>
                            <?php
                            // Organize categories for display (deduplicate by name first)
                            $programCats = ['basic' => [], 'other' => []];
                            $supportCats = [];
                            $programNames = ['Senior High School', 'Junior High School', 'Grade School', 'Aviation', 'Arts & Sciences', 'Business & Accountancy', 'Computer Studies', 'Criminology', 'Education', 'Engineering & Architecture', 'International Hospitality Management', 'Maritime', 'Law/Juris Doctor', 'Graduate School'];
                            $supportNames = ['Careers', 'University Clinic', 'Community Outreach Department', 'International & External Affairs', 'Student Personnel Services', 'Library', 'Quality Assurance', 'Research'];
                            
                            // Deduplicate categories by name (keep first occurrence)
                            $seenNames = [];
                            $uniqueCategories = [];
                            foreach ($allCategories as $cat) {
                                if (!in_array($cat['name'], $seenNames)) {
                                    $seenNames[] = $cat['name'];
                                    $uniqueCategories[] = $cat;
                                }
                            }
                            
                            // Organize unique categories
                            foreach ($uniqueCategories as $cat) {
                                if (in_array($cat['name'], $programNames)) {
                                    if (in_array($cat['name'], ['Senior High School', 'Junior High School', 'Grade School'])) {
                                        $programCats['basic'][] = $cat;
                                    } else {
                                        $programCats['other'][] = $cat;
                                    }
                                } elseif (in_array($cat['name'], $supportNames)) {
                                    $supportCats[] = $cat;
                                }
                            }
                            ?>
                            <?php if (!empty($programCats['basic'])): ?>
                                <optgroup label="Programs - Basic Education">
                                    <?php foreach ($programCats['basic'] as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (($categoryId !== null && $categoryId === (int)$cat['id']) || ($category === $cat['name'])) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($programCats['other'])): ?>
                                <optgroup label="Programs - Other">
                                    <?php foreach ($programCats['other'] as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (($categoryId !== null && $categoryId === (int)$cat['id']) || ($category === $cat['name'])) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($supportCats)): ?>
                                <optgroup label="Support Services">
                                    <?php foreach ($supportCats as $cat): ?>
                                        <option value="<?php echo $cat['id']; ?>" <?php echo (($categoryId !== null && $categoryId === (int)$cat['id']) || ($category === $cat['name'])) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <select name="date_range" id="dateRangeFilter">
                            <option value="">All Time</option>
                            <option value="today" <?php echo (($_GET['date_range'] ?? '') === 'today') ? 'selected' : ''; ?>>Today</option>
                            <option value="week" <?php echo (($_GET['date_range'] ?? '') === 'week') ? 'selected' : ''; ?>>This Week</option>
                            <option value="month" <?php echo (($_GET['date_range'] ?? '') === 'month') ? 'selected' : ''; ?>>This Month</option>
                            <option value="year" <?php echo (($_GET['date_range'] ?? '') === 'year') ? 'selected' : ''; ?>>This Year</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <input type="date" name="specific_date" id="specificDateFilter" 
                               value="<?php echo htmlspecialchars($_GET['specific_date'] ?? ''); ?>"
                               placeholder="Select Date">
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn" style="font-family: 'Barlow Semi Condensed', sans-serif;">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        <button type="button" class="clear-btn" id="clearFilters" style="font-family: 'Barlow Semi Condensed', sans-serif;">
                            <i class="fas fa-times"></i>
                            Clear
                        </button>
                    </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="posts-content">
            <div id="searchResults">
                <?php if (!empty($posts)): ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <div class="post-card-image">
                                    <?php if (!empty($post['featured_image'])):
                                        // Build absolute path when app is in a subdirectory (e.g. /uphsledu)
                                        $featured = $post['featured_image'];
                                        $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
                                        if ($scriptDir === '/' || $scriptDir === '.') {
                                            $scriptDir = '';
                                        }
                                        if (strpos($featured, 'http') !== 0) {
                                            if (strpos($featured, '/') === 0) {
                                                // leading slash: prepend script dir if not root
                                                $featured = ($scriptDir ? $scriptDir : '') . $featured;
                                            } else {
                                                $featured = ($scriptDir ? $scriptDir . '/' : '/') . $featured;
                                            }
                                        }
                                    ?>
                                        <img src="<?php echo htmlspecialchars($featured); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>"
                                             class="card-image"
                                             decoding="async">
                                    <?php else: ?>
                                        <div class="card-image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-card-overlay">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-btn">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="post-card-content">
                                    <h2 class="post-card-title">
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="post-card-meta">
                                        <span class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($post['published_at'] ?: $post['created_at']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="post-card-footer">
                                        <div class="post-stats">
                                            <span class="post-views">
                                                <i class="fas fa-eye"></i>
                                                <?php echo $post['views']; ?> views
                                            </span>
                                        </div>
                                        <a href="post.php?slug=<?php echo $post['slug']; ?>" class="read-more-link">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="#" class="pagination-btn prev-btn" data-page="<?php echo $page - 1; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <div class="pagination-numbers">
                                    <?php
                                    $startPage = max(1, $page - 2);
                                    $endPage = min($totalPages, $page + 2);
                                    
                                    if ($startPage > 1): ?>
                                        <a href="#" class="pagination-number" data-page="1">1</a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="#" class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>

                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="pagination-ellipsis">...</span>
                                        <?php endif; ?>
                                        <a href="#" class="pagination-number" data-page="<?php echo $totalPages; ?>">
                                            <?php echo $totalPages; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php if ($page < $totalPages): ?>
                                    <a href="#" class="pagination-btn next-btn" data-page="<?php echo $page + 1; ?>">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pagination-info">
                                <p>Showing <?php echo (($page - 1) * 12) + 1; ?>-<?php echo min($page * 12, $totalPosts); ?> of <?php echo $totalPosts; ?> posts</p>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-posts">
                        <div class="empty-posts-content">
                            <i class="fas fa-newspaper"></i>
                            <h3>No Posts Available</h3>
                            <p>There are no published posts at the moment. Check back later for updates.</p>
                            <a href="index.php" class="btn btn-primary">Go to Homepage</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
                    </div>
                </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const dateRangeFilter = document.getElementById('dateRangeFilter');
    const specificDateFilter = document.getElementById('specificDateFilter');
    const searchResults = document.getElementById('searchResults');
    const filterBtn = document.querySelector('.filter-btn');
    const filterToggleBtn = document.getElementById('filterToggleBtn');
    const filterRow = document.querySelector('.filter-row-collapsible');
    
    // Mobile filter toggle functionality
    if (filterToggleBtn && filterRow) {
        // Check if any filters are active
        const hasActiveFilters = (categoryFilter && categoryFilter.value) || dateRangeFilter.value || specificDateFilter.value;
        
        // If filters are active, expand by default
        if (hasActiveFilters) {
            filterRow.classList.add('expanded');
            filterToggleBtn.classList.add('active');
            filterToggleBtn.querySelector('.filter-toggle-text').textContent = 'Hide Filters';
        }
        
        filterToggleBtn.addEventListener('click', function() {
            filterRow.classList.toggle('expanded');
            filterToggleBtn.classList.toggle('active');
            
            if (filterRow.classList.contains('expanded')) {
                filterToggleBtn.querySelector('.filter-toggle-text').textContent = 'Hide Filters';
            } else {
                filterToggleBtn.querySelector('.filter-toggle-text').textContent = 'Show Filters';
            }
        });
    }
    
    // Initialize current page from PHP
    let currentPage = <?php echo $page; ?>;
    let isLoading = false;
    
    // Search with AJAX
    function performSearch(page = 1) {
        if (isLoading) return;
        
        isLoading = true;
        currentPage = page;
        
        // Show loading state
        const originalBtnText = filterBtn ? filterBtn.innerHTML : '';
        if (filterBtn) {
            filterBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
            filterBtn.disabled = true;
        }
        
        // Add loading overlay to results
        searchResults.style.opacity = '0.6';
        searchResults.style.pointerEvents = 'none';
        
        // Build URL parameters, only including non-empty values
        const urlParams = new URLSearchParams();
        
        if (searchInput.value.trim()) {
            urlParams.append('search', searchInput.value.trim());
        }
        if (categoryFilter && categoryFilter.value) {
            urlParams.append('category', categoryFilter.value);
        }
        if (dateRangeFilter.value) {
            urlParams.append('date_range', dateRangeFilter.value);
        }
        if (specificDateFilter.value) {
            urlParams.append('specific_date', specificDateFilter.value);
        }
        if (page > 1) {
            urlParams.append('page', page);
        }
        
        // Build params for AJAX request (always include page for server)
        const ajaxParams = new URLSearchParams({
            search: searchInput.value,
            category: categoryFilter ? categoryFilter.value : '',
            date_range: dateRangeFilter.value,
            specific_date: specificDateFilter.value,
            page: page
        });
        
        // Update URL without page reload (only include non-empty params, hide .php extension)
        const queryString = urlParams.toString();
        const newUrl = queryString ? `posts?${queryString}` : 'posts';
        window.history.pushState({ page: page }, '', newUrl);
        
        fetch(`ajax-search.php?${ajaxParams}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    searchResults.innerHTML = data.html;
                    
                    // Scroll to top of results smoothly after a brief delay to ensure DOM is updated
                    setTimeout(() => {
                        const postsContent = document.querySelector('.posts-content');
                        if (postsContent) {
                            const offset = 120; // Offset from top for header
                            const elementPosition = postsContent.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - offset;
                            
                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }, 50);
                    
                    // Re-attach pagination event listeners
                    attachPaginationListeners();
                    
                    // Initialize image loading for new results
                    initializeImageLoading();
                    
                    // Highlight search terms
                    if (searchInput.value.trim()) {
                        highlightSearchTerms(searchInput.value.trim());
                    }
                } else {
                    searchResults.innerHTML = `<div class="empty-posts">
                        <div class="empty-posts-content">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Search Error</h3>
                            <p>An error occurred while searching. Please try again.</p>
                        </div>
                    </div>`;
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = `<div class="empty-posts">
                    <div class="empty-posts-content">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Search Error</h3>
                        <p>Network error: ${error.message}</p>
                    </div>
                </div>`;
            })
            .finally(() => {
                isLoading = false;
                if (filterBtn) {
                    filterBtn.innerHTML = originalBtnText;
                    filterBtn.disabled = false;
                }
                searchResults.style.opacity = '1';
                searchResults.style.pointerEvents = 'auto';
            });
    }
    
    // Attach pagination event listeners
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination-number, .pagination-btn');
        paginationLinks.forEach(link => {
            // Remove existing listeners to prevent duplicates
            const newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);
            
            newLink.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.dataset.page);
                if (!isNaN(page) && page !== currentPage) {
                    performSearch(page);
                }
            });
        });
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        const urlParams = new URLSearchParams(window.location.search);
        const page = parseInt(urlParams.get('page')) || 1;
        const search = urlParams.get('search') || '';
        const category = urlParams.get('category') || '';
        const dateRange = urlParams.get('date_range') || '';
        const specificDate = urlParams.get('specific_date') || '';
        
        // Update form values
        searchInput.value = search;
        if (categoryFilter) categoryFilter.value = category;
        dateRangeFilter.value = dateRange;
        specificDateFilter.value = specificDate;
        
        // Perform search with the page from URL
        performSearch(page);
    });
    
    // Search input with debounce
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(1); // Reset to page 1 when searching
        }, 300);
    });
    
    // Category filter
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            performSearch(1); // Reset to page 1 when filtering
        });
    }
    
    // Date range filter
    dateRangeFilter.addEventListener('change', function() {
        // Clear specific date when using date range
        specificDateFilter.value = '';
        performSearch(1); // Reset to page 1 when filtering
    });
    
    // Specific date filter
    specificDateFilter.addEventListener('change', function() {
        // Clear date range when using specific date
        dateRangeFilter.value = '';
        performSearch(1); // Reset to page 1 when filtering
    });
    
    // Prevent form submission and use AJAX instead
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch(1);
        });
    }
    
    // Manual filter button
    if (filterBtn) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            performSearch(1);
        });
    }
    
    // Clear filters button
    const clearBtn = document.getElementById('clearFilters');
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Clear the form inputs
            searchInput.value = '';
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) categoryFilter.value = '';
            dateRangeFilter.value = '';
            specificDateFilter.value = '';
            
            // Perform search with empty filters
            performSearch(1);
        });
    }
    
    
    // Highlight search terms
    function highlightSearchTerms(term) {
        const postTitles = document.querySelectorAll('.post-card-title a');
        const postContents = document.querySelectorAll('.post-card-excerpt');
        
        const regex = new RegExp(`(${term})`, 'gi');
        
        postTitles.forEach(title => {
            title.innerHTML = title.innerHTML.replace(regex, '<mark>$1</mark>');
        });
        
        postContents.forEach(content => {
            content.innerHTML = content.innerHTML.replace(regex, '<mark>$1</mark>');
        });
        
        // Add CSS for highlighted terms if not already added
        if (!document.querySelector('#highlight-styles')) {
            const style = document.createElement('style');
            style.id = 'highlight-styles';
            style.textContent = `
                mark {
                    background-color: #fef3cd;
                    color: #856404;
                    padding: 2px 4px;
                    border-radius: 3px;
                    font-weight: 600;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Initial pagination setup
    attachPaginationListeners();
    
    // Highlight initial search term if present
    const initialSearch = '<?php echo addslashes($search); ?>';
    if (initialSearch) {
        highlightSearchTerms(initialSearch);
    }
    
    // Initialize image loading with shimmer effect
    initializeImageLoading();
});

// Image loading with shimmer effect
function initializeImageLoading() {
    const postImages = document.querySelectorAll('.card-image');
    
    postImages.forEach(function(img, index) {
        // Add loading class to container
        const container = img.closest('.post-card-image');
        if (container) {
            container.classList.add('loading');
        }
        
        // Add staggered delay for multiple images
        const delay = index * 100; // 100ms delay between each image
        
        if (img.complete) {
            setTimeout(() => {
                img.classList.add('loaded');
                if (container) {
                    container.classList.remove('loading');
                }
            }, delay);
        } else {
            img.addEventListener('load', function() {
                setTimeout(() => {
                    this.classList.add('loaded');
                    if (container) {
                        container.classList.remove('loading');
                    }
                }, delay);
            });
            img.addEventListener('error', function() {
                setTimeout(() => {
                    this.classList.add('loaded'); // Show alt text if image fails to load
                    if (container) {
                        container.classList.remove('loading');
                    }
                }, delay);
            });
        }
    });
}
</script>

<?php include 'app/includes/footer.php'; ?>

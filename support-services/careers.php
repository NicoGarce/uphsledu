<?php
/**
 * UPHSL Careers Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Career opportunities and job listings at the University of Perpetual Help System Laguna
 */
session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or Support Services section is in maintenance
if (isSectionInMaintenance('support-services', 'careers') || isSectionInMaintenance('support-services')) {
    $page_title = "Careers - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('support-services', $base_path, 'careers')) {
        include '../app/includes/footer.php';
        exit;
    }
}

$page_title = "Careers - UPHSL";
// Set base path for subdirectory
$base_path = '../';
// Include header
include '../app/includes/header.php';
?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- News Carousel Section -->
        <?php
        $categoryId = 'Careers'; // Pass category name, component will look it up
        $sectionTitle = 'Careers News & Updates';
        $sectionDescription = 'Stay updated with the latest news and announcements from the Careers department.';
        include '../app/includes/news-carousel.php';
        ?>

        <!-- Career Postings Banner -->
        <section class="page-hero">
            <div class="container">
                <div class="content">
                    <h1 class="title">Career Opportunities</h1>
                    <p class="subtitle">Explore exciting career opportunities at the University of Perpetual Help System Laguna</p>
                </div>
            </div>
        </section>

        <!-- Career Postings Section -->
        <section class="careers-section" style="padding: 4rem 2rem; background: #f8f9fa; position: relative;">
            <div class="container" style="max-width: 1200px; margin: 0 auto;">

                <?php
                // Get filter parameters
                $search = $_GET['search'] ?? '';
                $location = $_GET['location'] ?? '';
                $employmentType = $_GET['employment_type'] ?? '';
                $dateRange = $_GET['date_range'] ?? '';
                $specificDate = $_GET['specific_date'] ?? '';
                
                // Pagination
                $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                $limit = 12;
                
                // Get career postings with filters
                $careerPostings = getPublishedCareerPostingsWithFilters($page, $limit, $search, $location, $employmentType, $dateRange, $specificDate);
                $totalCareers = getPublishedCareerPostingsCountWithFilters($search, $location, $employmentType, $dateRange, $specificDate);
                $totalPages = ceil($totalCareers / $limit);
                
                // Get filter options
                $locations = getCareerLocations();
                $employmentTypes = getCareerEmploymentTypes();
                ?>

                <!-- Search and Filter System -->
                <div class="careers-filter" style="background: white; border-radius: 8px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 25px;">
                    <form method="GET" class="filter-form" id="careersFilterForm">
                        <div class="filter-row" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
                            <div class="search-group" style="flex: 1; min-width: 200px;">
                                <div class="search-input-wrapper" style="position: relative;">
                                    <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #999; font-size: 0.875rem;"></i>
                                    <input type="text" name="search" id="careersSearchInput" 
                                           placeholder="Search careers..." 
                                           value="<?php echo htmlspecialchars($search); ?>"
                                           style="width: 100%; padding: 0.5rem 0.5rem 0.5rem 2rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.875rem; transition: border-color 0.3s ease;">
                                </div>
                            </div>
                            
                            <div class="filter-group" style="flex: 0 0 auto;">
                                <label style="display: block; margin-bottom: 0.375rem; font-weight: 600; color: var(--text-dark); font-size: 0.8125rem;">Location</label>
                                <select name="location" id="locationFilter" style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.875rem; min-width: 130px; cursor: pointer;">
                                    <option value="">All Locations</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?php echo htmlspecialchars($loc); ?>" <?php echo ($location === $loc) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($loc); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group" style="flex: 0 0 auto;">
                                <label style="display: block; margin-bottom: 0.375rem; font-weight: 600; color: var(--text-dark); font-size: 0.8125rem;">Employment Type</label>
                                <select name="employment_type" id="employmentTypeFilter" style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.875rem; min-width: 130px; cursor: pointer;">
                                    <option value="">All Types</option>
                                    <?php foreach ($employmentTypes as $type): ?>
                                        <option value="<?php echo htmlspecialchars($type); ?>" <?php echo ($employmentType === $type) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($type); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="filter-group" style="flex: 0 0 auto;">
                                <label style="display: block; margin-bottom: 0.375rem; font-weight: 600; color: var(--text-dark); font-size: 0.8125rem;">Date Range</label>
                                <select name="date_range" id="dateRangeFilter" style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.875rem; min-width: 130px; cursor: pointer;">
                                    <option value="">All Time</option>
                                    <option value="today" <?php echo ($dateRange === 'today') ? 'selected' : ''; ?>>Today</option>
                                    <option value="week" <?php echo ($dateRange === 'week') ? 'selected' : ''; ?>>This Week</option>
                                    <option value="month" <?php echo ($dateRange === 'month') ? 'selected' : ''; ?>>This Month</option>
                                    <option value="year" <?php echo ($dateRange === 'year') ? 'selected' : ''; ?>>This Year</option>
                                </select>
                            </div>
                            
                            <div class="filter-group" style="flex: 0 0 auto;">
                                <label style="display: block; margin-bottom: 0.375rem; font-weight: 600; color: var(--text-dark); font-size: 0.8125rem;">Specific Date</label>
                                <input type="date" name="specific_date" id="specificDateFilter" 
                                       value="<?php echo htmlspecialchars($specificDate); ?>"
                                       placeholder="Select Date"
                                       style="padding: 0.5rem; border: 2px solid #e0e0e0; border-radius: 6px; font-size: 0.875rem; min-width: 130px;">
                            </div>
                            
                            <div class="filter-actions" style="display: flex; gap: 0.5rem;">
                                <button type="submit" class="filter-btn" style="padding: 0.5rem 1.25rem; background: var(--primary-color); color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.3s ease; font-size: 0.875rem;">
                                    <i class="fas fa-filter"></i>
                                    Filter
                                </button>
                                <button type="button" class="clear-btn" id="clearCareerFilters" style="padding: 0.5rem 1.25rem; background: #f0f0f0; color: var(--text-dark); border: 2px solid #e0e0e0; border-radius: 6px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.3s ease; font-size: 0.875rem;">
                                    <i class="fas fa-times"></i>
                                    Clear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="careersResults">
                    <?php if (empty($careerPostings)): ?>
                        <div class="empty-careers" style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            <i class="fas fa-briefcase" style="font-size: 4rem; color: #ddd; margin-bottom: 1.5rem;"></i>
                            <h3 style="color: #666; margin-bottom: 1rem;">No Job Postings Found</h3>
                            <p style="color: #999;"><?php echo !empty($search) || !empty($location) || !empty($employmentType) ? 'No careers match your search criteria. Try adjusting your filters.' : 'Check back soon for new career opportunities.'; ?></p>
                        </div>
                    <?php else: ?>
                        <div class="careers-grid">
                            <?php foreach ($careerPostings as $posting): ?>
                                <div class="career-card">
                                    <div class="career-header">
                                        <h3 class="career-title">
                                            <a href="../career.php?slug=<?php echo htmlspecialchars($posting['slug']); ?>">
                                                <?php echo htmlspecialchars($posting['position']); ?>
                                            </a>
                                        </h3>
                                        <div class="career-meta">
                                            <span class="career-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?php echo htmlspecialchars($posting['location']); ?>
                                            </span>
                                            <span class="career-type">
                                                <i class="fas fa-clock"></i>
                                                <?php echo htmlspecialchars($posting['employment_type']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="career-footer">
                                        <span class="career-date">
                                            <i class="fas fa-calendar"></i>
                                            <?php echo formatDate($posting['published_at'] ?: $posting['created_at']); ?>
                                        </span>
                                        <a href="../career.php?slug=<?php echo htmlspecialchars($posting['slug']); ?>" class="btn btn-primary career-btn">
                                            View
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="careers-pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="pagination-btn pagination-prev" data-page="<?php echo $page - 1; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </a>
                                <?php else: ?>
                                    <span class="pagination-btn pagination-prev disabled">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </span>
                                <?php endif; ?>

                                <div class="pagination-numbers">
                                    <?php
                                    $startPage = max(1, $page - 2);
                                    $endPage = min($totalPages, $page + 2);
                                    
                                    if ($startPage > 1): ?>
                                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="pagination-number" data-page="1">1</a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="pagination-dots">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="pagination-number <?php echo $i == $page ? 'active' : ''; ?>" data-page="<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="pagination-dots">...</span>
                                        <?php endif; ?>
                                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>" class="pagination-number" data-page="<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                                    <?php endif; ?>
                                </div>

                                <?php if ($page < $totalPages): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="pagination-btn pagination-next" data-page="<?php echo $page + 1; ?>">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="pagination-btn pagination-next disabled">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <style>
        /* Page Hero Banner */
        .page-hero {
            position: relative;
            padding: 80px 0;
            color: #fff;
            text-align: center;
            isolation: isolate;
            overflow: hidden;
            background: url('../assets/images/FACADE.jpg') center/cover no-repeat;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85));
            z-index: 1;
        }

        .page-hero .content {
            position: relative;
            z-index: 2;
            display: inline-block;
            padding: 24px 28px;
            border-radius: 16px;
            background: rgba(0,0,0,.55);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            box-shadow: 0 16px 40px rgba(0,0,0,.35);
        }

        .page-hero .title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 18px;
            text-shadow: 2px 2px 4px rgba(0,0,0,.3);
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .page-hero .subtitle {
            font-size: 1.05rem;
            margin: 0;
        }

        .careers-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--alt-color-1), var(--secondary-color));
        }

        /* Filter Form Styling */
        .careers-filter .search-input-wrapper input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .careers-filter .filter-group select:focus,
        .careers-filter .filter-group input[type="date"]:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .careers-filter .filter-btn:hover {
            background: var(--secondary-color) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(28, 77, 161, 0.3);
        }

        .careers-filter .clear-btn:hover {
            background: #e0e0e0 !important;
            border-color: #ccc;
        }

        .careers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .career-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .career-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .career-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .career-title {
            font-size: 1.25rem;
            color: var(--primary-color);
            margin-bottom: 0.75rem;
            line-height: 1.3;
            font-family: 'Barlow Semi Condensed', sans-serif;
        }

        .career-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .career-title a:hover {
            color: var(--secondary-color);
        }

        .career-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.875rem;
            color: #666;
        }

        .career-location,
        .career-type {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .career-location i,
        .career-type i {
            color: var(--primary-color);
            font-size: 0.75rem;
        }

        .career-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            margin-top: auto;
        }

        .career-date {
            font-size: 0.8125rem;
            color: #999;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .career-date i {
            font-size: 0.75rem;
        }

        .career-btn {
            padding: 0.5rem 1.25rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .career-btn:hover {
            transform: translateX(3px);
        }

        /* Pagination */
        .careers-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }

        .pagination-btn {
            padding: 0.75rem 1.5rem;
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
        }

        .pagination-btn:hover:not(.disabled) {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(28, 77, 161, 0.3);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #ccc;
            color: #999;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-number {
            padding: 0.75rem 1rem;
            background: white;
            border: 2px solid #e0e0e0;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            min-width: 44px;
            text-align: center;
            transition: all 0.3s ease;
            font-size: 0.9375rem;
        }

        .pagination-number:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .pagination-number.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination-dots {
            padding: 0.75rem 0.5rem;
            color: #999;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .careers-filter .filter-row {
                gap: 0.625rem;
            }

            .careers-filter .filter-group {
                min-width: 120px !important;
            }

            .careers-filter .search-group {
                min-width: 180px;
            }
        }

        @media (max-width: 1024px) {
            .page-hero {
                padding: 60px 0;
            }

            .page-hero .content {
                padding: 16px 18px;
                border-radius: 12px;
            }

            .page-hero .title {
                font-size: 2.2rem;
            }

            .page-hero .subtitle {
                font-size: 1rem;
            }

            .careers-filter .filter-row {
                gap: 0.75rem;
            }

            .careers-filter .filter-group {
                min-width: 140px !important;
            }

            .careers-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 900px) {
            .careers-filter .filter-row {
                flex-wrap: wrap;
            }

            .careers-filter .search-group {
                flex: 1 1 100%;
                min-width: 100%;
            }

            .careers-filter .filter-group {
                flex: 1 1 calc(50% - 0.375rem);
                min-width: calc(50% - 0.375rem) !important;
            }

            .careers-filter .filter-actions {
                flex: 1 1 100%;
                width: 100%;
                margin-top: 0.25rem;
            }
        }

        @media (max-width: 768px) {
            .page-hero {
                padding: 50px 0;
            }

            .page-hero .content {
                padding: 14px 16px;
                border-radius: 10px;
            }

            .page-hero .title {
                font-size: 1.8rem;
                margin-bottom: 12px;
            }

            .page-hero .subtitle {
                font-size: 0.9rem;
            }

            .careers-section {
                padding: 3rem 1.5rem !important;
            }

            .careers-filter {
                padding: 1rem !important;
            }

            .careers-filter .filter-row {
                flex-direction: column !important;
                gap: 0.75rem !important;
            }

            .careers-filter .search-group,
            .careers-filter .filter-group {
                width: 100% !important;
                min-width: 100% !important;
                flex: 1 1 100% !important;
            }

            .careers-filter .filter-actions {
                width: 100%;
                justify-content: stretch;
            }

            .careers-filter .filter-btn,
            .careers-filter .clear-btn {
                flex: 1;
            }

            .careers-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .career-card {
                padding: 1.5rem;
            }

            .career-title {
                font-size: 1.125rem;
            }

            .careers-pagination {
                gap: 0.5rem;
            }

            .pagination-btn {
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
            }

            .pagination-number {
                padding: 0.625rem 0.875rem;
                min-width: 40px;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .page-hero {
                padding: 40px 0;
            }

            .page-hero .content {
                padding: 12px 14px;
                border-radius: 8px;
            }

            .page-hero .title {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .page-hero .subtitle {
                font-size: 0.85rem;
            }

            .careers-section {
                padding: 2.5rem 1rem !important;
            }

            .career-card {
                padding: 1.25rem;
            }

            .career-title {
                font-size: 1rem;
            }

            .career-meta {
                font-size: 0.8125rem;
                gap: 0.75rem;
            }

            .pagination-numbers {
                gap: 0.25rem;
            }

            .pagination-number {
                padding: 0.5rem 0.75rem;
                min-width: 36px;
                font-size: 0.8125rem;
            }

            .pagination-btn {
                padding: 0.5rem 1rem;
                font-size: 0.8125rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('careersFilterForm');
            const searchInput = document.getElementById('careersSearchInput');
            const locationFilter = document.getElementById('locationFilter');
            const employmentTypeFilter = document.getElementById('employmentTypeFilter');
            const dateRangeFilter = document.getElementById('dateRangeFilter');
            const specificDateFilter = document.getElementById('specificDateFilter');
            const clearFiltersBtn = document.getElementById('clearCareerFilters');
            const careersResults = document.getElementById('careersResults');
            
            let searchTimeout;
            let currentPage = 1;
            let isSearching = false;
            
            // Perform AJAX search
            function performSearch(page = 1) {
                if (isSearching) return;
                
                isSearching = true;
                currentPage = page;
                
                const params = new URLSearchParams();
                if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
                if (locationFilter.value) params.set('location', locationFilter.value);
                if (employmentTypeFilter.value) params.set('employment_type', employmentTypeFilter.value);
                if (dateRangeFilter.value) params.set('date_range', dateRangeFilter.value);
                if (specificDateFilter.value) params.set('specific_date', specificDateFilter.value);
                params.set('page', page);
                
                // Show loading state
                careersResults.innerHTML = '<div style="text-align: center; padding: 3rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i><p style="margin-top: 1rem; color: #666;">Loading careers...</p></div>';
                
                fetch(`../ajax-careers-search.php?${params.toString()}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            careersResults.innerHTML = data.html;
                            
                            // Re-attach pagination event listeners
                            attachPaginationListeners();
                            
                            // Update URL without reload
                            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                            window.history.pushState({}, '', newUrl);
                        } else {
                            careersResults.innerHTML = '<div class="empty-careers" style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"><i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #ddd; margin-bottom: 1.5rem;"></i><h3 style="color: #666; margin-bottom: 1rem;">Search Error</h3><p style="color: #999;">An error occurred while searching. Please try again.</p></div>';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        careersResults.innerHTML = '<div class="empty-careers" style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"><i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #ddd; margin-bottom: 1.5rem;"></i><h3 style="color: #666; margin-bottom: 1rem;">Search Error</h3><p style="color: #999;">An error occurred while searching. Please try again.</p></div>';
                    })
                    .finally(() => {
                        isSearching = false;
                    });
            }
            
            // Attach pagination event listeners
            function attachPaginationListeners() {
                const paginationLinks = careersResults.querySelectorAll('.pagination-btn:not(.disabled), .pagination-number');
                paginationLinks.forEach(link => {
                    // Remove existing listeners to prevent duplicates
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                    
                    newLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        if (page) {
                            performSearch(parseInt(page));
                            // Scroll to top of results
                            careersResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        } else {
                            // Fallback: try to get page from href
                            const href = this.getAttribute('href');
                            if (href) {
                                const urlParams = new URLSearchParams(href.split('?')[1]);
                                const pageFromUrl = urlParams.get('page');
                                if (pageFromUrl) {
                                    performSearch(parseInt(pageFromUrl));
                                    careersResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            }
                        }
                    });
                });
            }
            
            // Also attach listeners to initial pagination (before AJAX)
            function attachInitialPaginationListeners() {
                const initialPagination = document.querySelectorAll('.careers-pagination .pagination-btn:not(.disabled), .careers-pagination .pagination-number');
                initialPagination.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        if (page) {
                            performSearch(parseInt(page));
                            careersResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            }
            
            // Search input with debounce
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch(1); // Reset to page 1 when searching
                    }, 500);
                });
            }
            
            // Filter change events
            if (locationFilter) {
                locationFilter.addEventListener('change', function() {
                    performSearch(1); // Reset to page 1 when filtering
                });
            }
            
            if (employmentTypeFilter) {
                employmentTypeFilter.addEventListener('change', function() {
                    performSearch(1); // Reset to page 1 when filtering
                });
            }
            
            if (dateRangeFilter) {
                dateRangeFilter.addEventListener('change', function() {
                    performSearch(1); // Reset to page 1 when filtering
                });
            }
            
            if (specificDateFilter) {
                specificDateFilter.addEventListener('change', function() {
                    performSearch(1); // Reset to page 1 when filtering
                });
            }
            
            // Clear filters
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    locationFilter.value = '';
                    employmentTypeFilter.value = '';
                    dateRangeFilter.value = '';
                    specificDateFilter.value = '';
                    performSearch(1); // Reset to page 1 when clearing
                });
            }
            
            // Form submission
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    performSearch(1);
                });
            }
            
            // Attach initial pagination listeners
            attachInitialPaginationListeners();
        });
    </script>

<?php
// Include footer
include '../app/includes/footer.php';
?>

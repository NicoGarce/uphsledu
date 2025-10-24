<?php
/**
 * AJAX Navbar Search Endpoint
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description AJAX endpoint for navbar search - only public content
 */

session_start();
require_once 'app/config/paths.php';
require_once 'app/config/database.php';
require_once 'app/includes/functions.php';

// Get base path
$base_path = $GLOBALS['base_path'];

// Set content type to JSON
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Get search query
    $query = trim($_GET['q'] ?? '');
    
    
    if (empty($query) || strlen($query) < 2) {
        echo json_encode([
            'success' => true,
            'results' => [],
            'total' => 0
        ]);
        exit;
    }
    
    $pdo = getDBConnection();
    $results = [];
    
    // Search published posts (public content only)
    $postsSql = "SELECT 'post' as type, id, title, slug, published_at as date, 'posts' as category
                 FROM posts 
                 WHERE status = 'published' 
                 AND (title LIKE ? OR content LIKE ?)
                 ORDER BY published_at DESC 
                 LIMIT 5";
    
    $searchTerm = "%{$query}%";
    $postsStmt = $pdo->prepare($postsSql);
    $postsStmt->execute([$searchTerm, $searchTerm]);
    $posts = $postsStmt->fetchAll();
    
    // Add posts to results
    foreach ($posts as $post) {
        $results[] = [
            'type' => 'post',
            'title' => $post['title'],
            'url' => "post.php?slug=" . $post['slug'],
            'date' => formatDate($post['date']),
            'category' => 'Article'
        ];
    }
    
    // Search public pages (actual navbar pages from codebase)
    $publicPages = [
        // Main Navigation
        [
            'type' => 'page',
            'title' => 'Home',
            'url' => $base_path . 'index.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['home', 'main', 'homepage', 'welcome']
        ],
        [
            'type' => 'page',
            'title' => 'Programs',
            'url' => $base_path . 'programs.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['programs', 'courses', 'academic', 'degrees', 'education', 'curriculum']
        ],
        [
            'type' => 'page',
            'title' => 'Campuses',
            'url' => $base_path . 'campuses.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['campuses', 'locations', 'branches', 'facilities', 'laguna', 'cavite']
        ],
        [
            'type' => 'page',
            'title' => 'SDG Initiatives',
            'url' => $base_path . 'sdg-initiatives.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['sdg', 'sustainable', 'development', 'goals', 'initiatives', 'social']
        ],
        [
            'type' => 'page',
            'title' => 'News & Updates',
            'url' => $base_path . 'posts.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['news', 'updates', 'announcements', 'posts', 'articles', 'latest']
        ],
        
        // Programs Subpages
        [
            'type' => 'page',
            'title' => 'Senior High School',
            'url' => $base_path . 'programs/senior-high-school.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['senior', 'high', 'school', 'shs', 'grade', '11', '12']
        ],
        [
            'type' => 'page',
            'title' => 'Junior High School',
            'url' => $base_path . 'programs/junior-high-school.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['junior', 'high', 'school', 'jhs', 'grade', '7', '8', '9', '10']
        ],
        [
            'type' => 'page',
            'title' => 'Grade School',
            'url' => $base_path . 'programs/grade-school.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['grade', 'school', 'elementary', 'primary', 'grade', '1', '2', '3', '4', '5', '6']
        ],
        [
            'type' => 'page',
            'title' => 'Aviation',
            'url' => $base_path . 'programs/aviation.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['aviation', 'pilot', 'flight', 'aircraft', 'airline']
        ],
        [
            'type' => 'page',
            'title' => 'Arts & Sciences',
            'url' => $base_path . 'programs/arts-sciences.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['arts', 'sciences', 'liberal', 'humanities', 'social', 'sciences']
        ],
        [
            'type' => 'page',
            'title' => 'Business & Accountancy',
            'url' => $base_path . 'programs/business-accountancy.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['business', 'accountancy', 'commerce', 'management', 'finance']
        ],
        [
            'type' => 'page',
            'title' => 'Computer Studies',
            'url' => $base_path . 'programs/computer-studies.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['computer', 'studies', 'information', 'technology', 'it', 'programming']
        ],
        [
            'type' => 'page',
            'title' => 'Criminology',
            'url' => $base_path . 'programs/criminology.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['criminology', 'criminal', 'justice', 'law', 'enforcement']
        ],
        [
            'type' => 'page',
            'title' => 'Education',
            'url' => $base_path . 'programs/education.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['education', 'teaching', 'teacher', 'pedagogy', 'learning']
        ],
        [
            'type' => 'page',
            'title' => 'Engineering & Architecture',
            'url' => $base_path . 'programs/engineering-architecture.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['engineering', 'architecture', 'construction', 'design', 'building']
        ],
        [
            'type' => 'page',
            'title' => 'International Hospitality Management',
            'url' => $base_path . 'programs/hospitality-management.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['hospitality', 'management', 'hotel', 'tourism', 'service']
        ],
        [
            'type' => 'page',
            'title' => 'Maritime',
            'url' => $base_path . 'programs/maritime.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['maritime', 'shipping', 'navigation', 'seafaring', 'marine']
        ],
        [
            'type' => 'page',
            'title' => 'Law/Juris Doctor',
            'url' => $base_path . 'programs/law.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['law', 'juris', 'doctor', 'legal', 'attorney', 'lawyer']
        ],
        [
            'type' => 'page',
            'title' => 'Graduate School',
            'url' => $base_path . 'programs/graduate-school.php',
            'date' => '',
            'category' => 'Program',
            'keywords' => ['graduate', 'school', 'masters', 'phd', 'doctoral', 'postgraduate']
        ],
        
        // Online Services
        [
            'type' => 'page',
            'title' => 'Online Services Instructions',
            'url' => $base_path . 'ols_instructions.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['online', 'services', 'instructions', 'help', 'guide', 'tutorial']
        ],
        [
            'type' => 'external',
            'title' => 'GTI Online Grades',
            'url' => 'http://gti-binan.uphsl.edu.ph:8339/PARENTS_STUDENTS/parents_student_index.htm',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['gti', 'online', 'grades', 'student', 'portal', 'academic']
        ],
        [
            'type' => 'external',
            'title' => 'Moodle',
            'url' => 'https://uphslms.com/blended/login/index.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['moodle', 'lms', 'learning', 'management', 'system', 'online']
        ],
        [
            'type' => 'external',
            'title' => 'Google Account',
            'url' => 'https://accounts.google.com/signin',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['google', 'account', 'gmail', 'drive', 'docs', 'collaboration']
        ],
        [
            'type' => 'external',
            'title' => 'Microsoft 365',
            'url' => 'https://login.microsoftonline.com/',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['microsoft', '365', 'office', 'outlook', 'teams', 'productivity']
        ],
        [
            'type' => 'external',
            'title' => 'Saliksik',
            'url' => 'https://saliksikuphsl.org/',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['saliksik', 'research', 'database', 'academic', 'journals']
        ],
        
        // Support Services
        [
            'type' => 'external',
            'title' => 'Alumni',
            'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['alumni', 'graduates', 'former', 'students', 'network', 'association']
        ],
        [
            'type' => 'page',
            'title' => 'Careers',
            'url' => $base_path . 'support-services/careers.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['careers', 'jobs', 'employment', 'opportunities', 'hiring']
        ],
        [
            'type' => 'page',
            'title' => 'University Clinic',
            'url' => $base_path . 'support-services/clinic.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['clinic', 'health', 'medical', 'healthcare', 'wellness']
        ],
        [
            'type' => 'page',
            'title' => 'Community Outreach Department',
            'url' => $base_path . 'support-services/cod.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['community', 'outreach', 'service', 'volunteer', 'social']
        ],
        [
            'type' => 'page',
            'title' => 'International & External Affairs',
            'url' => $base_path . 'support-services/iea.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['international', 'external', 'affairs', 'global', 'partnerships']
        ],
        [
            'type' => 'page',
            'title' => 'Student Personnel Services',
            'url' => $base_path . 'support-services/sps.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['student', 'personnel', 'services', 'support', 'assistance']
        ],
        [
            'type' => 'page',
            'title' => 'Library',
            'url' => $base_path . 'support-services/library.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['library', 'books', 'resources', 'research', 'study']
        ],
        [
            'type' => 'page',
            'title' => 'Quality Assurance',
            'url' => $base_path . 'support-services/quality-assurance.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['quality', 'assurance', 'standards', 'accreditation', 'excellence']
        ],
        [
            'type' => 'page',
            'title' => 'Research',
            'url' => $base_path . 'support-services/research.php',
            'date' => '',
            'category' => 'Service',
            'keywords' => ['research', 'studies', 'investigation', 'academic', 'scientific']
        ],
        
        // About Section
        [
            'type' => 'page',
            'title' => 'About Us',
            'url' => $base_path . 'about',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['about', 'university', 'history', 'mission', 'vision', 'overview']
        ],
        [
            'type' => 'page',
            'title' => 'Contact Us',
            'url' => $base_path . 'about/contact.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['contact', 'phone', 'email', 'address', 'location', 'reach']
        ],
        [
            'type' => 'page',
            'title' => 'Environmental Policy',
            'url' => $base_path . 'about/environmental-policy.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['environmental', 'policy', 'sustainability', 'green', 'eco']
        ],
        [
            'type' => 'page',
            'title' => 'University Policy',
            'url' => $base_path . 'about/university-policy.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['university', 'policy', 'rules', 'regulations', 'guidelines']
        ],
        [
            'type' => 'page',
            'title' => 'Campus Map',
            'url' => $base_path . 'about/map.php',
            'date' => '',
            'category' => 'Page',
            'keywords' => ['map', 'campus', 'location', 'directions', 'navigation']
        ],
        
        // Academic Calendar
        [
            'type' => 'page',
            'title' => 'College Academic Calendar',
            'url' => $base_path . 'calendar/college-academic-calendar.php',
            'date' => '',
            'category' => 'Calendar',
            'keywords' => ['academic', 'calendar', 'college', 'schedule', 'dates']
        ],
        [
            'type' => 'page',
            'title' => 'BED & SHS Academic Calendar',
            'url' => $base_path . 'calendar/bed-shs-academic-calendar.php',
            'date' => '',
            'category' => 'Calendar',
            'keywords' => ['academic', 'calendar', 'bed', 'shs', 'schedule', 'dates']
        ],
        
        // Online Payment
        [
            'type' => 'external',
            'title' => 'Entrance Exam Payment',
            'url' => 'https://uphsl.edu.ph/online_payment/guest_exam',
            'date' => '',
            'category' => 'Payment',
            'keywords' => ['entrance', 'exam', 'payment', 'online', 'enrollment', 'application']
        ],
        [
            'type' => 'external',
            'title' => 'New Enrollees Payment',
            'url' => 'https://uphsl.edu.ph/online_payment/guest',
            'date' => '',
            'category' => 'Payment',
            'keywords' => ['new', 'enrollees', 'payment', 'online', 'enrollment', 'freshmen']
        ],
        [
            'type' => 'external',
            'title' => 'Enrolled Students Payment',
            'url' => 'https://uphsl.edu.ph/online_payment/guestold_student',
            'date' => '',
            'category' => 'Payment',
            'keywords' => ['enrolled', 'students', 'payment', 'online', 'tuition', 'fees']
        ],
        
        // Enrollment
        [
            'type' => 'external',
            'title' => 'Enrollment for College & Graduate School & Juris Doctor',
            'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/closedform',
            'date' => '',
            'category' => 'Enrollment',
            'keywords' => ['enrollment', 'college', 'graduate', 'school', 'juris', 'doctor', 'law']
        ],
        [
            'type' => 'external',
            'title' => 'Enrollment for Senior High School',
            'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/closedform',
            'date' => '',
            'category' => 'Enrollment',
            'keywords' => ['enrollment', 'senior', 'high', 'school', 'shs', 'grade', '11', '12']
        ]
    ];
    
    // Filter pages based on search query (title and keywords)
    foreach ($publicPages as $page) {
        $match = false;
        
        // Check title match
        if (stripos($page['title'], $query) !== false) {
            $match = true;
        }
        
        // Check keyword matches
        if (!$match && isset($page['keywords'])) {
            foreach ($page['keywords'] as $keyword) {
                if (stripos($keyword, $query) !== false) {
                    $match = true;
                    break;
                }
            }
        }
        
        if ($match) {
            // Remove keywords from result (not needed in frontend)
            unset($page['keywords']);
            $results[] = $page;
        }
    }
    
    // Limit total results
    $results = array_slice($results, 0, 8);
    
    echo json_encode([
        'success' => true,
        'results' => $results,
        'total' => count($results)
    ]);
    
} catch (Exception $e) {
    error_log("Navbar search error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Search failed'
    ]);
}

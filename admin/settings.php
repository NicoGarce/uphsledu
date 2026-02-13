<?php
/**
 * UPHSL Admin Settings
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing website settings (Super Admin only)
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
// Session is automatical__DIR__ . ly initialized by security.php

// Check if user is logged in and is super admin only
if (!isLoggedIn() || !isSuperAdmin()) {
    redirect('../auth/login.php');
}

$user = getUserById($_SESSION['user_id']);
$userRole = $_SESSION['user_role'];

// Set page title for header
$page_title = 'System Settings';

// Get database connection
$pdo = getDBConnection();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } elseif (isset($_POST['action']) && $_POST['action'] === 'save_general_settings') {
        // Require password verification for saving settings
        $password = $_POST['settings_password'] ?? '';
        
        if (empty($password)) {
            $error = 'Password verification is required to save settings';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Invalid password. Please enter your current password to save settings.';
        } else {
            // Password verified, proceed with saving settings
            $settings_map = [
            'site_name' => ['type' => 'text', 'value' => Validator::sanitize($_POST['site_name'] ?? 'University of Perpetual Help System Laguna', 'string')],
            'site_tagline' => ['type' => 'text', 'value' => Validator::sanitize($_POST['site_tagline'] ?? 'Character Building is Nation Building', 'string')],
            'contact_address' => ['type' => 'text', 'value' => Validator::sanitize($_POST['contact_address'] ?? 'UPH Compound, National Highway, Sto. Niño, City of Biñan, Laguna', 'string')],
            'contact_phone' => ['type' => 'text', 'value' => Validator::sanitize($_POST['contact_phone'] ?? '02-779-5310', 'string')],
            'contact_email_primary' => ['type' => 'text', 'value' => Validator::sanitize($_POST['contact_email_primary'] ?? 'marketing@uphsl.edu.ph', 'email')],
            'contact_email_secondary' => ['type' => 'text', 'value' => Validator::sanitize($_POST['contact_email_secondary'] ?? 'info@uphsl.edu.ph', 'email')],
            'contact_email_tertiary' => ['type' => 'text', 'value' => Validator::sanitize($_POST['contact_email_tertiary'] ?? '', 'email')],
            'facebook_url' => ['type' => 'text', 'value' => Validator::sanitize($_POST['facebook_url'] ?? 'https://www.facebook.com/uphsl.info.ph', 'url')],
            'youtube_url' => ['type' => 'text', 'value' => Validator::sanitize($_POST['youtube_url'] ?? 'https://www.youtube.com/@uphsltv1397', 'url')],
            'instagram_url' => ['type' => 'text', 'value' => Validator::sanitize($_POST['instagram_url'] ?? 'https://www.instagram.com/uphs.laguna', 'url')],
            'tiktok_url' => ['type' => 'text', 'value' => Validator::sanitize($_POST['tiktok_url'] ?? 'https://tiktok.com/@uphs.laguna', 'url')],
            'posts_per_page' => ['type' => 'integer', 'value' => (int)($_POST['posts_per_page'] ?? 12)],
            'homepage_recent_posts' => ['type' => 'integer', 'value' => (int)($_POST['homepage_recent_posts'] ?? 6)],
            'news_carousel_posts' => ['type' => 'integer', 'value' => (int)($_POST['news_carousel_posts'] ?? 5)],
            'default_post_status' => ['type' => 'text', 'value' => Validator::sanitize($_POST['default_post_status'] ?? 'draft', 'string')]
        ];
        
            $saved_count = 0;
            foreach ($settings_map as $key => $setting) {
                if (setSetting($key, $setting['value'], $setting['type'], ucfirst(str_replace('_', ' ', $key)), $_SESSION['user_id'])) {
                    $saved_count++;
                }
            }
            
            if ($saved_count > 0) {
                $success = 'Settings saved successfully!';
            } else {
                $error = 'Failed to save settings. Please try again.';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'toggle_maintenance') {
        $maintenance_mode = isset($_POST['maintenance_mode']) ? '1' : '0';
        $maintenance_message = Validator::sanitize($_POST['maintenance_message'] ?? 'We are currently performing scheduled maintenance. Please check back soon.', 'string');
        $current_mode = getSetting('maintenance_mode', '0');
        
        // If enabling maintenance mode (was off, now turning on), require password verification
        if ($maintenance_mode === '1' && $current_mode === '0') {
            $password = $_POST['password'] ?? '';
            
            if (empty($password)) {
                $error = 'Password verification is required to enable maintenance mode';
            } elseif (!password_verify($password, $user['password'])) {
                $error = 'Invalid password. Please enter your current password to enable maintenance mode.';
            } else {
                // Password verified, proceed with enabling maintenance mode
                if (setSetting('maintenance_mode', $maintenance_mode, 'boolean', 'Enable/disable maintenance mode for the entire website', $_SESSION['user_id'])) {
                    setSetting('maintenance_message', $maintenance_message, 'text', 'Message displayed to users during maintenance mode', $_SESSION['user_id']);
                    $success = 'Maintenance mode enabled successfully!';
                } else {
                    $error = 'Failed to update maintenance mode setting';
                }
            }
        } else {
            // Disabling maintenance mode or no change - no password required
            if (setSetting('maintenance_mode', $maintenance_mode, 'boolean', 'Enable/disable maintenance mode for the entire website', $_SESSION['user_id'])) {
                setSetting('maintenance_message', $maintenance_message, 'text', 'Message displayed to users during maintenance mode', $_SESSION['user_id']);
                $success = $maintenance_mode === '1' ? 'Maintenance mode enabled successfully!' : 'Maintenance mode disabled successfully!';
            } else {
                $error = 'Failed to update maintenance mode setting';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'save_section_maintenance') {
        // Require password verification for saving section maintenance settings
        $password = $_POST['section_maintenance_password'] ?? '';
        
        if (empty($password)) {
            $error = 'Password verification is required to save section maintenance settings';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Invalid password. Please enter your current password to save settings.';
        } else {
            // Password verified, proceed with saving section maintenance settings
            $sections_config = [
                'home' => ['name' => 'Home', 'subpages' => []],
                'programs' => [
                    'name' => 'Programs',
                    'subpages' => ['programs-index', 'senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school']
                ],
                'online-services' => ['name' => 'Online Services', 'subpages' => ['ols-instructions']],
                'support-services' => [
                    'name' => 'Support Services',
                    'subpages' => ['support-services-index', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research']
                ],
                'campuses' => ['name' => 'Campuses', 'subpages' => []],
                'about' => ['name' => 'About', 'subpages' => ['about-index', 'contact', 'environmental-policy', 'university-policy', 'map']],
                'online-payment' => ['name' => 'Online Payment', 'subpages' => ['payment-main', 'guest', 'guest-exam', 'guestold-student', 'guestold']],
                'calendar' => ['name' => 'Calendar', 'subpages' => ['college-academic-calendar', 'bed-shs-academic-calendar']],
                'enrollment' => ['name' => 'Enrollment', 'subpages' => ['enrollment-college', 'enrollment-shs']],
                'sdg-initiatives' => ['name' => 'SDG Initiatives', 'subpages' => []],
                'posts' => ['name' => 'Posts', 'subpages' => []],
                'post' => ['name' => 'Post', 'subpages' => []]
            ];
            
            $saved_count = 0;
            foreach ($sections_config as $key => $section) {
                // Save main section
                $maintenance_enabled = isset($_POST["section_maintenance_{$key}"]) ? '1' : '0';
                $maintenance_message = Validator::sanitize($_POST["section_maintenance_message_{$key}"] ?? "The {$section['name']} section is currently under maintenance. Please check back soon.", 'string');
                
                if (setSetting("section_maintenance_{$key}", $maintenance_enabled, 'boolean', "Enable/disable maintenance mode for {$section['name']} section", $_SESSION['user_id'])) {
                    setSetting("section_maintenance_message_{$key}", $maintenance_message, 'text', "Maintenance message for {$section['name']} section", $_SESSION['user_id']);
                    $saved_count++;
                }
                
                // Save sub-pages
                foreach ($section['subpages'] as $subKey) {
                    $subMaintenanceEnabled = isset($_POST["section_maintenance_{$key}_{$subKey}"]) ? '1' : '0';
                    $subMaintenanceMessage = Validator::sanitize($_POST["section_maintenance_message_{$key}_{$subKey}"] ?? "This page is currently under maintenance. Please check back soon.", 'string');
                    
                    if (setSetting("section_maintenance_{$key}_{$subKey}", $subMaintenanceEnabled, 'boolean', "Enable/disable maintenance mode for {$section['name']} - {$subKey}", $_SESSION['user_id'])) {
                        setSetting("section_maintenance_message_{$key}_{$subKey}", $subMaintenanceMessage, 'text', "Maintenance message for {$section['name']} - {$subKey}", $_SESSION['user_id']);
                        $saved_count++;
                    }
                }
            }
            
            if ($saved_count > 0) {
                $success = 'Section maintenance settings saved successfully!';
            } else {
                $error = 'Failed to save section maintenance settings. Please try again.';
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'save_navbar_visibility') {
        // Check if there are actual changes by comparing with current settings
        $hasChanges = false;
        $navbar_items_check = [
            'home' => ['name' => 'Home', 'subitems' => []],
            'programs' => ['name' => 'Programs', 'subitems' => ['basic-education', 'senior-high-school', 'junior-high-school', 'grade-school', 'aviation', 'arts-sciences', 'business-accountancy', 'computer-studies', 'criminology', 'education', 'engineering-architecture', 'hospitality-management', 'maritime', 'law', 'graduate-school']],
            'online-services' => ['name' => 'Online Services', 'subitems' => ['instructions', 'gti-online-grades', 'moodle', 'google-account', 'microsoft-365', 'saliksik']],
            'support-services' => ['name' => 'Support Services', 'subitems' => ['alumni', 'careers', 'clinic', 'cod', 'iea', 'sps', 'library', 'quality-assurance', 'research']],
            'campuses' => ['name' => 'Campuses', 'subitems' => []],
            'about' => ['name' => 'About', 'subitems' => ['about-us', 'contact', 'environmental-policy', 'university-policy', 'map']],
            'online-payment' => ['name' => 'Online Payment', 'subitems' => ['entrance-exam', 'new-enrollees', 'enrolled-students', 'other-payments']],
            'calendar' => ['name' => 'Calendar', 'subitems' => ['college-academic-calendar', 'bed-shs-academic-calendar']],
            'enrollment' => ['name' => 'Enrollment', 'subitems' => ['enrollment-college', 'enrollment-shs']],
            'sdg-initiatives' => ['name' => 'SDG Initiatives', 'subitems' => ['sdg-1', 'sdg-2', 'sdg-3', 'sdg-4', 'sdg-5', 'sdg-6', 'sdg-7', 'sdg-8', 'sdg-9', 'sdg-10', 'sdg-11', 'sdg-12', 'sdg-13', 'sdg-14', 'sdg-15', 'sdg-16', 'sdg-17', 'sdg-full-report']]
        ];
        
        foreach ($navbar_items_check as $key => $item) {
            $currentValue = getSetting("navbar_item_{$key}", '1');
            $newValue = isset($_POST["navbar_item_{$key}"]) ? '1' : '0';
            if ($currentValue !== $newValue) {
                $hasChanges = true;
                break;
            }
            
            foreach ($item['subitems'] as $subKey) {
                $currentSubValue = getSetting("navbar_item_{$key}_{$subKey}", '1');
                $newSubValue = isset($_POST["navbar_item_{$key}_{$subKey}"]) ? '1' : '0';
                if ($currentSubValue !== $newSubValue) {
                    $hasChanges = true;
                    break 2;
                }
            }
        }
        
        // Only require password if there are actual changes
        $password = $_POST['navbar_visibility_password'] ?? '';
        
        if ($hasChanges) {
            if (empty($password)) {
                $error = 'Password verification is required to save navbar visibility settings';
            } elseif (!password_verify($password, $user['password'])) {
                $error = 'Invalid password. Please enter your current password to save settings.';
            } else {
            // Password verified, proceed with saving navbar visibility settings
            $navbar_items = [
                'home' => ['name' => 'Home', 'subitems' => []],
                'programs' => [
                    'name' => 'Programs',
                    'subitems' => [
                        'basic-education' => 'Basic Education',
                        'senior-high-school' => 'Senior High School',
                        'junior-high-school' => 'Junior High School',
                        'grade-school' => 'Grade School',
                        'aviation' => 'Aviation',
                        'arts-sciences' => 'Arts & Sciences',
                        'business-accountancy' => 'Business & Accountancy',
                        'computer-studies' => 'Computer Studies',
                        'criminology' => 'Criminology',
                        'education' => 'Education',
                        'engineering-architecture' => 'Engineering & Architecture',
                        'hospitality-management' => 'International Hospitality Management',
                        'maritime' => 'Maritime',
                        'law' => 'Law/Juris Doctor',
                        'graduate-school' => 'Graduate School'
                    ]
                ],
                'online-services' => [
                    'name' => 'Online Services',
                    'subitems' => [
                        'instructions' => 'Instructions',
                        'gti-online-grades' => 'GTI Online Grades',
                        'moodle' => 'Moodle',
                        'google-account' => 'Google Account',
                        'microsoft-365' => 'Microsoft 365',
                        'saliksik' => 'Saliksik'
                    ]
                ],
                'support-services' => [
                    'name' => 'Support Services',
                    'subitems' => [
                        'alumni' => 'Alumni',
                        'careers' => 'Careers',
                        'clinic' => 'University Clinic',
                        'cod' => 'Community Outreach Department',
                        'iea' => 'International & External Affairs',
                        'sps' => 'Student Personnel Services',
                        'library' => 'Library',
                        'quality-assurance' => 'Quality Assurance',
                        'research' => 'Research'
                    ]
                ],
                'campuses' => ['name' => 'Campuses', 'subitems' => []],
                'about' => [
                    'name' => 'About',
                    'subitems' => [
                        'about-us' => 'About Us',
                        'contact' => 'Contact Us',
                        'environmental-policy' => 'Environmental Policy',
                        'university-policy' => 'University Policy',
                        'map' => 'Map'
                    ]
                ],
                'online-payment' => [
                    'name' => 'Online Payment',
                    'subitems' => [
                        'entrance-exam' => 'Entrance Exam',
                        'new-enrollees' => 'New Enrollees',
                        'enrolled-students' => 'Enrolled Students',
                        'other-payments' => 'Other Payments'
                    ]
                ],
                'calendar' => [
                    'name' => 'Calendar',
                    'subitems' => [
                        'college-academic-calendar' => 'College Academic Calendar',
                        'bed-shs-academic-calendar' => 'BED & SHS Academic Calendar'
                    ]
                ],
                'enrollment' => [
                    'name' => 'Enrollment',
                    'subitems' => [
                        'enrollment-college' => 'Enrollment for College & Graduate School & Juris Doctor',
                        'enrollment-shs' => 'Enrollment for Senior High School'
                    ]
                ],
                'sdg-initiatives' => [
                    'name' => 'SDG Initiatives',
                    'subitems' => [
                        'sdg-1' => 'SDG 1',
                        'sdg-2' => 'SDG 2',
                        'sdg-3' => 'SDG 3',
                        'sdg-4' => 'SDG 4',
                        'sdg-5' => 'SDG 5',
                        'sdg-6' => 'SDG 6',
                        'sdg-7' => 'SDG 7',
                        'sdg-8' => 'SDG 8',
                        'sdg-9' => 'SDG 9',
                        'sdg-10' => 'SDG 10',
                        'sdg-11' => 'SDG 11',
                        'sdg-12' => 'SDG 12',
                        'sdg-13' => 'SDG 13',
                        'sdg-14' => 'SDG 14',
                        'sdg-15' => 'SDG 15',
                        'sdg-16' => 'SDG 16',
                        'sdg-17' => 'SDG 17',
                        'sdg-full-report' => 'SDG Full Report'
                    ]
                ]
            ];
            
            $saved_count = 0;
            foreach ($navbar_items as $key => $item) {
                // Save main navbar item
                $item_enabled = isset($_POST["navbar_item_{$key}"]) ? '1' : '0';
                
                if (setSetting("navbar_item_{$key}", $item_enabled, 'boolean', "Enable/disable {$item['name']} navbar item", $_SESSION['user_id'])) {
                    $saved_count++;
                }
                
                // Save sub-items
                foreach ($item['subitems'] as $subKey => $subName) {
                    $subItemEnabled = isset($_POST["navbar_item_{$key}_{$subKey}"]) ? '1' : '0';
                    
                    if (setSetting("navbar_item_{$key}_{$subKey}", $subItemEnabled, 'boolean', "Enable/disable {$item['name']} - {$subName} submenu item", $_SESSION['user_id'])) {
                        $saved_count++;
                    }
                }
            }
            
            if ($saved_count > 0) {
                $success = 'Navbar visibility settings saved successfully!';
            } else {
                $error = 'Failed to save navbar visibility settings. Please try again.';
            }
            }
        } else {
            // No changes detected, no need to save
            $success = 'No changes detected. Settings remain unchanged.';
        }
    }
}

// Get current settings
$maintenance_mode = getSetting('maintenance_mode', '0');
$maintenance_message = getSetting('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');

// Get general settings
$site_name = getSetting('site_name', 'University of Perpetual Help System Laguna');
$site_tagline = getSetting('site_tagline', 'Character Building is Nation Building');
$contact_address = getSetting('contact_address', 'UPH Compound, National Highway, Sto. Niño, City of Biñan, Laguna');
$contact_phone = getSetting('contact_phone', '02-779-5310');
$contact_email_primary = getSetting('contact_email_primary', 'marketing@uphsl.edu.ph');
$contact_email_secondary = getSetting('contact_email_secondary', 'info@uphsl.edu.ph');
$contact_email_tertiary = getSetting('contact_email_tertiary', '');
$facebook_url = getSetting('facebook_url', 'https://www.facebook.com/uphsl.info.ph');
$youtube_url = getSetting('youtube_url', 'https://www.youtube.com/@uphsltv1397');
$instagram_url = getSetting('instagram_url', 'https://www.instagram.com/uphs.laguna');
$tiktok_url = getSetting('tiktok_url', 'https://tiktok.com/@uphs.laguna');
$posts_per_page = getSetting('posts_per_page', '12');
$homepage_recent_posts = getSetting('homepage_recent_posts', '6');
$news_carousel_posts = getSetting('news_carousel_posts', '5');
$default_post_status = getSetting('default_post_status', 'draft');

// Get navbar visibility settings
$navbar_items_config = [
    'home' => ['name' => 'Home', 'subitems' => []],
    'programs' => [
        'name' => 'Programs',
        'subitems' => [
            'basic-education' => 'Basic Education',
            'senior-high-school' => 'Senior High School',
            'junior-high-school' => 'Junior High School',
            'grade-school' => 'Grade School',
            'aviation' => 'Aviation',
            'arts-sciences' => 'Arts & Sciences',
            'business-accountancy' => 'Business & Accountancy',
            'computer-studies' => 'Computer Studies',
            'criminology' => 'Criminology',
            'education' => 'Education',
            'engineering-architecture' => 'Engineering & Architecture',
            'hospitality-management' => 'International Hospitality Management',
            'maritime' => 'Maritime',
            'law' => 'Law/Juris Doctor',
            'graduate-school' => 'Graduate School'
        ]
    ],
    'online-services' => [
        'name' => 'Online Services',
        'subitems' => [
            'instructions' => 'Instructions',
            'gti-online-grades' => 'GTI Online Grades',
            'moodle' => 'Moodle',
            'google-account' => 'Google Account',
            'microsoft-365' => 'Microsoft 365',
            'saliksik' => 'Saliksik'
        ]
    ],
    'support-services' => [
        'name' => 'Support Services',
        'subitems' => [
            'alumni' => 'Alumni',
            'careers' => 'Careers',
            'clinic' => 'University Clinic',
            'cod' => 'Community Outreach Department',
            'iea' => 'International & External Affairs',
            'sps' => 'Student Personnel Services',
            'library' => 'Library',
            'quality-assurance' => 'Quality Assurance',
            'research' => 'Research'
        ]
    ],
    'campuses' => ['name' => 'Campuses', 'subitems' => []],
    'about' => [
        'name' => 'About',
        'subitems' => [
            'about-us' => 'About Us',
            'contact' => 'Contact Us',
            'environmental-policy' => 'Environmental Policy',
            'university-policy' => 'University Policy',
            'map' => 'Map'
        ]
    ],
    'online-payment' => [
        'name' => 'Online Payment',
        'subitems' => [
            'entrance-exam' => 'Entrance Exam',
            'new-enrollees' => 'New Enrollees',
            'enrolled-students' => 'Enrolled Students',
            'other-payments' => 'Other Payments'
        ]
    ],
    'calendar' => [
        'name' => 'Calendar',
        'subitems' => [
            'college-academic-calendar' => 'College Academic Calendar',
            'bed-shs-academic-calendar' => 'BED & SHS Academic Calendar'
        ]
    ],
    'enrollment' => [
        'name' => 'Enrollment',
        'subitems' => [
            'enrollment-college' => 'Enrollment for College & Graduate School & Juris Doctor',
            'enrollment-shs' => 'Enrollment for Senior High School'
        ]
    ],
    'sdg-initiatives' => [
        'name' => 'SDG Initiatives',
        'subitems' => [
            'sdg-1' => 'SDG 1',
            'sdg-2' => 'SDG 2',
            'sdg-3' => 'SDG 3',
            'sdg-4' => 'SDG 4',
            'sdg-5' => 'SDG 5',
            'sdg-6' => 'SDG 6',
            'sdg-7' => 'SDG 7',
            'sdg-8' => 'SDG 8',
            'sdg-9' => 'SDG 9',
            'sdg-10' => 'SDG 10',
            'sdg-11' => 'SDG 11',
            'sdg-12' => 'SDG 12',
            'sdg-13' => 'SDG 13',
            'sdg-14' => 'SDG 14',
            'sdg-15' => 'SDG 15',
            'sdg-16' => 'SDG 16',
            'sdg-17' => 'SDG 17',
            'sdg-full-report' => 'SDG Full Report'
        ]
    ]
];

$navbar_visibility = [];
foreach ($navbar_items_config as $key => $item) {
    $navbar_visibility[$key] = [
        'enabled' => getSetting("navbar_item_{$key}", '1'), // Default to enabled
        'subitems' => []
    ];
    
    // Get sub-item visibility settings
    foreach ($item['subitems'] as $subKey => $subName) {
        $navbar_visibility[$key]['subitems'][$subKey] = [
            'enabled' => getSetting("navbar_item_{$key}_{$subKey}", '1') // Default to enabled
        ];
    }
}

// Get section maintenance settings with sub-pages
$sections = [
    'home' => ['name' => 'Home', 'subpages' => []],
    'programs' => [
        'name' => 'Programs',
        'subpages' => [
            'programs-index' => 'Programs Index',
            'senior-high-school' => 'Senior High School',
            'junior-high-school' => 'Junior High School',
            'grade-school' => 'Grade School',
            'aviation' => 'Aviation',
            'arts-sciences' => 'Arts & Sciences',
            'business-accountancy' => 'Business & Accountancy',
            'computer-studies' => 'Computer Studies',
            'criminology' => 'Criminology',
            'education' => 'Education',
            'engineering-architecture' => 'Engineering & Architecture',
            'hospitality-management' => 'Hospitality Management',
            'maritime' => 'Maritime',
            'law' => 'Law/Juris Doctor',
            'graduate-school' => 'Graduate School'
        ]
    ],
    'online-services' => [
        'name' => 'Online Services',
        'subpages' => [
            'ols-instructions' => 'OLS Instructions'
        ]
    ],
    'support-services' => [
        'name' => 'Support Services',
        'subpages' => [
            'support-services-index' => 'Support Services Index',
            'careers' => 'Careers',
            'clinic' => 'University Clinic',
            'cod' => 'Community Outreach',
            'iea' => 'International & External Affairs',
            'sps' => 'Student Personnel Services',
            'library' => 'Library',
            'quality-assurance' => 'Quality Assurance',
            'research' => 'Research'
        ]
    ],
    'campuses' => ['name' => 'Campuses', 'subpages' => []],
    'about' => [
        'name' => 'About',
        'subpages' => [
            'about-index' => 'About Us',
            'contact' => 'Contact Us',
            'environmental-policy' => 'Environmental Policy',
            'university-policy' => 'University Policy',
            'map' => 'Map'
        ]
    ],
    'online-payment' => [
        'name' => 'Online Payment',
        'subpages' => [
            'payment-main' => 'Payment Main',
            'guest' => 'Guest (New Enrollees)',
            'guest-exam' => 'Guest Exam (Entrance Exam)',
            'guestold-student' => 'Guest Old Student (Enrolled Students)',
            'guestold' => 'Other Payments'
        ]
    ],
    'calendar' => [
        'name' => 'Calendar',
        'subpages' => [
            'college-academic-calendar' => 'College Academic Calendar',
            'bed-shs-academic-calendar' => 'BED & SHS Academic Calendar'
        ]
    ],
    'enrollment' => [
        'name' => 'Enrollment',
        'subpages' => [
            'enrollment-college' => 'Enrollment College',
            'enrollment-shs' => 'Enrollment SHS'
        ]
    ],
    'sdg-initiatives' => ['name' => 'SDG Initiatives', 'subpages' => []],
    'posts' => ['name' => 'Posts', 'subpages' => []],
    'post' => ['name' => 'Post', 'subpages' => []]
];

$section_maintenance = [];
foreach ($sections as $key => $section) {
    $section_maintenance[$key] = [
        'enabled' => getSetting("section_maintenance_{$key}", '0'),
        'message' => getSetting("section_maintenance_message_{$key}", "The {$section['name']} section is currently under maintenance. Please check back soon."),
        'subpages' => []
    ];
    
    // Get sub-page maintenance settings
    foreach ($section['subpages'] as $subKey => $subName) {
        $section_maintenance[$key]['subpages'][$subKey] = [
            'enabled' => getSetting("section_maintenance_{$key}_{$subKey}", '0'),
            'message' => getSetting("section_maintenance_message_{$key}_{$subKey}", "The {$subName} page is currently under maintenance. Please check back soon.")
        ];
    }
}

// Library Programs POST handlers (create / update / delete / set source)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['library_create_program','library_update_program','library_delete_program','set_library_programs_source'])) {
    if (!CSRF::verify()) {
        $error = 'Security token mismatch. Please refresh the page and try again.';
    } else {
        try {
            if ($_POST['action'] === 'set_library_programs_source') {
                $source = isset($_POST['library_programs_source']) && $_POST['library_programs_source'] === 'db' ? 'db' : 'static';
                if (setSetting('library_programs_source', $source, 'text', 'Library programs source (db|static)', $_SESSION['user_id'])) {
                    $success = 'Library programs source updated.';
                } else {
                    $error = 'Failed to update library programs source.';
                }
            } elseif ($_POST['action'] === 'library_create_program') {
                $title = Validator::sanitize($_POST['title'] ?? '', 'string');
                $description = Validator::sanitize($_POST['description'] ?? '', 'string');
                $slug = Validator::sanitize($_POST['slug'] ?? '', 'slug');
                if (empty($slug)) {
                    $slug = preg_replace('/[^a-z0-9-]+/','-',strtolower(trim($title)));
                    $slug = trim($slug,'-');
                }

                $stmt = $pdo->prepare('SELECT id FROM library_programs WHERE slug = :slug');
                $stmt->execute([':slug' => $slug]);
                if ($stmt->fetch()) {
                    $error = 'A program with that slug already exists.';
                } else {
                    $ins = $pdo->prepare('INSERT INTO library_programs (slug,title,description,created_at,updated_at) VALUES (:slug,:title,:description,NOW(),NOW())');
                    $ins->execute([':slug'=>$slug,':title'=>$title,':description'=>$description]);
                    $success = 'Program created successfully.';
                }
            } elseif ($_POST['action'] === 'library_update_program') {
                $id = (int)($_POST['id'] ?? 0);
                $title = Validator::sanitize($_POST['title'] ?? '', 'string');
                $description = Validator::sanitize($_POST['description'] ?? '', 'string');
                if ($id <= 0) {
                    $error = 'Invalid program ID.';
                } else {
                    $up = $pdo->prepare('UPDATE library_programs SET title = :title, description = :description, updated_at = NOW() WHERE id = :id');
                    $up->execute([':title'=>$title,':description'=>$description,':id'=>$id]);
                    $success = 'Program updated successfully.';
                }
            } elseif ($_POST['action'] === 'library_delete_program') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $error = 'Invalid program ID.';
                } else {
                    // get slug and delete files
                    $s = $pdo->prepare('SELECT slug FROM library_programs WHERE id = :id');
                    $s->execute([':id'=>$id]);
                    $row = $s->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $slug = $row['slug'];
                        $baseDir = __DIR__ . '/../assets/documents/library/programs/' . $slug;
                        // delete files and directory if exists
                        if (is_dir($baseDir)) {
                            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
                            foreach ($files as $fileinfo) {
                                if ($fileinfo->isDir()) {
                                    rmdir($fileinfo->getRealPath());
                                } else {
                                    @unlink($fileinfo->getRealPath());
                                }
                            }
                            @rmdir($baseDir);
                        }
                        // delete DB rows
                        $pdo->prepare('DELETE FROM library_program_pdfs WHERE program_id = :id')->execute([':id'=>$id]);
                        $pdo->prepare('DELETE FROM library_programs WHERE id = :id')->execute([':id'=>$id]);
                        $success = 'Program and associated PDFs deleted.';
                    } else {
                        $error = 'Program not found.';
                    }
                }
            }
        } catch (Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
        }
    }
}
?>
<?php include '../app/includes/admin-header.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1 class="dashboard-title">
                <i class="fas fa-cog"></i>
                System Settings
            </h1>
            <p class="dashboard-subtitle">Manage website-wide settings and configurations</p>
        </div>

        <!-- Library Programs Manager -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-book"></i>
                        Library Programs Manager
                    </h2>
                    <p class="settings-description">Create, edit, delete library programs and upload multiple PDFs per program. Uploaded PDFs are stored under <strong>assets/documents/library/programs/{slug}/</strong>.</p>
                </div>

                <?php
                // Fetch programs and their PDFs from DB
                $programs = [];
                $program_pdfs = [];
                try {
                    $st = $pdo->query('SELECT id, slug, title, description FROM library_programs ORDER BY created_at ASC');
                    $programs = $st->fetchAll(PDO::FETCH_ASSOC);
                    $ids = array_column($programs, 'id');
                    if (!empty($ids)) {
                        $placeholders = implode(',', array_fill(0, count($ids), '?'));
                        $stmt = $pdo->prepare("SELECT id, program_id, filename, path, uploaded_at FROM library_program_pdfs WHERE program_id IN ({$placeholders}) ORDER BY uploaded_at DESC");
                        $stmt->execute($ids);
                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $r) {
                            $program_pdfs[$r['program_id']][] = $r;
                        }
                    }
                } catch (Exception $e) {
                    // ignore
                }
                ?>

                <div class="form-actions" style="margin-bottom:16px;">
                    <form method="POST" class="inline" style="display:flex;gap:8px;align-items:center;">
                        <?php echo CSRF::field(); ?>
                        <input type="hidden" name="action" value="set_library_programs_source">
                        <label style="margin-right:8px;">Source:</label>
                        <select name="library_programs_source" class="form-input" style="width:auto;">
                            <option value="static" <?php echo getSetting('library_programs_source','static') !== 'db' ? 'selected' : ''; ?>>Static</option>
                            <option value="db" <?php echo getSetting('library_programs_source','static') === 'db' ? 'selected' : ''; ?>>Database</option>
                        </select>
                        <button class="btn" type="submit">Save</button>
                    </form>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="flex:1; width:100%;">
                        <h3 style="margin-top:0;margin-bottom:8px;">Create Program</h3>
                        <form method="POST" class="settings-form" novalidate style="display:flex;flex-direction:column;gap:8px;">
                            <?php echo CSRF::field(); ?>
                            <input type="hidden" name="action" value="library_create_program">
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="text" name="title" class="form-input" placeholder="Program title" style="flex:1;min-width:140px;padding:8px;font-size:0.95rem;" required>
                                <input type="text" name="slug" class="form-input" placeholder="slug (optional)" style="width:140px;padding:8px;font-size:0.95rem;">
                                <button type="submit" class="btn" style="padding:6px 10px;font-size:0.95rem;">Create</button>
                            </div>
                            <div>
                                <label class="form-label" style="font-size:0.9rem;margin-bottom:6px;">Description</label>
                                <textarea name="description" class="form-textarea" rows="2" style="padding:8px;font-size:0.95rem;min-height:56px;" placeholder="Short description (optional)"></textarea>
                            </div>
                        </form>
                    </div>

                    <div style="flex:1;">
                        <h3 style="margin-top:0;margin-bottom:8px;">Existing Programs</h3>
                        <?php if (empty($programs)): ?>
                            <p>No programs yet.</p>
                        <?php else: ?>
                            <ul id="program-list" style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                                <?php foreach ($programs as $p): ?>
                                    <li class="section-maintenance-group" style="display:flex;align-items:center;gap:8px;padding:10px;">
                                        <button class="" style="background:transparent;color:var(--text-dark);padding:8px;border-radius:6px;box-shadow:none;border:0;text-align:left;flex:1;font-weight:600;" onclick="openEditModal(<?php echo (int)$p['id']; ?>)">
                                            <?php echo htmlspecialchars($p['title']); ?>
                                            <div style="font-size:0.85rem;color:#6b7280;margin-top:4px;font-weight:400;"><?php echo htmlspecialchars($p['slug']); ?></div>
                                        </button>
                                        <button class="btn btn-icon" title="Delete program" onclick="deleteProgram(<?php echo (int)$p['id']; ?>)" style="background:#ef4444;color:#fff;border-radius:6px;padding:8px 10px;"> 
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <input type="hidden" id="program-data-<?php echo (int)$p['id']; ?>" data-title="<?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?>" data-desc="<?php echo htmlspecialchars($p['description'], ENT_QUOTES); ?>">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo XSS::clean($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo XSS::clean($success); ?>
            </div>
        <?php endif; ?>

        <!-- General Information Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-info-circle"></i>
                        General Information
                    </h2>
                    <p class="settings-description">Configure basic website information and branding displayed throughout the site.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="site_name" class="form-label">
                            <i class="fas fa-university"></i>
                            Site Name
                        </label>
                        <input type="text" name="site_name" id="site_name" class="form-input" value="<?php echo XSS::escapeAttr($site_name); ?>" required>
                        <small class="form-help">The official name of the university displayed in headers, footers, and page titles.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_tagline" class="form-label">
                            <i class="fas fa-quote-left"></i>
                            Site Tagline
                        </label>
                        <input type="text" name="site_tagline" id="site_tagline" class="form-input" value="<?php echo XSS::escapeAttr($site_tagline); ?>">
                        <small class="form-help">A short tagline or motto displayed in the footer and other areas.</small>
                    </div>
                    
                    <div class="form-group" id="general-password-verification-group" style="display: none;">
                        <label for="general_settings_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="settings_password" 
                            id="general_settings_password" 
                            class="form-input" 
                            placeholder="Enter your password to save settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save general settings for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save General Information
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-address-book"></i>
                        Contact Information
                    </h2>
                    <p class="settings-description">Manage contact details displayed on the website footer and contact pages.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="contact_address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Address
                        </label>
                        <textarea name="contact_address" id="contact_address" class="form-textarea" rows="3"><?php echo XSS::clean($contact_address); ?></textarea>
                        <small class="form-help">Physical address of the university displayed in the footer.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">
                            <i class="fas fa-phone"></i>
                            Phone Number
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" class="form-input" value="<?php echo XSS::escapeAttr($contact_phone); ?>">
                        <small class="form-help">Main contact phone number displayed in the footer.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email_primary" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Primary Email
                        </label>
                        <input type="email" name="contact_email_primary" id="contact_email_primary" class="form-input" value="<?php echo XSS::escapeAttr($contact_email_primary); ?>">
                        <small class="form-help">Primary contact email address (e.g., marketing@uphsl.edu.ph).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email_secondary" class="form-label">
                            <i class="fas fa-envelope-open"></i>
                            Secondary Email
                        </label>
                        <input type="email" name="contact_email_secondary" id="contact_email_secondary" class="form-input" value="<?php echo XSS::escapeAttr($contact_email_secondary); ?>">
                        <small class="form-help">Secondary contact email address (e.g., info@uphsl.edu.ph).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email_tertiary" class="form-label">
                            <i class="fas fa-envelope-square"></i>
                            Tertiary Email
                        </label>
                        <input type="email" name="contact_email_tertiary" id="contact_email_tertiary" class="form-input" value="<?php echo XSS::escapeAttr($contact_email_tertiary); ?>">
                        <small class="form-help">Tertiary contact email address (optional).</small>
                    </div>
                    
                    <div class="form-group" id="contact-password-verification-group" style="display: none;">
                        <label for="contact_settings_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="settings_password" 
                            id="contact_settings_password" 
                            class="form-input" 
                            placeholder="Enter your password to save settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save contact information for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Contact Information
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Social Media Links Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-share-alt"></i>
                        Social Media Links
                    </h2>
                    <p class="settings-description">Configure social media profile URLs displayed throughout the website.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="facebook_url" class="form-label">
                            <i class="fab fa-facebook"></i>
                            Facebook URL
                        </label>
                        <input type="url" name="facebook_url" id="facebook_url" class="form-input" value="<?php echo XSS::escapeAttr($facebook_url); ?>" placeholder="https://www.facebook.com/yourpage">
                        <small class="form-help">Facebook page URL used in footer, news carousel, and homepage.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="youtube_url" class="form-label">
                            <i class="fab fa-youtube"></i>
                            YouTube URL
                        </label>
                        <input type="url" name="youtube_url" id="youtube_url" class="form-input" value="<?php echo XSS::escapeAttr($youtube_url); ?>" placeholder="https://www.youtube.com/@channel">
                        <small class="form-help">YouTube channel URL.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="instagram_url" class="form-label">
                            <i class="fab fa-instagram"></i>
                            Instagram URL
                        </label>
                        <input type="url" name="instagram_url" id="instagram_url" class="form-input" value="<?php echo XSS::escapeAttr($instagram_url); ?>" placeholder="https://www.instagram.com/username">
                        <small class="form-help">Instagram profile URL.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="tiktok_url" class="form-label">
                            <i class="fab fa-tiktok"></i>
                            TikTok URL
                        </label>
                        <input type="url" name="tiktok_url" id="tiktok_url" class="form-input" value="<?php echo XSS::escapeAttr($tiktok_url); ?>" placeholder="https://tiktok.com/@username">
                        <small class="form-help">TikTok profile URL.</small>
                    </div>
                    
                    <div class="form-group" id="social-password-verification-group" style="display: none;">
                        <label for="social_settings_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="settings_password" 
                            id="social_settings_password" 
                            class="form-input" 
                            placeholder="Enter your password to save settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save social media links for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Social Media Links
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Display Settings Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-sliders-h"></i>
                        Display Settings
                    </h2>
                    <p class="settings-description">Configure how content is displayed throughout the website.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="posts_per_page" class="form-label">
                            <i class="fas fa-list"></i>
                            Posts Per Page
                        </label>
                        <input type="number" name="posts_per_page" id="posts_per_page" class="form-input" value="<?php echo XSS::escapeAttr($posts_per_page); ?>" min="1" max="50" required>
                        <small class="form-help">Number of posts displayed per page on the posts listing page (default: 12).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="homepage_recent_posts" class="form-label">
                            <i class="fas fa-home"></i>
                            Homepage Recent Posts
                        </label>
                        <input type="number" name="homepage_recent_posts" id="homepage_recent_posts" class="form-input" value="<?php echo htmlspecialchars($homepage_recent_posts); ?>" min="1" max="20" required>
                        <small class="form-help">Number of recent posts displayed on the homepage (default: 6).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="news_carousel_posts" class="form-label">
                            <i class="fas fa-images"></i>
                            News Carousel Posts
                        </label>
                        <input type="number" name="news_carousel_posts" id="news_carousel_posts" class="form-input" value="<?php echo htmlspecialchars($news_carousel_posts); ?>" min="1" max="10" required>
                        <small class="form-help">Number of posts displayed in the news carousel on program and support service pages (default: 5).</small>
                    </div>
                    
                    <div class="form-group" id="display-password-verification-group" style="display: none;">
                        <label for="display_settings_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="settings_password" 
                            id="display_settings_password" 
                            class="form-input" 
                            placeholder="Enter your password to save settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save display settings for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Display Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Post Settings Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-newspaper"></i>
                        Post Settings
                    </h2>
                    <p class="settings-description">Configure default behavior for post creation and publishing.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="default_post_status" class="form-label">
                            <i class="fas fa-toggle-on"></i>
                            Default Post Status
                        </label>
                        <select name="default_post_status" id="default_post_status" class="form-input">
                            <option value="draft" <?php echo $default_post_status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $default_post_status === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                        <small class="form-help">Default status for newly created posts. Draft posts require manual publishing, while published posts are immediately visible.</small>
                    </div>
                    
                    <div class="form-group" id="post-password-verification-group" style="display: none;">
                        <label for="post_settings_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="settings_password" 
                            id="post_settings_password" 
                            class="form-input" 
                            placeholder="Enter your password to save settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save post settings for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Post Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Section Maintenance Switches -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-wrench"></i>
                        Section Maintenance Switches
                    </h2>
                    <p class="settings-description">Enable maintenance mode for specific sections of the website. When enabled, users will see a maintenance message instead of the section content.</p>
                </div>
                
                <form method="POST" action="" class="settings-form" id="section-maintenance-form" novalidate>
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_section_maintenance">
                    
                    <!-- Master Toggle All Switch -->
                    <div class="master-toggle-container">
                        <label class="switch-label-compact master-toggle">
                            <div class="switch-container-small">
                                <input type="checkbox" 
                                       id="toggle-all-maintenance" 
                                       onchange="toggleAllMaintenance(this.checked)">
                                <span class="switch-slider-small"></span>
                            </div>
                            <span class="switch-text-compact">
                                <strong>Toggle All Maintenance</strong>
                                <small id="master-status" class="status-badge">All Disabled</small>
                            </span>
                        </label>
                        <p class="master-toggle-description">Enable or disable maintenance mode for all sections and sub-pages at once.</p>
                    </div>
                    
                    <div class="section-maintenance-list">
                        <?php foreach ($sections as $key => $section): ?>
                            <div class="section-maintenance-group">
                                <div class="section-maintenance-main">
                                    <label class="switch-label-compact">
                                        <div class="switch-container-small">
                                            <input type="checkbox" 
                                                   name="section_maintenance_<?php echo $key; ?>" 
                                                   id="section_maintenance_<?php echo $key; ?>" 
                                                   value="1" 
                                                   <?php echo $section_maintenance[$key]['enabled'] === '1' ? 'checked' : ''; ?>
                                                   onchange="toggleSectionMessage('<?php echo $key; ?>')">
                                            <span class="switch-slider-small"></span>
                                        </div>
                                        <span class="switch-text-compact">
                                            <strong><?php echo htmlspecialchars($section['name']); ?></strong>
                                            <small id="section-status-<?php echo $key; ?>" class="status-badge">
                                                <?php echo $section_maintenance[$key]['enabled'] === '1' ? 'Enabled' : 'Disabled'; ?>
                                            </small>
                                        </span>
                                    </label>
                                    
                                    <div class="section-maintenance-message-compact" id="section-message-<?php echo $key; ?>" style="<?php echo $section_maintenance[$key]['enabled'] === '1' ? '' : 'display: none;'; ?>">
                                        <textarea 
                                            name="section_maintenance_message_<?php echo $key; ?>" 
                                            id="section_maintenance_message_<?php echo $key; ?>" 
                                            class="form-textarea-compact" 
                                            rows="1" 
                                            placeholder="Maintenance message..."><?php echo htmlspecialchars($section_maintenance[$key]['message']); ?></textarea>
                                    </div>
                                </div>
                                
                                <?php if (!empty($section['subpages'])): ?>
                                    <div class="section-subpages">
                                        <?php foreach ($section['subpages'] as $subKey => $subName): ?>
                                            <div class="subpage-item">
                                                <label class="switch-label-compact">
                                                    <div class="switch-container-small">
                                                        <input type="checkbox" 
                                                               name="section_maintenance_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                               id="section_maintenance_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                               value="1" 
                                                               <?php echo isset($section_maintenance[$key]['subpages'][$subKey]) && $section_maintenance[$key]['subpages'][$subKey]['enabled'] === '1' ? 'checked' : ''; ?>
                                                               onchange="toggleSubpageMessage('<?php echo $key; ?>', '<?php echo $subKey; ?>')">
                                                        <span class="switch-slider-small"></span>
                                                    </div>
                                                    <span class="switch-text-compact">
                                                        <span><?php echo htmlspecialchars($subName); ?></span>
                                                        <small id="subpage-status-<?php echo $key; ?>-<?php echo $subKey; ?>" class="status-badge-small">
                                                            <?php echo isset($section_maintenance[$key]['subpages'][$subKey]) && $section_maintenance[$key]['subpages'][$subKey]['enabled'] === '1' ? 'On' : 'Off'; ?>
                                                        </small>
                                                    </span>
                                                </label>
                                                
                                                <div class="subpage-message" id="subpage-message-<?php echo $key; ?>-<?php echo $subKey; ?>" style="<?php echo isset($section_maintenance[$key]['subpages'][$subKey]) && $section_maintenance[$key]['subpages'][$subKey]['enabled'] === '1' ? '' : 'display: none;'; ?>">
                                                    <textarea 
                                                        name="section_maintenance_message_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                        id="section_maintenance_message_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                        class="form-textarea-compact" 
                                                        rows="1" 
                                                        placeholder="Message..."><?php echo isset($section_maintenance[$key]['subpages'][$subKey]) ? htmlspecialchars($section_maintenance[$key]['subpages'][$subKey]['message']) : ''; ?></textarea>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-group" id="section-maintenance-password-verification-group" style="display: none;">
                        <label for="section_maintenance_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="section_maintenance_password" 
                            id="section_maintenance_password" 
                            class="form-input" 
                            placeholder="Enter your password to save section maintenance settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save section maintenance settings for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Section Maintenance Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Navbar Visibility Switches -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-bars"></i>
                        Navbar Visibility Switches
                    </h2>
                    <p class="settings-description">Enable or disable navbar items and sub-menu items in the website header. Disabled items will be hidden from the navigation menu.</p>
                </div>
                
                <form method="POST" action="" class="settings-form" id="navbar-visibility-form" novalidate>
                    <?php echo CSRF::field(); ?>
                    <input type="hidden" name="action" value="save_navbar_visibility">
                    
                    <!-- Master Toggle All Switch -->
                    <div class="master-toggle-container">
                        <label class="switch-label-compact master-toggle">
                            <div class="switch-container-small">
                                <input type="checkbox" 
                                       id="toggle-all-navbar" 
                                       onchange="toggleAllNavbar(this.checked)">
                                <span class="switch-slider-small"></span>
                            </div>
                            <span class="switch-text-compact">
                                <strong>Toggle All Navbar Items</strong>
                                <small id="navbar-master-status" class="status-badge">All Enabled</small>
                            </span>
                        </label>
                        <p class="master-toggle-description">Enable or disable all navbar items and sub-menu items at once.</p>
                    </div>
                    
                    <div class="section-maintenance-list">
                        <?php foreach ($navbar_items_config as $key => $item): ?>
                            <div class="section-maintenance-group">
                                <div class="section-maintenance-main">
                                    <label class="switch-label-compact">
                                        <div class="switch-container-small">
                                            <input type="checkbox" 
                                                   name="navbar_item_<?php echo $key; ?>" 
                                                   id="navbar_item_<?php echo $key; ?>" 
                                                   value="1" 
                                                   <?php echo $navbar_visibility[$key]['enabled'] === '1' ? 'checked' : ''; ?>
                                                   onchange="updateNavbarStatus('<?php echo $key; ?>')">
                                            <span class="switch-slider-small"></span>
                                        </div>
                                        <span class="switch-text-compact">
                                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                            <small id="navbar-status-<?php echo $key; ?>" class="status-badge">
                                                <?php echo $navbar_visibility[$key]['enabled'] === '1' ? 'Visible' : 'Hidden'; ?>
                                            </small>
                                        </span>
                                    </label>
                                </div>
                                
                                <?php if (!empty($item['subitems'])): ?>
                                    <div class="section-subpages">
                                        <?php foreach ($item['subitems'] as $subKey => $subName): ?>
                                            <div class="subpage-item">
                                                <label class="switch-label-compact">
                                                    <div class="switch-container-small">
                                                        <input type="checkbox" 
                                                               name="navbar_item_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                               id="navbar_item_<?php echo $key; ?>_<?php echo $subKey; ?>" 
                                                               value="1" 
                                                               <?php echo isset($navbar_visibility[$key]['subitems'][$subKey]) && $navbar_visibility[$key]['subitems'][$subKey]['enabled'] === '1' ? 'checked' : ''; ?>
                                                               onchange="updateNavbarSubitemStatus('<?php echo $key; ?>', '<?php echo $subKey; ?>')">
                                                        <span class="switch-slider-small"></span>
                                                    </div>
                                                    <span class="switch-text-compact">
                                                        <span><?php echo htmlspecialchars($subName); ?></span>
                                                        <small id="navbar-subitem-status-<?php echo $key; ?>-<?php echo $subKey; ?>" class="status-badge-small">
                                                            <?php echo isset($navbar_visibility[$key]['subitems'][$subKey]) && $navbar_visibility[$key]['subitems'][$subKey]['enabled'] === '1' ? 'Visible' : 'Hidden'; ?>
                                                        </small>
                                                    </span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="form-group" id="navbar-visibility-password-verification-group" style="display: none;">
                        <label for="navbar_visibility_password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="navbar_visibility_password" 
                            id="navbar_visibility_password" 
                            class="form-input" 
                            placeholder="Enter your password to save navbar visibility settings"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to save navbar visibility settings for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Navbar Visibility Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <style>
        .settings-section {
            margin-top: 30px;
        }
        
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        
        .settings-card-header {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }
        
        .settings-card-header h2 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .settings-card-header h2 i {
            color: var(--alt-color-1);
        }
        
        .settings-description {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        .switch-label {
            display: flex;
            align-items: center;
            gap: 20px;
            cursor: pointer;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        
        .switch-label:hover {
            background: #f1f5f9;
        }
        
        .switch-container {
            position: relative;
            width: 60px;
            height: 32px;
        }
        
        .switch-container input[type="checkbox"] {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 32px;
        }
        
        .switch-slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        .switch-container input:checked + .switch-slider {
            background-color: var(--primary-color);
        }
        
        .switch-container input:checked + .switch-slider:before {
            transform: translateX(28px);
        }
        
        .switch-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .switch-text strong {
            font-size: 1.1rem;
            color: var(--text-dark);
        }
        
        .switch-text small {
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        .form-actions {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f1f5f9;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(28, 77, 161, 0.3);
        }
        
        .btn i {
            font-size: 1rem;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
        }
        
        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            min-height: 100px;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(28, 77, 161, 0.1);
        }
        
        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-size: 1rem;
        }
        
        .form-label i {
            color: var(--primary-color);
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-help {
            display: block;
            margin-top: 8px;
            color: var(--text-light);
            font-size: 0.85rem;
            line-height: 1.5;
        }
        
        .form-help i {
            margin-right: 4px;
            color: var(--primary-color);
        }
        
        #password-verification-group {
            background: #fff3cd;
            border: 2px solid #ffc63e;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }
        
        #password-verification-group .form-label {
            color: #856404;
            font-weight: 600;
        }
        
        #password-verification-group .form-help {
            color: #856404;
        }
        
        /* Password verification groups for all settings sections */
        #general-password-verification-group,
        #contact-password-verification-group,
        #social-password-verification-group,
        #display-password-verification-group,
        #post-password-verification-group {
            background: #fff3cd;
            border: 2px solid #ffc63e;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }
        
        #general-password-verification-group .form-label,
        #contact-password-verification-group .form-label,
        #social-password-verification-group .form-label,
        #display-password-verification-group .form-label,
        #post-password-verification-group .form-label {
            color: #856404;
            font-weight: 600;
        }
        
        #general-password-verification-group .form-help,
        #contact-password-verification-group .form-help,
        #social-password-verification-group .form-help,
        #display-password-verification-group .form-help,
        #post-password-verification-group .form-help {
            color: #856404;
        }
        
        /* Section Maintenance Styles - Compact */
        .section-maintenance-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .section-maintenance-group {
            background: #f8f9fa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            transition: all 0.3s ease;
        }
        
        .section-maintenance-group:hover {
            border-color: var(--primary-color);
            box-shadow: 0 1px 4px rgba(28, 77, 161, 0.1);
        }
        
        .section-maintenance-main {
            margin-bottom: 8px;
        }
        
        .switch-label-compact {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 0;
        }
        
        .switch-container-small {
            position: relative;
            width: 40px;
            height: 22px;
            flex-shrink: 0;
        }
        
        .switch-container-small input[type="checkbox"] {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .switch-slider-small {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 22px;
        }
        
        .switch-slider-small:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }
        
        .switch-container-small input:checked + .switch-slider-small {
            background-color: var(--primary-color);
        }
        
        .switch-container-small input:checked + .switch-slider-small:before {
            transform: translateX(18px);
        }
        
        .switch-container-small input:indeterminate + .switch-slider-small {
            background-color: #fbbf24;
        }
        
        .switch-container-small input:indeterminate + .switch-slider-small:before {
            transform: translateX(9px);
        }
        
        .switch-text-compact {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 0.9rem;
        }
        
        .switch-text-compact strong {
            font-size: 0.95rem;
            color: var(--text-dark);
            font-weight: 600;
        }
        
        .switch-text-compact span {
            font-size: 0.85rem;
            color: var(--text-dark);
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            background: #e5e7eb;
            color: #6b7280;
        }
        
        .status-badge-small {
            font-size: 0.7rem;
            padding: 1px 6px;
            border-radius: 10px;
            font-weight: 600;
            background: #e5e7eb;
            color: #6b7280;
        }
        
        .section-maintenance-message-compact {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }
        
        .form-textarea-compact {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.85rem;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            min-height: 32px;
        }

        /* Icon-only buttons */
        .btn-icon {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            width: 40px !important;
            height: 40px !important;
            border-radius: 8px !important;
            font-size: 0.95rem !important;
            line-height: 1 !important;
            box-shadow: none !important;
            vertical-align: middle;
        }

        .btn-icon i { font-size: 1rem; margin: 0; line-height: 1; display: inline-block; }

        /* Slightly tighter program list spacing */
        #program-list li { padding: 8px 10px; }

        /* Modal tweaks */
        #editModal .settings-card { padding: 20px; border-radius:10px; }
        #editModal h2 { font-size:1.1rem; }
        #editModal .form-label { margin-bottom:6px; }
        #editModal .form-input, #editModal .form-textarea { padding:10px; }
        
        .form-textarea-compact:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(28, 77, 161, 0.1);
        }
        
        .section-subpages {
            margin-top: 8px;
            padding-left: 20px;
            border-left: 2px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .subpage-item {
            padding: 4px 0;
        }
        
        .subpage-message {
            margin-top: 4px;
            padding-left: 50px;
        }
        
        /* Master Toggle Styles */
        .master-toggle-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(28, 77, 161, 0.1);
        }
        
        .master-toggle {
            margin-bottom: 10px;
        }
        
        .master-toggle-description {
            margin: 0;
            padding-left: 50px;
            font-size: 0.85rem;
            color: var(--text-light);
            font-style: italic;
        }
        
        #section-maintenance-password-verification-group {
            background: #fff3cd;
            border: 2px solid #ffc63e;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }
        
        #section-maintenance-password-verification-group .form-label {
            color: #856404;
            font-weight: 600;
        }
        
        #section-maintenance-password-verification-group .form-help {
            color: #856404;
        }
        
        #navbar-visibility-password-verification-group {
            background: #fff3cd;
            border: 2px solid #ffc63e;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }
        
        #navbar-visibility-password-verification-group .form-label {
            color: #856404;
            font-weight: 600;
        }
        
        #navbar-visibility-password-verification-group .form-help {
            color: #856404;
        }
        
        @media (max-width: 768px) {
            .section-subpages {
                padding-left: 10px;
            }
            
            .subpage-message {
                padding-left: 30px;
            }
        }
        /* Font sizing polish */
        .settings-card {
            font-size: 14px; /* base for card content */
        }

        .settings-card h2 {
            font-size: 1.25rem;
        }

        .settings-card h3 {
            font-size: 1.05rem;
        }

        .settings-card h4 {
            font-size: 1rem;
        }

        .settings-description {
            font-size: 0.95rem;
        }

        .form-input,
        .form-textarea,
        .form-textarea-compact {
            font-size: 0.95rem;
        }

        .btn {
            font-size: 0.9rem;
            padding: 8px 14px;
            min-height: 36px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            font-size: 0.95rem;
        }

        #program-list li button {
            font-size: 1rem;
        }

        #program-list li div {
            font-size: 0.85rem;
        }

        /* PDF list row */
        .pdf-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px;
            border-radius: 6px;
            background: #fff;
        }

        .pdf-row a { flex: 1; }

        .pdf-row .btn { margin-left: 8px; display:inline-flex; align-items:center; justify-content:center; }

        /* Ensure modal buttons using .btn-icon are centered */
        #editModal .btn-icon { padding: 0 !important; }

        /* Modal-specific sizing */
        #editModal .settings-card h2 { font-size: 1.15rem; }
        #modal_edit_status { font-size: 0.95rem; }
    </style>

    <script>
        // Validate form before submission (for general settings forms only)
        document.querySelectorAll('.settings-form').forEach(function(form) {
            // Skip forms that have their own specific validation handlers
            if (form.id === 'section-maintenance-form' || form.id === 'navbar-visibility-form') {
                return; // Skip - these have their own handlers
            }
            
            form.addEventListener('submit', function(e) {
                // Check if password is required
                const passwordInput = form.querySelector('input[name="settings_password"]');
                const passwordGroup = form.querySelector('[id$="-password-verification-group"]');
                
                if (passwordGroup && passwordInput) {
                    // Check visibility using computed style
                    const computedStyle = window.getComputedStyle(passwordGroup);
                    const isVisible = computedStyle.display !== 'none' && computedStyle.display !== '';
                    
                    if (isVisible) {
                        const passwordValue = (passwordInput.value || '').trim();
                        if (passwordValue.length === 0) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Password verification is required to save settings.');
                            passwordInput.focus();
                            return false;
                        }
                    }
                }
            });
        });
        
        // Track original values for each form using WeakMap
        const formOriginalValues = new WeakMap();
        
        // Store original values when page loads
        document.querySelectorAll('.settings-form').forEach(function(form) {
            const originalValues = {};
            
            // Store original values for all inputs, textareas, and selects
            form.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], input[type="number"], textarea, select').forEach(function(input) {
                if (input.name && !input.name.includes('password')) {
                    originalValues[input.name] = input.value;
                }
            });
            
            formOriginalValues.set(form, originalValues);
        });
        
        // Function to check if form has changes
        function checkFormChanges(form) {
            const originalValues = formOriginalValues.get(form) || {};
            let hasChanges = false;
            
            form.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], input[type="number"], textarea, select').forEach(function(input) {
                if (input.name && !input.name.includes('password')) {
                    const originalValue = originalValues[input.name] || '';
                    if (input.value !== originalValue) {
                        hasChanges = true;
                    }
                }
            });
            
            return hasChanges;
        }
        
        // Function to show/hide password verification based on changes
        function updatePasswordVerification(form) {
            const hasChanges = checkFormChanges(form);
            const passwordGroup = form.querySelector('[id$="-password-verification-group"]');
            const passwordInput = form.querySelector('input[name="settings_password"]');
            
            if (passwordGroup && passwordInput) {
                if (hasChanges) {
                    passwordGroup.style.display = 'block';
                    passwordInput.required = true;
                } else {
                    passwordGroup.style.display = 'none';
                    passwordInput.required = false;
                    passwordInput.value = '';
                }
            }
        }
        
        // Add change listeners to all form inputs
        document.querySelectorAll('.settings-form').forEach(function(form) {
            form.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], input[type="number"], textarea, select').forEach(function(input) {
                if (!input.name || !input.name.includes('password')) {
                    input.addEventListener('input', function() {
                        updatePasswordVerification(form);
                    });
                    input.addEventListener('change', function() {
                        updatePasswordVerification(form);
                    });
                }
            });
        });
        
        // Toggle section maintenance message visibility
        function toggleSectionMessage(key) {
            const checkbox = document.getElementById('section_maintenance_' + key);
            const messageDiv = document.getElementById('section-message-' + key);
            const statusText = document.getElementById('section-status-' + key);
            
            if (checkbox.checked) {
                if (messageDiv) messageDiv.style.display = 'block';
                if (statusText) statusText.textContent = 'Enabled';
            } else {
                if (messageDiv) messageDiv.style.display = 'none';
                if (statusText) statusText.textContent = 'Disabled';
            }
            
            // Show password verification if any changes are made
            checkSectionMaintenanceChanges();
            updateMasterToggleStatus();
        }
        
        // Toggle subpage maintenance message visibility
        function toggleSubpageMessage(sectionKey, subKey) {
            const checkbox = document.getElementById('section_maintenance_' + sectionKey + '_' + subKey);
            const messageDiv = document.getElementById('subpage-message-' + sectionKey + '-' + subKey);
            const statusText = document.getElementById('subpage-status-' + sectionKey + '-' + subKey);
            
            if (checkbox.checked) {
                if (messageDiv) messageDiv.style.display = 'block';
                if (statusText) {
                    statusText.textContent = 'On';
                    statusText.style.background = '#dbeafe';
                    statusText.style.color = '#1e40af';
                }
            } else {
                if (messageDiv) messageDiv.style.display = 'none';
                if (statusText) {
                    statusText.textContent = 'Off';
                    statusText.style.background = '#e5e7eb';
                    statusText.style.color = '#6b7280';
                }
            }
            
            // Show password verification if any changes are made
            checkSectionMaintenanceChanges();
            updateMasterToggleStatus();
        }
        
        // Toggle all maintenance switches
        function toggleAllMaintenance(enable) {
            // Prevent the default change event to avoid conflicts
            const masterToggle = document.getElementById('toggle-all-maintenance');
            if (masterToggle) {
                masterToggle.checked = enable;
                masterToggle.indeterminate = false;
            }
            
            // Toggle all switches (both main sections and sub-pages)
            document.querySelectorAll('[id^="section_maintenance_"]').forEach(function(checkbox) {
                // Skip the master toggle itself
                if (checkbox.id === 'toggle-all-maintenance') return;
                
                const id = checkbox.id.replace('section_maintenance_', '');
                const parts = id.split('_');
                
                // Set the checkbox state
                checkbox.checked = enable;
                
                // Trigger the appropriate toggle function
                if (parts.length === 1) {
                    // Main section switch
                    const key = parts[0];
                    const messageDiv = document.getElementById('section-message-' + key);
                    const statusText = document.getElementById('section-status-' + key);
                    
                    if (enable) {
                        if (messageDiv) messageDiv.style.display = 'block';
                        if (statusText) statusText.textContent = 'Enabled';
                    } else {
                        if (messageDiv) messageDiv.style.display = 'none';
                        if (statusText) statusText.textContent = 'Disabled';
                    }
                } else {
                    // Sub-page switch
                    const sectionKey = parts[0];
                    const subKey = parts.slice(1).join('_');
                    const messageDiv = document.getElementById('subpage-message-' + sectionKey + '-' + subKey);
                    const statusText = document.getElementById('subpage-status-' + sectionKey + '-' + subKey);
                    
                    if (enable) {
                        if (messageDiv) messageDiv.style.display = 'block';
                        if (statusText) {
                            statusText.textContent = 'On';
                            statusText.style.background = '#dbeafe';
                            statusText.style.color = '#1e40af';
                        }
                    } else {
                        if (messageDiv) messageDiv.style.display = 'none';
                        if (statusText) {
                            statusText.textContent = 'Off';
                            statusText.style.background = '#e5e7eb';
                            statusText.style.color = '#6b7280';
                        }
                    }
                }
            });
            
            // Show password verification since changes were made
            checkSectionMaintenanceChanges();
            
            // Update master toggle status
            setTimeout(function() {
                updateMasterToggleStatus();
            }, 100);
        }
        
        // Update master toggle status based on current state
        function updateMasterToggleStatus() {
            const allCheckboxes = document.querySelectorAll('[id^="section_maintenance_"]');
            // Filter out the master toggle itself
            const maintenanceCheckboxes = Array.from(allCheckboxes).filter(function(cb) {
                return cb.id !== 'toggle-all-maintenance';
            });
            
            if (maintenanceCheckboxes.length === 0) {
                return;
            }
            
            let allEnabled = true;
            let allDisabled = true;
            
            maintenanceCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    allDisabled = false;
                } else {
                    allEnabled = false;
                }
            });
            
            const masterToggle = document.getElementById('toggle-all-maintenance');
            const masterStatus = document.getElementById('master-status');
            
            if (masterToggle) {
                masterToggle.indeterminate = false;
            }
            
            if (allEnabled) {
                if (masterToggle) {
                    masterToggle.checked = true;
                    masterToggle.indeterminate = false;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'All Enabled';
                    masterStatus.style.background = '#dbeafe';
                    masterStatus.style.color = '#1e40af';
                }
            } else if (allDisabled) {
                if (masterToggle) {
                    masterToggle.checked = false;
                    masterToggle.indeterminate = false;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'All Disabled';
                    masterStatus.style.background = '#e5e7eb';
                    masterStatus.style.color = '#6b7280';
                }
            } else {
                if (masterToggle) {
                    masterToggle.checked = false;
                    masterToggle.indeterminate = true;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'Mixed';
                    masterStatus.style.background = '#fef3c7';
                    masterStatus.style.color = '#92400e';
                }
            }
        }
        
        // Initialize master toggle status on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateMasterToggleStatus();
            updateNavbarMasterToggleStatus();
            
            // Ensure password groups are hidden on page load
            const navbarPasswordGroup = document.getElementById('navbar-visibility-password-verification-group');
            const sectionPasswordGroup = document.getElementById('section-maintenance-password-verification-group');
            if (navbarPasswordGroup) {
                navbarPasswordGroup.style.display = 'none';
            }
            if (sectionPasswordGroup) {
                sectionPasswordGroup.style.display = 'none';
            }
            
            // Initialize original navbar states for change tracking
            checkNavbarVisibilityChanges(); // Initialize on page load
            checkSectionMaintenanceChanges(); // Initialize on page load
        });
        
        // Navbar Visibility Functions
        function updateNavbarStatus(key) {
            const checkbox = document.getElementById('navbar_item_' + key);
            const statusText = document.getElementById('navbar-status-' + key);
            
            if (checkbox.checked) {
                if (statusText) {
                    statusText.textContent = 'Visible';
                    statusText.style.background = '#dbeafe';
                    statusText.style.color = '#1e40af';
                }
            } else {
                if (statusText) {
                    statusText.textContent = 'Hidden';
                    statusText.style.background = '#e5e7eb';
                    statusText.style.color = '#6b7280';
                }
            }
            
            checkNavbarVisibilityChanges();
            updateNavbarMasterToggleStatus();
        }
        
        function updateNavbarSubitemStatus(key, subKey) {
            const checkbox = document.getElementById('navbar_item_' + key + '_' + subKey);
            const statusText = document.getElementById('navbar-subitem-status-' + key + '-' + subKey);
            
            if (checkbox.checked) {
                if (statusText) {
                    statusText.textContent = 'Visible';
                    statusText.style.background = '#dbeafe';
                    statusText.style.color = '#1e40af';
                }
            } else {
                if (statusText) {
                    statusText.textContent = 'Hidden';
                    statusText.style.background = '#e5e7eb';
                    statusText.style.color = '#6b7280';
                }
            }
            
            checkNavbarVisibilityChanges();
            updateNavbarMasterToggleStatus();
        }
        
        function toggleAllNavbar(enable) {
            const masterToggle = document.getElementById('toggle-all-navbar');
            if (masterToggle) {
                masterToggle.checked = enable;
                masterToggle.indeterminate = false;
            }
            
            document.querySelectorAll('[id^="navbar_item_"]').forEach(function(checkbox) {
                if (checkbox.id === 'toggle-all-navbar') return;
                
                checkbox.checked = enable;
                
                const id = checkbox.id.replace('navbar_item_', '');
                const parts = id.split('_');
                
                if (parts.length === 1) {
                    updateNavbarStatus(parts[0]);
                } else {
                    const key = parts[0];
                    const subKey = parts.slice(1).join('_');
                    updateNavbarSubitemStatus(key, subKey);
                }
            });
            
            setTimeout(function() {
                updateNavbarMasterToggleStatus();
            }, 100);
        }
        
        function updateNavbarMasterToggleStatus() {
            const allCheckboxes = document.querySelectorAll('[id^="navbar_item_"]');
            const navbarCheckboxes = Array.from(allCheckboxes).filter(function(cb) {
                return cb.id !== 'toggle-all-navbar';
            });
            
            if (navbarCheckboxes.length === 0) {
                return;
            }
            
            let allEnabled = true;
            let allDisabled = true;
            
            navbarCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    allDisabled = false;
                } else {
                    allEnabled = false;
                }
            });
            
            const masterToggle = document.getElementById('toggle-all-navbar');
            const masterStatus = document.getElementById('navbar-master-status');
            
            if (masterToggle) {
                masterToggle.indeterminate = false;
            }
            
            if (allEnabled) {
                if (masterToggle) {
                    masterToggle.checked = true;
                    masterToggle.indeterminate = false;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'All Enabled';
                    masterStatus.style.background = '#dbeafe';
                    masterStatus.style.color = '#1e40af';
                }
            } else if (allDisabled) {
                if (masterToggle) {
                    masterToggle.checked = false;
                    masterToggle.indeterminate = false;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'All Disabled';
                    masterStatus.style.background = '#e5e7eb';
                    masterStatus.style.color = '#6b7280';
                }
            } else {
                if (masterToggle) {
                    masterToggle.checked = false;
                    masterToggle.indeterminate = true;
                }
                if (masterStatus) {
                    masterStatus.textContent = 'Mixed';
                    masterStatus.style.background = '#fef3c7';
                    masterStatus.style.color = '#92400e';
                }
            }
        }
        
        function checkNavbarVisibilityChanges() {
            const form = document.getElementById('navbar-visibility-form');
            const passwordGroup = document.getElementById('navbar-visibility-password-verification-group');
            const passwordInput = document.getElementById('navbar_visibility_password');
            
            if (!form || !passwordGroup || !passwordInput) return;
            
            // Track original states if not already tracked
            if (!form.originalNavbarStates) {
                form.originalNavbarStates = {};
                form.querySelectorAll('[id^="navbar_item_"]').forEach(function(checkbox) {
                    if (checkbox.id !== 'toggle-all-navbar') {
                        form.originalNavbarStates[checkbox.id] = checkbox.checked;
                    }
                });
            }
            
            // Check if any checkbox state has changed from original
            let hasChanges = false;
            const checkboxes = form.querySelectorAll('[id^="navbar_item_"]');
            for (let i = 0; i < checkboxes.length; i++) {
                const checkbox = checkboxes[i];
                if (checkbox.id === 'toggle-all-navbar') continue;
                
                const originalState = form.originalNavbarStates[checkbox.id];
                if (originalState !== undefined && checkbox.checked !== originalState) {
                    hasChanges = true;
                    break; // Break out of loop
                }
            }
            
            // Use explicit display style to ensure it's hidden when no changes
            if (hasChanges) {
                passwordGroup.style.display = 'block';
                passwordInput.setAttribute('required', 'required');
                passwordInput.required = true;
            } else {
                passwordGroup.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordInput.required = false;
                passwordInput.value = '';
            }
        }
        
        // Add change listeners to navbar visibility form
        const navbarFormChange = document.getElementById('navbar-visibility-form');
        if (navbarFormChange) {
            navbarFormChange.addEventListener('change', function() {
                checkNavbarVisibilityChanges();
                updateNavbarMasterToggleStatus();
            });
        }
        
        // Check if section maintenance form has changes
        function checkSectionMaintenanceChanges() {
            const form = document.getElementById('section-maintenance-form');
            const passwordGroup = document.getElementById('section-maintenance-password-verification-group');
            const passwordInput = document.getElementById('section_maintenance_password');
            
            if (!form || !passwordGroup || !passwordInput) return;
            
            // Track original states if not already tracked
            if (!form.originalStates) {
                form.originalStates = {};
                form.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                    form.originalStates[checkbox.id] = checkbox.checked;
                });
            }
            
            // Track original textarea values if not already tracked
            if (!form.originalTextareaValues) {
                form.originalTextareaValues = {};
                form.querySelectorAll('textarea').forEach(function(textarea) {
                    form.originalTextareaValues[textarea.id] = textarea.value.trim();
                });
            }
            
            let hasChanges = false;
            
            // Check checkbox changes
            form.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                const originalState = form.originalStates[checkbox.id];
                if (originalState !== undefined && checkbox.checked !== originalState) {
                    hasChanges = true;
                }
            });
            
            // Check textarea changes (only if value changed from original)
            if (!hasChanges) {
                form.querySelectorAll('textarea').forEach(function(textarea) {
                    const originalValue = form.originalTextareaValues[textarea.id] || '';
                    const currentValue = textarea.value.trim();
                    if (originalValue !== currentValue) {
                        hasChanges = true;
                    }
                });
            }
            
            if (hasChanges) {
                passwordGroup.style.display = 'block';
                passwordInput.setAttribute('required', 'required');
                passwordInput.required = true;
            } else {
                passwordGroup.style.display = 'none';
                passwordInput.removeAttribute('required');
                passwordInput.required = false;
                passwordInput.value = '';
            }
        }
        
        // Add change listeners to section maintenance form
        const sectionMaintenanceForm = document.getElementById('section-maintenance-form');
        if (sectionMaintenanceForm) {
            sectionMaintenanceForm.addEventListener('change', function() {
                checkSectionMaintenanceChanges();
                updateMasterToggleStatus();
            });
            sectionMaintenanceForm.addEventListener('input', function() {
                checkSectionMaintenanceChanges();
            });
        }
        
        // Validate section maintenance form before submission
        const sectionMaintenanceFormSubmit = document.getElementById('section-maintenance-form');
        if (sectionMaintenanceFormSubmit) {
            sectionMaintenanceFormSubmit.addEventListener('submit', function(e) {
                const passwordInput = document.getElementById('section_maintenance_password');
                const passwordGroup = document.getElementById('section-maintenance-password-verification-group');
                
                if (passwordGroup && passwordInput) {
                    // Check visibility using computed style - most reliable method
                    const computedStyle = window.getComputedStyle(passwordGroup);
                    const isVisible = computedStyle.display !== 'none' && computedStyle.display !== '';
                    
                    if (isVisible) {
                        // Get password value directly from input
                        let passwordValue = passwordInput.value || '';
                        passwordValue = passwordValue.trim();
                        
                        if (!passwordValue || passwordValue.length === 0) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Password verification is required to save section maintenance settings.');
                            passwordInput.focus();
                            return false;
                        }
                    }
                }
                // If validation passes, allow form to submit normally
                return true;
            });
        }
        
        // Validate navbar visibility form before submission
        const navbarForm = document.getElementById('navbar-visibility-form');
        if (navbarForm) {
            navbarForm.addEventListener('submit', function(e) {
                const passwordInput = document.getElementById('navbar_visibility_password');
                const passwordGroup = document.getElementById('navbar-visibility-password-verification-group');
                
                // Only check password if the group is visible (meaning there are changes)
                if (passwordGroup && passwordInput) {
                    // Check visibility using computed style - this is the most reliable method
                    const computedStyle = window.getComputedStyle(passwordGroup);
                    const isVisible = computedStyle.display !== 'none' && computedStyle.display !== '';
                    
                    // Also check if required attribute is set
                    const isRequired = passwordInput.hasAttribute('required') && passwordInput.required;
                    
                    if (isVisible || isRequired) {
                        // Get password value directly from input - use getAttribute to ensure we get the actual value
                        let passwordValue = passwordInput.value;
                        if (!passwordValue) {
                            // Try getting it from the form data as fallback
                            passwordValue = '';
                        }
                        passwordValue = passwordValue.trim();
                        
                        // Only require password if the field is actually visible and empty
                        if (!passwordValue || passwordValue.length === 0) {
                            e.preventDefault();
                            e.stopPropagation();
                            alert('Password verification is required to save navbar visibility settings.');
                            passwordInput.focus();
                            return false;
                        }
                    }
                }
                // If validation passes, allow form to submit normally
                return true;
            });
        }
    </script>

    <script>
        function deleteProgram(id) {
            if (!confirm('Delete this program and all PDFs? This cannot be undone.')) return;
            const tokenField = document.querySelector('input[name="_token"]');
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            const action = document.createElement('input'); action.type='hidden'; action.name='action'; action.value='library_delete_program'; form.appendChild(action);
            const idf = document.createElement('input'); idf.type='hidden'; idf.name='id'; idf.value=id; form.appendChild(idf);
            if (tokenField) { const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value = tokenField.value; form.appendChild(t); }
            document.body.appendChild(form);
            form.submit();
        }

        async function startUpload(e) {
            e.preventDefault();
            const programId = document.getElementById('upload_program_id').value;
            const filesInput = document.getElementById('upload_files');
            const statusEl = document.getElementById('upload_status');
            const progressBar = document.getElementById('upload_progress');

            if (!programId) { alert('Please select a program.'); return; }
            if (!filesInput.files || filesInput.files.length === 0) { alert('Select at least one PDF file.'); return; }

            const form = new FormData();
            form.append('program_id', programId);
            const tokenField = document.querySelector('input[name="_token"]');
            if (tokenField) form.append('_token', tokenField.value);
            for (let i=0;i<filesInput.files.length;i++) form.append('files[]', filesInput.files[i]);

            statusEl.textContent = '';
            progressBar.style.width = '0%';
            progressBar.parentElement.style.display = 'block';

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax-library-programs-upload.php');
            xhr.upload.onprogress = function(evt) {
                if (evt.lengthComputable) {
                    const pct = Math.round((evt.loaded / evt.total) * 100);
                    progressBar.style.width = pct + '%';
                    progressBar.textContent = pct + '%';
                    if (pct === 100) progressBar.textContent = 'Processing…';
                }
            };
            xhr.onload = function() {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data.success) {
                        statusEl.textContent = data.message || 'Upload successful.';
                        // refresh the file list for the program
                        refreshPdfList(programId);
                        filesInput.value = '';
                    } else {
                        statusEl.textContent = data.error || 'Upload failed.';
                    }
                } catch (e) {
                    statusEl.textContent = 'Upload error: invalid server response';
                }
                setTimeout(()=>{ progressBar.parentElement.style.display='none'; progressBar.style.width='0%'; progressBar.textContent=''; }, 900);
            };
            xhr.onerror = function() { statusEl.textContent = 'Upload failed (network).'; };
            xhr.send(form);
        }

        // Refresh PDF list for a program via AJAX
        async function refreshPdfList(programId) {
            try {
                const resp = await fetch('ajax-library-programs-list.php?program_id='+encodeURIComponent(programId));
                const data = await resp.json();
                if (!data.success) return;
                const container = document.getElementById('pdf-list-'+programId);
                if (!container) return;
                container.innerHTML = '';
                if (!data.files || data.files.length === 0) {
                    container.innerHTML = '<div style="color:#6b7280;">No PDFs uploaded yet.</div>';
                    return;
                }

                data.files.forEach(function(pdf){
                    const row = document.createElement('div'); row.className = 'pdf-row'; row.setAttribute('data-pdf-id', pdf.id);

                    const p = window.location.pathname.split('/admin/');
                    const base = p.length > 1 ? p[0] : '';
                    const rel = (pdf.path || '').replace(/^\/+/, '');
                    const href = (base === '' ? '/' + rel : base + '/' + rel);

                    const a = document.createElement('a'); a.href = href; a.target = '_blank'; a.textContent = pdf.filename; a.style.flex='1'; a.style.color='#1e3a8a'; a.style.textDecoration='underline';
                    const t = document.createElement('span'); t.style.color='#6b7280'; t.style.fontSize='0.85rem'; t.textContent = pdf.uploaded_at;
                    const btn = document.createElement('button'); btn.className='btn btn-icon'; btn.title='Remove PDF'; btn.style.background='#f59e0b'; btn.style.color='#fff'; btn.onclick = function(){ deletePdf(pdf.id, programId); };
                    btn.innerHTML = '<i class="fas fa-minus-circle"></i>';

                    row.appendChild(a); row.appendChild(t); row.appendChild(btn);
                    container.appendChild(row);
                });
            } catch (e) {
                console.error(e);
            }
        }

        // Delete PDF by id (AJAX)
        async function deletePdf(pdfId, programId) {
            if (!confirm('Remove this PDF from the program? The file will remain on the server.')) return;
            const tokenField = document.querySelector('input[name="_token"]');
            const form = new FormData(); form.append('pdf_id', pdfId); if (tokenField) form.append('_token', tokenField.value);
            try {
                const resp = await fetch('ajax-library-programs-delete.php', { method:'POST', body: form });
                const data = await resp.json();
                if (data.success) {
                    // remove from DOM
                    const el = document.querySelector('[data-pdf-id="'+pdfId+'"]'); if (el) el.remove();
                    // if list empty, show placeholder
                    const listEl = document.getElementById('pdf-list-'+programId);
                    if (listEl && listEl.children.length === 0) listEl.innerHTML = '<div style="color:#6b7280;">No PDFs uploaded yet.</div>';
                } else {
                    alert(data.error || 'Delete failed');
                }
            } catch (e) { alert('Delete error'); }
        }

        // Open edit modal for a program
        function openEditModal(programId) {
            const dataEl = document.getElementById('program-data-' + programId);
            if (!dataEl) return;
            const title = dataEl.getAttribute('data-title') || '';
            const desc = dataEl.getAttribute('data-desc') || '';
            document.getElementById('modal_program_id').value = programId;
            document.getElementById('modal_title').value = title;
            document.getElementById('modal_description').value = desc;
            // refresh pdf list into modal area
            refreshPdfListIntoModal(programId);
            document.getElementById('editModal').style.display = 'flex';
            // wire choose existing button
            setTimeout(()=>{
                const chooseBtn = document.getElementById('modal_choose_existing');
                if (chooseBtn) {
                    chooseBtn.onclick = function(){ openChooseExisting(programId); };
                }
            },10);
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('modal_upload_status') && (document.getElementById('modal_upload_status').textContent = '');
        }
        

        // Refresh PDF list but target modal container
        async function refreshPdfListIntoModal(programId) {
            try {
                const resp = await fetch('ajax-library-programs-list.php?program_id='+encodeURIComponent(programId));
                const data = await resp.json();
                const container = document.getElementById('modal_pdf_list');
                if (!container) return;
                container.innerHTML = '';
                if (!data.success || !data.files || data.files.length === 0) {
                    container.innerHTML = '<div style="color:#6b7280;">No PDFs uploaded yet.</div>';
                    return;
                }
                data.files.forEach(function(pdf){
                    const row = document.createElement('div'); row.className = 'pdf-row'; row.setAttribute('data-pdf-id', pdf.id);

                    const p = window.location.pathname.split('/admin/');
                    const base = p.length > 1 ? p[0] : '';
                    const rel = (pdf.path || '').replace(/^\/+/, '');
                    const href = (base === '' ? '/' + rel : base + '/' + rel);

                    const a = document.createElement('a'); a.href = href; a.target = '_blank'; a.textContent = pdf.filename; a.style.flex='1'; a.style.color='#1e3a8a'; a.style.textDecoration='underline';
                    const t = document.createElement('span'); t.textContent = pdf.uploaded_at; t.style.color='#6b7280'; t.style.fontSize='0.85rem';
                    const btn = document.createElement('button'); btn.className = 'btn btn-icon'; btn.title = 'Delete PDF'; btn.style.background = '#ef4444'; btn.style.color = '#fff'; btn.onclick = function(){ deletePdf(pdf.id, programId); row.remove(); };
                    btn.innerHTML = '<i class="fas fa-trash"></i>';
                    row.appendChild(a); row.appendChild(t); row.appendChild(btn);
                    container.appendChild(row);
                });
            } catch (e) { console.error(e); }
        }

        // AJAX-save modal form to avoid full page reload
        document.getElementById('modal_edit_form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formEl = this;
            const form = new FormData(formEl);
            // append CSRF token if present on page
            const tokenField = document.querySelector('input[name="_token"]');
            if (tokenField) form.append('_token', tokenField.value);

            const statusEl = document.getElementById('modal_edit_status');
            statusEl.textContent = 'Saving...';

            try {
                const resp = await fetch('ajax-library-programs-update.php', { method: 'POST', body: form });
                const data = await resp.json();
                if (data && data.success) {
                    statusEl.textContent = data.message || 'Saved.';
                    // update program list entry
                    const id = form.get('id');
                    const title = form.get('title');
                    const desc = form.get('description');
                    const dataEl = document.getElementById('program-data-' + id);
                    if (dataEl) {
                        dataEl.setAttribute('data-title', title);
                        dataEl.setAttribute('data-desc', desc);
                        const li = dataEl.closest('li');
                        if (li) {
                            const titleBtn = li.querySelector('button');
                            if (titleBtn) {
                                // update inner HTML to keep slug line intact
                                const slugDiv = titleBtn.querySelector('div');
                                const slugText = slugDiv ? slugDiv.innerText : '';
                                titleBtn.innerHTML = title + (slugText ? ('<div style="font-size:0.85rem;color:#6b7280;margin-top:4px;font-weight:400;">' + slugText + '</div>') : '');
                            }
                        }
                    }
                    // brief highlight
                    setTimeout(()=>{ statusEl.textContent = ''; }, 2500);
                } else {
                    statusEl.textContent = data.error || 'Save failed.';
                }
            } catch (err) {
                statusEl.textContent = 'Save error';
            }
        });

        // --- Choose existing files UI ---
        function openChooseExisting(programId) {
            const overlay = document.createElement('div'); overlay.style.position='fixed'; overlay.style.inset='0'; overlay.style.background='rgba(0,0,0,0.5)'; overlay.style.display='flex'; overlay.style.alignItems='center'; overlay.style.justifyContent='center'; overlay.style.zIndex='3000';
            const box = document.createElement('div'); box.style.background='#fff'; box.style.width='720px'; box.style.maxHeight='80vh'; box.style.overflow='auto'; box.style.borderRadius='10px'; box.style.padding='12px';
            const title = document.createElement('div'); title.textContent='Choose existing PDFs'; title.style.fontWeight='700'; title.style.marginBottom='8px';
            const list = document.createElement('div'); list.style.display='grid'; list.style.gridTemplateColumns='repeat(2,1fr)'; list.style.gap='8px'; list.style.marginBottom='8px';
            const footer = document.createElement('div'); footer.style.display='flex'; footer.style.justifyContent='flex-end'; footer.style.gap='8px';
            const btnCancel = document.createElement('button'); btnCancel.className='btn'; btnCancel.textContent='Cancel';
            const btnAttach = document.createElement('button'); btnAttach.className='btn btn-primary'; btnAttach.textContent='Attach selected';
            footer.appendChild(btnCancel); footer.appendChild(btnAttach);
            box.appendChild(title); box.appendChild(list); box.appendChild(footer); overlay.appendChild(box); document.body.appendChild(overlay);

            btnCancel.onclick = function(){ overlay.remove(); };

            fetch('ajax-library-programs-scan.php?program_id='+encodeURIComponent(programId)).then(r=>r.json()).then(data=>{
                if (!data.success) { list.innerHTML = '<div style="color:#6b7280">'+(data.error||'Scan failed')+'</div>'; return; }
                const attached = data.attached || [];
                data.files.forEach(f => {
                    const id = 'choose_file_'+Math.random().toString(36).slice(2,9);
                    const el = document.createElement('label'); el.style.display='flex'; el.style.alignItems='center'; el.style.gap='8px'; el.style.padding='6px'; el.style.border='1px solid #eef2ff'; el.style.borderRadius='6px';
                    const cb = document.createElement('input'); cb.type='checkbox'; cb.id = id; cb.value = f.filename; if (attached.indexOf(f.filename) !== -1) { cb.disabled = true; }
                    const txt = document.createElement('span'); txt.textContent = f.filename; txt.style.fontSize='0.95rem'; txt.style.color = attached.indexOf(f.filename) !== -1 ? '#9ca3af' : '#0f172a';
                    el.appendChild(cb); el.appendChild(txt);
                    list.appendChild(el);
                });
            }).catch(err=>{ list.innerHTML = '<div style="color:#6b7280">Scan error</div>'; });

            btnAttach.onclick = function(){
                const checks = Array.from(list.querySelectorAll('input[type=checkbox]:not(:disabled):checked')).map(i=>i.value);
                if (checks.length === 0) { alert('Select files to attach'); return; }
                const form = new FormData(); form.append('program_id', programId); const tokenField = document.querySelector('input[name="_token"]'); if (tokenField) form.append('_token', tokenField.value);
                checks.forEach(f => form.append('files[]', f));
                fetch('ajax-library-programs-attach.php', { method: 'POST', body: form }).then(r=>r.json()).then(resp=>{
                    if (resp && resp.success) {
                        overlay.remove(); refreshPdfListIntoModal(programId);
                        if (resp.added && resp.added.length) alert('Attached: '+resp.added.join(', '));
                        else if (resp.skipped && resp.skipped.length) alert('Already attached: '+resp.skipped.join(', '));
                    } else {
                        alert(resp.error || 'Attach failed');
                    }
                }).catch(()=>{ alert('Attach error'); });
            };
        }

	</script>

<?php include '../app/includes/admin-footer.php'; ?>

<!-- Edit Modal -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);align-items:flex-start;justify-content:center;overflow:auto;z-index:2000;">
    <div class="settings-card" style="max-width:820px;width:95%;margin:40px auto;position:relative;max-height:calc(100vh - 120px);overflow:auto;padding:20px;">
        <button onclick="closeEditModal()" style="position:absolute;right:12px;top:12px;background:transparent;border:none;font-size:22px;color:var(--text-dark);">&times;</button>
        <div class="settings-card-header" style="padding-bottom:10px;margin-bottom:6px;border-bottom:1px solid #eef2ff;">
            <h2 style="font-size:1.25rem;display:flex;align-items:center;gap:8px;margin:0;"><i class="fas fa-book"></i> Edit Program</h2>
            <p class="settings-description" style="margin:6px 0 0 0;color:var(--text-light);">Edit program details and manage uploaded PDFs.</p>
        </div>

        <div id="modal_edit_status" style="margin-bottom:8px;color:#0f172a;font-weight:600;"></div>
        <form method="POST" style="display:flex;flex-direction:column;gap:10px;" id="modal_edit_form">
            <?php echo CSRF::field(); ?>
            <input type="hidden" name="action" value="library_update_program">
            <input type="hidden" name="id" id="modal_program_id">
            <div>
                <label class="form-label">Title</label>
                <input id="modal_title" name="title" class="form-input" style="padding:8px;font-size:0.95rem;">
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea id="modal_description" name="description" class="form-textarea" rows="4" style="padding:8px;font-size:0.95rem;"></textarea>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <button type="submit" class="btn btn-icon btn-primary" title="Save changes"><i class="fas fa-save"></i></button>
                <button type="button" class="btn btn-icon" title="Delete program" style="background:#ef4444;color:#fff;" onclick="if(confirm('Delete this program and all PDFs?')){ deleteProgram(parseInt(document.getElementById('modal_program_id').value)); }"><i class="fas fa-trash"></i></button>
                <div id="modal_edit_status" style="margin-left:8px;color:#374151;font-size:0.95rem;"></div>
            </div>
        </form>

        <hr style="margin:16px 0;border-color:#eef2ff;">

        <h4 style="margin:0 0 8px 0;color:var(--text-dark);">PDFs</h4>
        <div id="modal_pdf_list" style="margin-bottom:12px;color:#374151;"></div>

        <div style="display:flex;gap:8px;align-items:center;">
            <div style="color:#6b7280;font-size:0.95rem;">Uploads disabled here — use "Choose existing" to attach files already on the server.</div>
            <button class="btn" id="modal_choose_existing" style="padding:6px 10px;" title="Choose existing files">Choose existing</button>
        </div>
    </div>
</div>


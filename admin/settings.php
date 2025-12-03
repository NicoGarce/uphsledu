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
// Session is automatically initialized by security.php

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
            'guestold-student' => 'Guest Old Student (Enrolled Students)'
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

<?php include '../app/includes/admin-footer.php'; ?>


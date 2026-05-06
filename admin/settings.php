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
            // snapshot modal values to gate Save button
            setModalFormOriginalSnapshot();
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
                    'subpages' => ['support-services-index', 'careers', 'clinic', 'cod', 'iea', 'sao', 'library', 'quality-assurance', 'research']
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
            'support-services' => ['name' => 'Support Services', 'subitems' => ['alumni', 'careers', 'clinic', 'cod', 'iea', 'sao', 'library', 'quality-assurance', 'research']],
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
                        'sao' => 'Student Affairs Office',
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
            'sao' => 'Student Affairs Office',
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
            'sao' => 'Student Affairs Office',
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && in_array($_POST['action'], ['library_create_program','library_update_program','library_delete_program','set_library_programs_source','library_cas_create','library_cas_update','library_cas_delete','set_library_cas_source'])) {
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
            } elseif ($_POST['action'] === 'set_library_cas_source') {
                $source = isset($_POST['library_cas_source']) && $_POST['library_cas_source'] === 'db' ? 'db' : 'static';
                if (setSetting('library_cas_source', $source, 'text', 'Library CAS source (db|static)', $_SESSION['user_id'])) {
                    $success = 'Library CAS source updated.';
                } else {
                    $error = 'Failed to update Library CAS source.';
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
                        // Handle optional uploaded thumbnail image (takes precedence) or image URL
                        $image = '';
                        if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            $file = $_FILES['image'];
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                                $baseDir = __DIR__ . '/../assets/images/support-services/college-library/programs/' . $slug . '/';
                                if (!is_dir($baseDir)) { @mkdir($baseDir,0755,true); }
                                $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/','_',pathinfo($file['name'], PATHINFO_FILENAME));
                                $final = $safe . '.' . $ext;
                                $target = $baseDir . $final;
                                $counter = 1; while (file_exists($target)) { $final = $safe . '_' . $counter . '.' . $ext; $target = $baseDir . $final; $counter++; }
                                if (move_uploaded_file($file['tmp_name'], $target)) {
                                    @chmod($target, 0644);
                                    $image = 'assets/images/support-services/college-library/programs/' . $slug . '/' . $final;
                                }
                            }
                        }
                        if (empty($image)) {
                            $image = Validator::sanitize($_POST['image'] ?? '', 'string');
                        }
                        $link = Validator::sanitize($_POST['link'] ?? '', 'url');
                        $ins = $pdo->prepare('INSERT INTO library_programs (slug,title,description,image,link,created_at,updated_at) VALUES (:slug,:title,:description,:image,:link,NOW(),NOW())');
                        $ins->execute([':slug'=>$slug,':title'=>$title,':description'=>$description,':image'=>$image,':link'=>$link]);
                        $success = 'Program created successfully.';
                    }
            } elseif ($_POST['action'] === 'library_update_program') {
                $id = (int)($_POST['id'] ?? 0);
                $title = Validator::sanitize($_POST['title'] ?? '', 'string');
                $description = Validator::sanitize($_POST['description'] ?? '', 'string');
                $image = Validator::sanitize($_POST['image'] ?? '', 'string');
                $link = Validator::sanitize($_POST['link'] ?? '', 'url');
                if ($id <= 0) {
                    $error = 'Invalid program ID.';
                } else {
                    $parts = ['title = :title', 'description = :description', 'updated_at = NOW()'];
                    $params = [':title'=>$title, ':description'=>$description, ':id'=>$id];
                    if (!empty($image)) { $parts[] = 'image = :image'; $params[':image'] = $image; }
                    if (!empty($link)) { $parts[] = 'link = :link'; $params[':link'] = $link; }
                    $sql = 'UPDATE library_programs SET ' . implode(', ', $parts) . ' WHERE id = :id';
                    $up = $pdo->prepare($sql);
                    $up->execute($params);
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
                        // remove thumbnail image directory if present (new thumbnails are stored under assets/images/...)
                        $imgDir = __DIR__ . '/../assets/images/support-services/college-library/programs/' . $slug;
                        if (is_dir($imgDir)) {
                            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imgDir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
                            foreach ($files as $fileinfo) {
                                if ($fileinfo->isDir()) {
                                    @rmdir($fileinfo->getRealPath());
                                } else {
                                    @unlink($fileinfo->getRealPath());
                                }
                            }
                            @rmdir($imgDir);
                        }
                        // delete program record
                        $pdo->prepare('DELETE FROM library_programs WHERE id = :id')->execute([':id'=>$id]);
                        $success = 'Program deleted.';
                    } else {
                        $error = 'Program not found.';
                    }
                }
            } elseif ($_POST['action'] === 'library_cas_create') {
                $title = Validator::sanitize($_POST['title'] ?? '', 'string');
                $description = Validator::sanitize($_POST['description'] ?? '', 'string');
                $slug = Validator::sanitize($_POST['slug'] ?? '', 'slug');
                if (empty($slug)) {
                    $slug = preg_replace('/[^a-z0-9-]+/','-',strtolower(trim($title)));
                    $slug = trim($slug,'-');
                }

                $stmt = $pdo->prepare('SELECT id FROM library_cas WHERE slug = :slug');
                $stmt->execute([':slug' => $slug]);
                if ($stmt->fetch()) {
                    $error = 'An item with that slug already exists.';
                } else {
                    // Handle optional uploaded thumbnail image (takes precedence) or image URL
                    $image = '';
                    if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $file = $_FILES['image'];
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
                            $baseDir = __DIR__ . '/../assets/images/support-services/college-library/cas/' . $slug . '/';
                            if (!is_dir($baseDir)) { @mkdir($baseDir,0755,true); }
                            $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/','_',pathinfo($file['name'], PATHINFO_FILENAME));
                            $final = $safe . '.' . $ext;
                            $target = $baseDir . $final;
                            $counter = 1; while (file_exists($target)) { $final = $safe . '_' . $counter . '.' . $ext; $target = $baseDir . $final; $counter++; }
                            if (move_uploaded_file($file['tmp_name'], $target)) {
                                @chmod($target, 0644);
                                $image = 'assets/images/support-services/college-library/cas/' . $slug . '/' . $final;
                            }
                        }
                    }
                    if (empty($image)) {
                        $image = Validator::sanitize($_POST['image'] ?? '', 'string');
                    }
                    $link = Validator::sanitize($_POST['link'] ?? '', 'url');
                    $ins = $pdo->prepare('INSERT INTO library_cas (slug,title,description,image,link,created_at,updated_at) VALUES (:slug,:title,:description,:image,:link,NOW(),NOW())');
                    $ins->execute([':slug'=>$slug,':title'=>$title,':description'=>$description,':image'=>$image,':link'=>$link]);
                    $success = 'Item created successfully.';
                }
            } elseif ($_POST['action'] === 'library_cas_update') {
                $id = (int)($_POST['id'] ?? 0);
                $title = Validator::sanitize($_POST['title'] ?? '', 'string');
                $description = Validator::sanitize($_POST['description'] ?? '', 'string');
                $image = Validator::sanitize($_POST['image'] ?? '', 'string');
                $link = Validator::sanitize($_POST['link'] ?? '', 'url');
                if ($id <= 0) {
                    $error = 'Invalid item ID.';
                } else {
                    $parts = ['title = :title', 'description = :description', 'updated_at = NOW()'];
                    $params = [':title'=>$title, ':description'=>$description, ':id'=>$id];
                    if (!empty($image)) { $parts[] = 'image = :image'; $params[':image'] = $image; }
                    if (!empty($link)) { $parts[] = 'link = :link'; $params[':link'] = $link; }
                    $sql = 'UPDATE library_cas SET ' . implode(', ', $parts) . ' WHERE id = :id';
                    $up = $pdo->prepare($sql);
                    $up->execute($params);
                    $success = 'Item updated successfully.';
                }
            } elseif ($_POST['action'] === 'library_cas_delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $error = 'Invalid item ID.';
                } else {
                    $s = $pdo->prepare('SELECT slug FROM library_cas WHERE id = :id');
                    $s->execute([':id'=>$id]);
                    $row = $s->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $slug = $row['slug'];
                        $imgDir = __DIR__ . '/../assets/images/support-services/college-library/cas/' . $slug;
                        if (is_dir($imgDir)) {
                            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imgDir, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
                            foreach ($files as $fileinfo) {
                                if ($fileinfo->isDir()) {
                                    @rmdir($fileinfo->getRealPath());
                                } else {
                                    @unlink($fileinfo->getRealPath());
                                }
                            }
                            @rmdir($imgDir);
                        }
                        $pdo->prepare('DELETE FROM library_cas WHERE id = :id')->execute([':id'=>$id]);
                        $success = 'Item deleted.';
                    } else {
                        $error = 'Item not found.';
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

        <!-- Quick Navigation & Search -->
        <div class="settings-section" id="settings-quicknav">
            <div class="settings-card">
                <div style="display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;">
                    <div style="display:flex;gap:8px;align-items:center;flex:1;min-width:220px;">
                        <a href="#section-general" class="btn quicknav-link" data-target="section-general">General</a>
                        <a href="#section-contact" class="btn quicknav-link" data-target="section-contact">Contact</a>
                        <a href="#section-social" class="btn quicknav-link" data-target="section-social">Social</a>
                        <a href="#section-library-programs" class="btn quicknav-link" data-target="section-library-programs">Library</a>
                    </div>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input id="settings_search" type="search" placeholder="Search settings..." class="form-input" style="min-width:220px;max-width:420px;padding:8px 12px;">
                        <button id="settings_search_clear" class="btn" type="button">Clear</button>
                    </div>
                </div>
                <p class="settings-description" style="margin-top:10px;">Quickly jump to a settings card or search to filter settings cards by name or description.</p>
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
        <div id="section-general" class="settings-section">
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
        <div id="section-contact" class="settings-section">
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
        <div id="section-social" class="settings-section">
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

        <!-- Library Programs Manager -->
        <div id="section-library-programs" class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header no-divider-bottom">
                    <h2>
                        <i class="fas fa-book"></i>
                        Library Programs Manager
                    </h2>
                    <p class="settings-description">Create, edit, delete library programs and upload multiple PDFs per program. Uploaded PDFs are stored under <strong>assets/documents/library/programs/{slug}/</strong>.</p>
                </div>

                <?php
                // Fetch programs from DB. PDF attachments are deprecated so we no longer query library_program_pdfs.
                $programs = [];
                $program_pdfs = []; // kept as empty for backward compatibility in templates
                try {
                    $st = $pdo->query('SELECT id, slug, title, description, image, link FROM library_programs ORDER BY created_at ASC');
                    $programs = $st->fetchAll(PDO::FETCH_ASSOC);
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
                        <form method="POST" id="create_program_form" class="settings-form" novalidate enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:8px;">
                            <?php echo CSRF::field(); ?>
                            <input type="hidden" name="action" value="library_create_program">
                            <div style="display:grid;grid-template-columns:1fr 220px;gap:12px;align-items:start;">
                                <div>
                                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                                        <input id="create_title" type="text" name="title" class="form-input" placeholder="Program title" style="flex:1;min-width:140px;padding:8px;font-size:0.95rem;" required>
                                        <input type="text" name="slug" class="form-input" placeholder="slug (optional)" style="width:140px;padding:8px;font-size:0.95rem;">
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:6px;">Description</label>
                                        <textarea id="create_description" name="description" class="form-textarea" rows="3" style="padding:8px;font-size:0.95rem;min-height:80px;" placeholder="Short description (required)"></textarea>
                                    </div>
                                    <div style="margin-top:8px;display:flex;gap:8px;align-items:center;">
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:0;">Thumbnail</label>
                                        <input id="create_image_file" type="file" name="image" accept="image/*" class="form-input" style="padding:6px;font-size:0.95rem;" onchange="previewCreateImageFile(this); checkCreateForm();">
                                    </div>
                                    <div style="margin-top:8px;">
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:6px;">Google Drive Link</label>
                                        <input id="create_link" type="url" name="link" class="form-input" placeholder="https://drive.google.com/..." style="padding:8px;font-size:0.95rem;">
                                    </div>
                                    <div style="margin-top:12px;">
                                        <button type="submit" id="create_program_btn" class="btn btn-primary" style="padding:8px 12px;font-size:0.95rem;" disabled>Create Program</button>
                                    </div>
                                </div>
                                <div style="border-left:1px solid #eef2ff;padding-left:12px;display:flex;flex-direction:column;align-items:center;gap:8px;">
                                    <div style="font-size:0.9rem;color:#6b7280;width:100%;">Thumbnail Preview</div>
                                    <div id="create_image_preview" style="width:180px;height:120px;background:#fbfdff;border:1px dashed #e6eefb;display:flex;align-items:center;justify-content:center;border-radius:6px;color:#6b7280;">No image</div>
                                    <div style="font-size:0.85rem;color:#6b7280;text-align:center;padding-top:6px;">Fill all fields and select an image to enable Create.</div>
                                </div>
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
                                    <?php
                                        // compute public image path including app base directory so admin preview works
                                        $publicImage = '';
                                        if (!empty($p['image'])) {
                                            $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
                                            $prefix = ($scriptDir && $scriptDir !== '/') ? rtrim($scriptDir, '/') . '/' : '/';
                                            $imgRel = $p['image'];
                                            if (strpos($imgRel, '/') === 0) { $imgRel = ltrim($imgRel, '/'); }
                                            $publicImage = $prefix . $imgRel;
                                        }
                                    ?>
                                    <li class="section-maintenance-group" style="display:flex;align-items:center;gap:12px;padding:10px;">
                                        <?php if ($publicImage): ?>
                                            <div style="width:84px;height:56px;flex:0 0 84px;overflow:hidden;border-radius:6px;">
                                                <img src="<?php echo htmlspecialchars($publicImage, ENT_QUOTES); ?>" style="width:84px;height:56px;object-fit:cover;border-radius:6px;display:block;">
                                            </div>
                                        <?php else: ?>
                                            <div style="width:84px;height:56px;flex:0 0 84px;display:flex;align-items:center;justify-content:center;background:#fbfdff;border-radius:6px;color:#6b7280;border:1px dashed #e6eefb;">No image</div>
                                        <?php endif; ?>
                                        <div style="flex:1;min-width:0;cursor:pointer;" onclick="openEditModal(<?php echo (int)$p['id']; ?>)" role="button" tabindex="0">
                                            <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($p['title']); ?></div>
                                            <div style="font-size:0.85rem;color:#6b7280;margin-top:4px;"><?php echo htmlspecialchars($p['slug']); ?></div>
                                        </div>
                                        <div style="display:flex;gap:8px;align-items:center;">
                                            <button class="btn" title="Edit" onclick="openEditModal(<?php echo (int)$p['id']; ?>)" style="padding:8px;border-radius:6px;background:transparent;border:1px solid transparent;"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-icon" title="Delete program" onclick="deleteProgram(<?php echo (int)$p['id']; ?>)" style="background:#ef4444;color:#fff;border-radius:6px;padding:8px 10px;"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <input type="hidden" id="program-data-<?php echo (int)$p['id']; ?>" data-title="<?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?>" data-desc="<?php echo htmlspecialchars($p['description'], ENT_QUOTES); ?>" data-image="<?php echo htmlspecialchars($publicImage, ENT_QUOTES); ?>" data-link="<?php echo htmlspecialchars($p['link'] ?? '', ENT_QUOTES); ?>">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Library CAS Manager (Current Awareness Services) -->
        <div id="section-library-cas" class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header no-divider-bottom">
                    <h2>
                        <i class="fas fa-bell"></i>
                        Library CAS Manager (Current Awareness Services)
                    </h2>
                    <p class="settings-description">Create, edit, and delete current awareness items. Thumbnails are stored under <strong>assets/images/support-services/college-library/cas/{slug}/</strong>.</p>
                </div>

                <?php
                $cas_items = [];
                try {
                    $st = $pdo->query('SELECT id, slug, title, description, image, link FROM library_cas ORDER BY created_at ASC');
                    $cas_items = $st->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    // ignore
                }
                ?>

                <div class="form-actions" style="margin-bottom:16px;">
                    <form method="POST" class="inline" style="display:flex;gap:8px;align-items:center;">
                        <?php echo CSRF::field(); ?>
                        <input type="hidden" name="action" value="set_library_cas_source">
                        <label style="margin-right:8px;">Source:</label>
                        <select name="library_cas_source" class="form-input" style="width:auto;">
                            <option value="static" <?php echo getSetting('library_cas_source','static') !== 'db' ? 'selected' : ''; ?>>Static</option>
                            <option value="db" <?php echo getSetting('library_cas_source','static') === 'db' ? 'selected' : ''; ?>>Database</option>
                        </select>
                        <button class="btn" type="submit">Save</button>
                    </form>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="flex:1; width:100%;">
                        <h3 style="margin-top:0;margin-bottom:8px;">Create CAS Item</h3>
                        <form method="POST" id="create_cas_form" class="settings-form" novalidate enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:8px;">
                            <?php echo CSRF::field(); ?>
                            <input type="hidden" name="action" value="library_cas_create">
                            <div style="display:grid;grid-template-columns:1fr 220px;gap:12px;align-items:start;">
                                <div>
                                    <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px;">
                                        <input id="create_cas_title" type="text" name="title" class="form-input" placeholder="Item title" style="flex:1;min-width:140px;padding:8px;font-size:0.95rem;" required>
                                        <input type="text" name="slug" class="form-input" placeholder="slug (optional)" style="width:140px;padding:8px;font-size:0.95rem;">
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:6px;">Description</label>
                                        <textarea id="create_cas_description" name="description" class="form-textarea" rows="3" style="padding:8px;font-size:0.95rem;min-height:80px;" placeholder="Short description (required)"></textarea>
                                    </div>
                                    <div style="margin-top:8px;display:flex;gap:8px;align-items:center;">
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:0;">Thumbnail</label>
                                        <input id="create_cas_image_file" type="file" name="image" accept="image/*" class="form-input" style="padding:6px;font-size:0.95rem;" onchange="previewCreateCASImageFile(this); checkCreateCASForm();">
                                    </div>
                                    <div style="margin-top:8px;">
                                        <label class="form-label" style="font-size:0.9rem;margin-bottom:6px;">Google Drive Link</label>
                                        <input id="create_cas_link" type="url" name="link" class="form-input" placeholder="https://drive.google.com/..." style="padding:8px;font-size:0.95rem;">
                                    </div>
                                    <div style="margin-top:12px;">
                                        <button type="submit" id="create_cas_btn" class="btn btn-primary" style="padding:8px 12px;font-size:0.95rem;" disabled>Create Item</button>
                                    </div>
                                </div>
                                <div style="border-left:1px solid #eef2ff;padding-left:12px;display:flex;flex-direction:column;align-items:center;gap:8px;">
                                    <div style="font-size:0.9rem;color:#6b7280;width:100%;">Thumbnail Preview</div>
                                    <div id="create_cas_image_preview" style="width:180px;height:120px;background:#fbfdff;border:1px dashed #e6eefb;display:flex;align-items:center;justify-content:center;border-radius:6px;color:#6b7280;">No image</div>
                                    <div style="font-size:0.85rem;color:#6b7280;text-align:center;padding-top:6px;">Fill all fields and select an image to enable Create.</div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div style="flex:1;">
                        <h3 style="margin-top:0;margin-bottom:8px;">Existing CAS Items</h3>
                        <?php if (empty($cas_items)): ?>
                            <p>No items yet.</p>
                        <?php else: ?>
                            <ul id="cas-item-list" style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                                <?php foreach ($cas_items as $p): ?>
                                    <?php
                                        $publicImage = '';
                                        if (!empty($p['image'])) {
                                            $scriptDir = dirname(dirname($_SERVER['SCRIPT_NAME']));
                                            $prefix = ($scriptDir && $scriptDir !== '/') ? rtrim($scriptDir, '/') . '/' : '/';
                                            $imgRel = $p['image'];
                                            if (strpos($imgRel, '/') === 0) { $imgRel = ltrim($imgRel, '/'); }
                                            $publicImage = $prefix . $imgRel;
                                        }
                                    ?>
                                    <li class="section-maintenance-group" style="display:flex;align-items:center;gap:12px;padding:10px;">
                                        <?php if ($publicImage): ?>
                                            <div style="width:84px;height:56px;flex:0 0 84px;overflow:hidden;border-radius:6px;">
                                                <img src="<?php echo htmlspecialchars($publicImage, ENT_QUOTES); ?>" style="width:84px;height:56px;object-fit:cover;border-radius:6px;display:block;">
                                            </div>
                                        <?php else: ?>
                                            <div style="width:84px;height:56px;flex:0 0 84px;display:flex;align-items:center;justify-content:center;background:#fbfdff;border-radius:6px;color:#6b7280;border:1px dashed #e6eefb;">No image</div>
                                        <?php endif; ?>
                                        <div style="flex:1;min-width:0;cursor:pointer;" onclick="openEditCASModal(<?php echo (int)$p['id']; ?>)" role="button" tabindex="0">
                                            <div style="font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo htmlspecialchars($p['title']); ?></div>
                                            <div style="font-size:0.85rem;color:#6b7280;margin-top:4px;"><?php echo htmlspecialchars($p['slug']); ?></div>
                                        </div>
                                        <div style="display:flex;gap:8px;align-items:center;">
                                            <button class="btn" title="Edit" onclick="openEditCASModal(<?php echo (int)$p['id']; ?>)" style="padding:8px;border-radius:6px;background:transparent;border:1px solid transparent;"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-icon" title="Delete item" onclick="deleteCASItem(<?php echo (int)$p['id']; ?>)" style="background:#ef4444;color:#fff;border-radius:6px;padding:8px 10px;"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <input type="hidden" id="cas-data-<?php echo (int)$p['id']; ?>" data-title="<?php echo htmlspecialchars($p['title'], ENT_QUOTES); ?>" data-desc="<?php echo htmlspecialchars($p['description'], ENT_QUOTES); ?>" data-image="<?php echo htmlspecialchars($publicImage, ENT_QUOTES); ?>" data-link="<?php echo htmlspecialchars($p['link'] ?? '', ENT_QUOTES); ?>">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Settings Section -->
        <div id="section-display" class="settings-section">
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
        <div id="section-post" class="settings-section">
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
        <div id="section-maintenance" class="settings-section">
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
        <div id="section-navbar" class="settings-section">
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

        /* utility to suppress the header divider when the following card already draws a divider */
        .settings-card-header.no-divider-bottom {
            border-bottom: none;
            padding-bottom: 12px;
            margin-bottom: 12px;
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

        /* If a settings card begins with a .form-actions block (e.g. program list header), don't double the divider line between cards */
        .settings-card > .form-actions:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
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

        /* Disabled state visual cue for buttons/inputs */
        button[disabled], .btn[disabled], .btn[aria-disabled="true"], input[disabled], textarea[disabled] {
            opacity: 0.55 !important;
            cursor: not-allowed !important;
            filter: grayscale(20%);
            transform: none !important;
            box-shadow: none !important;
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

        function deleteCASItem(id) {
            if (!confirm('Delete this item? This cannot be undone.')) return;
            const tokenField = document.querySelector('input[name="_token"]');
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            const action = document.createElement('input'); action.type='hidden'; action.name='action'; action.value='library_cas_delete'; form.appendChild(action);
            const idf = document.createElement('input'); idf.type='hidden'; idf.name='id'; idf.value=id; form.appendChild(idf);
            if (tokenField) { const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value = tokenField.value; form.appendChild(t); }
            document.body.appendChild(form);
            form.submit();
        }

        async function startUpload(e) {
            e && e.preventDefault && e.preventDefault();
            // PDF upload flow deprecated — thumbnail images and links are managed on the program edit form now.
            alert('PDF upload/attachment has been deprecated. Use the program edit form to set thumbnails and external links.');
            return;
        }

        // Upload a thumbnail image file for a program
        async function startThumbnailUpload(e) {
            e && e.preventDefault && e.preventDefault();
            const programIdEl = document.getElementById('thumbnail_upload_program_id');
            const filesInput = document.getElementById('thumbnail_upload_files');
            const statusEl = document.getElementById('thumbnail_upload_status');
            const progressBar = document.getElementById('thumbnail_upload_progress');

            if (!programIdEl || !programIdEl.value) { alert('Please select a program.'); return; }
            if (!filesInput || !filesInput.files || filesInput.files.length === 0) { alert('Select an image file.'); return; }

            const file = filesInput.files[0];
            const allowed = ['image/jpeg','image/png','image/webp','image/gif'];
            if (allowed.indexOf(file.type) === -1) {
                if (!file.type.startsWith('image/')) { alert('Please upload a valid image file (jpg, png, webp, gif).'); return; }
            }

            const form = new FormData();
            form.append('program_id', programIdEl.value);
            form.append('thumbnail', file);
            const tokenField = document.querySelector('input[name="_token"]');
            if (tokenField) form.append('_token', tokenField.value);

            statusEl.textContent = '';
            if (progressBar) { progressBar.style.width = '0%'; progressBar.parentElement.style.display = 'block'; }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax-library-programs-upload.php');
            xhr.upload.onprogress = function(evt) {
                if (evt.lengthComputable && progressBar) {
                    const pct = Math.round((evt.loaded / evt.total) * 100);
                    progressBar.style.width = pct + '%';
                    progressBar.textContent = pct + '%';
                }
            };
            xhr.onload = function() {
                try {
                    const data = JSON.parse(xhr.responseText);
                    if (data && data.success) {
                        statusEl.textContent = data.message || 'Thumbnail uploaded.';
                        // Update hidden program-data image attribute so edit modal and list show new thumbnail
                        const el = document.getElementById('program-data-' + programIdEl.value);
                        if (el && data.image) {
                            el.setAttribute('data-image', data.image);
                        }
                        // If edit modal is open for this program, update preview and modal image field
                        const modalId = document.getElementById('modal_program_id') && document.getElementById('modal_program_id').value;
                        if (modalId && modalId == programIdEl.value) {
                            const imgField = document.getElementById('modal_image'); if (imgField && data.image) imgField.value = data.image;
                            const preview = document.getElementById('modal_image_preview'); if (preview) preview.innerHTML = data.image ? ('<img src="'+data.image+'" style="max-width:180px;max-height:120px;border-radius:6px;">') : '<div style="color:#6b7280;">No thumbnail</div>';
                        }
                        filesInput.value = '';
                    } else {
                        statusEl.textContent = data.error || 'Upload failed.';
                    }
                } catch (e) {
                    statusEl.textContent = 'Upload error: invalid server response';
                }
                setTimeout(()=>{ if (progressBar) { progressBar.parentElement.style.display='none'; progressBar.style.width='0%'; progressBar.textContent=''; } }, 900);
            };
            xhr.onerror = function() { statusEl.textContent = 'Upload failed (network).'; };
            xhr.send(form);
        }

        // Refresh PDF list for a program via AJAX
        async function refreshPdfList(programId) {
            // Deprecated: PDF lists are no longer used. Show placeholder info if present in the DOM.
            try {
                const container = document.getElementById('pdf-list-'+programId);
                if (!container) return;
                container.innerHTML = '<div style="color:#6b7280;">PDF attachments are deprecated.</div>';
            } catch (e) { console.error(e); }
        }

        // Delete PDF by id (AJAX)
        async function deletePdf(pdfId, programId) {
            // Deprecated: PDF delete is no longer applicable in the new flow.
            alert('Removing individual PDF attachments is deprecated.');
            return;
        }

        // Open edit modal for a program. Fetch fresh metadata from server to ensure image is current.
        async function openEditModal(programId) {
            const dataEl = document.getElementById('program-data-' + programId);
            // optimistic fill from data attributes
            if (dataEl) {
                const title = dataEl.getAttribute('data-title') || '';
                const desc = dataEl.getAttribute('data-desc') || '';
                document.getElementById('modal_title').value = title;
                document.getElementById('modal_description').value = desc;
            }
            document.getElementById('modal_program_id').value = programId;
            document.getElementById('modal_title').value = document.getElementById('modal_title').value || '';
            document.getElementById('modal_description').value = document.getElementById('modal_description').value || '';
            // show modal immediately
            document.getElementById('editModal').style.display = 'flex';

            // Fetch latest program metadata from server (fallback to data attributes on failure)
            try {
                const resp = await fetch('ajax-library-programs-list.php?program_id=' + encodeURIComponent(programId));
                const data = await resp.json();
                if (data && data.success && data.program) {
                    const p = data.program;
                    document.getElementById('modal_title').value = p.title || '';
                    document.getElementById('modal_description').value = p.description || '';
                    const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = p.image || '';
                    const linkField = document.getElementById('modal_link'); if (linkField) linkField.value = p.link || '';
                    // update preview
                    const preview = document.getElementById('modal_image_preview'); if (preview) {
                        if (p.image) preview.innerHTML = '<img src="' + p.image + '" style="max-width:180px;max-height:120px;border-radius:6px;">';
                        else preview.innerHTML = '<div style="color:#6b7280;">No thumbnail</div>';
                    }
                    // also update data-element attributes so list stays in sync
                    if (dataEl) {
                        dataEl.setAttribute('data-title', p.title || '');
                        dataEl.setAttribute('data-desc', p.description || '');
                        dataEl.setAttribute('data-image', p.image || '');
                        dataEl.setAttribute('data-link', p.link || '');
                    }
                } else {
                    // fallback to attributes if fetch fails
                    if (dataEl) {
                        const image = dataEl.getAttribute('data-image') || '';
                        const link = dataEl.getAttribute('data-link') || '';
                        const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = image;
                        const linkField = document.getElementById('modal_link'); if (linkField) linkField.value = link;
                        const preview = document.getElementById('modal_image_preview'); if (preview) {
                            if (image) preview.innerHTML = '<img src="' + image + '" style="max-width:180px;max-height:120px;border-radius:6px;">';
                            else preview.innerHTML = '<div style="color:#6b7280;">No thumbnail</div>';
                        }
                    }
                }
            } catch (e) {
                if (dataEl) {
                    const image = dataEl.getAttribute('data-image') || '';
                    const link = dataEl.getAttribute('data-link') || '';
                    const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = image;
                    const linkField = document.getElementById('modal_link'); if (linkField) linkField.value = link;
                    const preview = document.getElementById('modal_image_preview'); if (preview) {
                        if (image) preview.innerHTML = '<img src="' + image + '" style="max-width:180px;max-height:120px;border-radius:6px;">';
                        else preview.innerHTML = '<div style="color:#6b7280;">No thumbnail</div>';
                    }
                }
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.getElementById('modal_upload_status') && (document.getElementById('modal_upload_status').textContent = '');
        }
        
        // Open edit modal for CAS item by reusing the same modal but switching action
        function openEditCASModal(casId) {
            const dataEl = document.getElementById('cas-data-' + casId);
            if (dataEl) {
                const title = dataEl.getAttribute('data-title') || '';
                const desc = dataEl.getAttribute('data-desc') || '';
                document.getElementById('modal_title').value = title;
                document.getElementById('modal_description').value = desc;
                const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = dataEl.getAttribute('data-image') || '';
                const linkField = document.getElementById('modal_link'); if (linkField) linkField.value = dataEl.getAttribute('data-link') || '';
            }
            document.getElementById('modal_program_id').value = casId;
            // change modal form to update CAS item instead of library program
            var actionInput = document.querySelector('#modal_edit_form input[name="action"]');
            if (actionInput) actionInput.value = 'library_cas_update';
            // change modal heading
            var heading = document.querySelector('#editModal .settings-card-header h2'); if (heading) heading.innerHTML = '<i class="fas fa-bell"></i> Edit CAS Item';
            // change delete button to call deleteCASItem
            var delBtn = document.querySelector('#editModal button[title="Delete program"]');
            if (delBtn) {
                delBtn.setAttribute('title','Delete item');
                delBtn.onclick = function(){ if(confirm('Delete this item?')){ deleteCASItem(parseInt(document.getElementById('modal_program_id').value)); } };
            }
            document.getElementById('editModal').style.display = 'flex';
            setModalFormOriginalSnapshot();
            setModalImagePreviewFromValue();
        }

        // Preview for CAS create form
        function previewCreateCASImageFile(input){
            var preview = document.getElementById('create_cas_image_preview');
            if(!preview) return;
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e){
                    preview.innerHTML = '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = 'No image';
            }
        }

        function checkCreateCASForm(){
            var title = document.getElementById('create_cas_title') ? document.getElementById('create_cas_title').value.trim() : '';
            var desc = document.getElementById('create_cas_description') ? document.getElementById('create_cas_description').value.trim() : '';
            var link = document.getElementById('create_cas_link') ? document.getElementById('create_cas_link').value.trim() : '';
            var fileInput = document.getElementById('create_cas_image_file');
            var fileSelected = fileInput && fileInput.files && fileInput.files.length > 0;
            var btn = document.getElementById('create_cas_btn');
            if(!btn) return;
            btn.disabled = !(title && desc && link && fileSelected);
        }
        

        // Refresh PDF list but target modal container
        async function refreshPdfListIntoModal(programId) {
            // Deprecated: modal PDF list not used. Show a note.
            try {
                const container = document.getElementById('modal_pdf_list');
                if (!container) return;
                container.innerHTML = '<div style="color:#6b7280;">PDF attachments are deprecated.</div>';
            } catch (e) { console.error(e); }
        }

        // AJAX-save modal form to avoid full page reload
        (function(){
            const modalForm = document.getElementById('modal_edit_form');
            const register = function(formEl){
                formEl.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formEl = this;
                    // If a new thumbnail was selected, upload it first so the update receives the stored image path
                    const tokenField = document.querySelector('input[name="_token"]');
                    const fileInput = document.getElementById('modal_image_file');
                    const statusEl = document.getElementById('modal_edit_status');
                    statusEl.textContent = 'Saving...';

                    if (fileInput && fileInput.files && fileInput.files.length > 0) {
                        try {
                            const upForm = new FormData();
                            upForm.append('program_id', document.getElementById('modal_program_id').value);
                            upForm.append('thumbnail', fileInput.files[0]);
                            if (tokenField) upForm.append('_token', tokenField.value);
                            var actionInputVal = document.querySelector('#modal_edit_form input[name="action"]') ? document.querySelector('#modal_edit_form input[name="action"]').value : 'library_update_program';
                            const uploadEndpoint = actionInputVal === 'library_cas_update' ? 'ajax-library-cas-upload.php' : 'ajax-library-programs-upload.php';
                            const upResp = await fetch(uploadEndpoint, { method: 'POST', body: upForm });
                            const upData = await upResp.json();
                            if (upData && upData.success && upData.image) {
                                // set hidden image field so update will persist it
                                const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = upData.image;
                                // update program-data attribute and preview
                                const pid = document.getElementById('modal_program_id').value;
                                const el = document.getElementById('program-data-' + pid);
                                if (el) el.setAttribute('data-image', upData.image);
                                const preview = document.getElementById('modal_image_preview'); if (preview) preview.innerHTML = '<img src="'+upData.image+'" style="max-width:180px;max-height:120px;border-radius:6px;">';
                                // clear file input
                                fileInput.value = '';
                            } else {
                                statusEl.textContent = upData.error || 'Thumbnail upload failed.';
                                return;
                            }
                        } catch (ux) {
                            statusEl.textContent = 'Thumbnail upload error';
                            return;
                        }
                    }

                    // proceed with update (includes hidden image field if upload succeeded)
                    const form = new FormData(formEl);
                    if (tokenField) form.append('_token', tokenField.value);

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
                                // if server returned program image, update attribute and preview
                                if (data.program && data.program.image) {
                                    dataEl.setAttribute('data-image', data.program.image);
                                    const preview = document.getElementById('modal_image_preview'); if (preview) preview.innerHTML = '<img src="'+data.program.image+'" style="max-width:180px;max-height:120px;border-radius:6px;">';
                                    const imgHidden = document.getElementById('modal_image'); if (imgHidden) imgHidden.value = data.program.image;
                                }
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
                            // brief highlight then close modal and show toast
                            setTimeout(()=>{ statusEl.textContent = ''; }, 800);
                            // close modal after a short delay so user sees the brief status
                            setTimeout(()=>{ closeEditModal(); showToast(data.message || 'Saved.'); }, 900);
                        } else {
                            statusEl.textContent = data.error || 'Save failed.';
                        }
                    } catch (err) {
                        statusEl.textContent = 'Save error';
                    }
                });
            };

            if (modalForm) register(modalForm);
            else document.addEventListener('DOMContentLoaded', function(){ const mf = document.getElementById('modal_edit_form'); if (mf) register(mf); });
        })();

        // --- Choose existing files UI ---
        function openChooseExisting(programId) {
            // Deprecated: choosing existing server-side PDF files is no longer supported.
            alert('Choosing existing PDFs is deprecated. Manage thumbnails and external links on the program edit form.');
            return;
        }

	</script>

<?php include '../app/includes/admin-footer.php'; ?>

<!-- Edit Modal -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);align-items:flex-start;justify-content:center;overflow:auto;z-index:2000;">
    <div class="settings-card" style="max-width:820px;width:95%;margin:40px auto;position:relative;max-height:calc(100vh - 120px);overflow:auto;padding:20px;">
        <button onclick="closeEditModal()" style="position:absolute;right:12px;top:12px;background:transparent;border:none;font-size:22px;color:var(--text-dark);">&times;</button>
        <div class="settings-card-header" style="padding-bottom:10px;margin-bottom:6px;border-bottom:1px solid #eef2ff;">
            <h2 style="font-size:1.25rem;display:flex;align-items:center;gap:8px;margin:0;"><i class="fas fa-book"></i> Edit Program</h2>
            <p class="settings-description" style="margin:6px 0 0 0;color:var(--text-light);">Edit program details, thumbnail and resource link (Google Drive).</p>
        </div>

        <div id="modal_edit_status" style="margin-bottom:8px;color:#0f172a;font-weight:600;"></div>
        <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:10px;" id="modal_edit_form">
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
            <div>
                <label class="form-label">Thumbnail (upload to replace current image)</label>
                <input id="modal_image_file" name="image_file" type="file" accept="image/*" class="form-input" style="padding:6px;font-size:0.95rem;" onchange="previewModalImageFile(this);">
                <input type="hidden" id="modal_image" name="image">
                <div id="modal_image_preview" style="margin-top:8px;color:#6b7280;"></div>
            </div>
            <div>
                <label class="form-label">Google Drive Link</label>
                <input id="modal_link" name="link" type="url" class="form-input" style="padding:8px;font-size:0.95rem;">
            </div>
                <div style="display:flex;gap:8px;align-items:center;">
                <button type="submit" id="modal_save_btn" class="btn btn-icon btn-primary" title="Save changes" disabled><i class="fas fa-save"></i></button>
                <button type="button" class="btn btn-icon" title="Delete program" style="background:#ef4444;color:#fff;" onclick="if(confirm('Delete this program and all PDFs?')){ deleteProgram(parseInt(document.getElementById('modal_program_id').value)); }"><i class="fas fa-trash"></i></button>
                <div id="modal_edit_status" style="margin-left:8px;color:#374151;font-size:0.95rem;"></div>
            </div>
        </form>

        <hr style="margin:16px 0;border-color:#eef2ff;">

        <div style="color:#6b7280;font-size:0.95rem;">Thumbnails and links are saved immediately when you save the program. Use the upload button elsewhere to upload image files.</div>
    </div>
</div>

<script>
function previewModalImageFile(input){
    var preview = document.getElementById('modal_image_preview');
    if(!preview) return;
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            preview.innerHTML = '<img src="'+e.target.result+'" style="max-width:160px;max-height:120px;border-radius:6px;object-fit:cover;">';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        var url = document.getElementById('modal_image') ? document.getElementById('modal_image').value : '';
        if(url){
            preview.innerHTML = '<img src="'+url+'" style="max-width:160px;max-height:120px;border-radius:6px;object-fit:cover;">';
        } else {
            preview.innerHTML = '<div style="color:#6b7280;">No thumbnail</div>';
        }
    }
}

// When opening the modal, ensure preview shows current image value (if any)
function setModalImagePreviewFromValue(){
    var hidden = document.getElementById('modal_image');
    var preview = document.getElementById('modal_image_preview');
    if(!preview) return;
    if(hidden && hidden.value){
        preview.innerHTML = '<img src="'+hidden.value+'" style="max-width:160px;max-height:120px;border-radius:6px;object-fit:cover;">';
    } else {
        preview.innerHTML = '<div style="color:#6b7280;">No thumbnail</div>';
    }
}

// Expose for callers that populate modal fields
window.previewModalImageFile = previewModalImageFile;
window.setModalImagePreviewFromValue = setModalImagePreviewFromValue;

// Preview for the Create form image input
function previewCreateImageFile(input){
    var preview = document.getElementById('create_image_preview');
    if(!preview) return;
    if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
            preview.innerHTML = '<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.innerHTML = 'No image';
    }
}

window.previewCreateImageFile = previewCreateImageFile;
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Quick-nav smooth scroll and highlight
    document.querySelectorAll('.quicknav-link').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            var id = this.dataset.target;
            var el = document.getElementById(id);
            if (!el) return;
            var y = el.getBoundingClientRect().top + window.pageYOffset - 16;
            window.scrollTo({ top: y, behavior: 'smooth' });
            // highlight
            var orig = el.style.boxShadow;
            el.style.boxShadow = '0 8px 24px rgba(28,77,161,0.12)';
            setTimeout(function(){ el.style.boxShadow = orig || ''; }, 700);
        });
    });

    // Search/filter settings cards
    var search = document.getElementById('settings_search');
    var clear = document.getElementById('settings_search_clear');
    function filterSettings(){
        var q = (search && search.value || '').toLowerCase().trim();
        document.querySelectorAll('.settings-section').forEach(function(section){
            if (section.id === 'settings-quicknav') { section.style.display = ''; return; }
            var card = section.querySelector('.settings-card');
            if (!card) return;
            var title = (card.querySelector('h2') && card.querySelector('h2').innerText) || '';
            var desc = (card.querySelector('.settings-description') && card.querySelector('.settings-description').innerText) || '';
            var sub = Array.from(card.querySelectorAll('h3')).map(function(h){ return h.innerText; }).join(' ');
            var hay = (title + ' ' + desc + ' ' + sub).toLowerCase();
            if (!q || hay.indexOf(q) !== -1) {
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });
    }
    if (search) search.addEventListener('input', filterSettings);
    if (clear) clear.addEventListener('click', function(){ if (search) search.value=''; filterSettings(); if (search) search.focus(); });
});
</script>

<script>
(function(){
    // Create form gating: enable Create only when title, description, link and a file are provided
    function checkCreateForm(){
        var title = document.getElementById('create_title') ? document.getElementById('create_title').value.trim() : '';
        var desc = document.getElementById('create_description') ? document.getElementById('create_description').value.trim() : '';
        var link = document.getElementById('create_link') ? document.getElementById('create_link').value.trim() : '';
        var fileInput = document.getElementById('create_image_file');
        var fileSelected = fileInput && fileInput.files && fileInput.files.length > 0;
        var btn = document.getElementById('create_program_btn');
        if(!btn) return;
        btn.disabled = !(title && desc && link && fileSelected);
    }
    // expose for inline onchange handlers
    window.checkCreateForm = checkCreateForm;

    // Modal change-detection: snapshot original values and enable Save only when any of the key fields change
    window._modalOriginalSnapshot = { title:'', desc:'', link:'', image:'' };
    window.setModalFormOriginalSnapshot = function(){
        var title = document.getElementById('modal_title') ? document.getElementById('modal_title').value : '';
        var desc = document.getElementById('modal_description') ? document.getElementById('modal_description').value : '';
        var link = document.getElementById('modal_link') ? document.getElementById('modal_link').value : '';
        var image = document.getElementById('modal_image') ? document.getElementById('modal_image').value : '';
        window._modalOriginalSnapshot = { title: title, desc: desc, link: link, image: image };
        var saveBtn = document.getElementById('modal_save_btn'); if(saveBtn) saveBtn.disabled = true;
        var fileInput = document.getElementById('modal_image_file'); if(fileInput) fileInput.value = '';
    };

    window.modalFormChanged = function(){
        var snap = window._modalOriginalSnapshot || { title:'', desc:'', link:'', image:'' };
        var title = document.getElementById('modal_title') ? document.getElementById('modal_title').value : '';
        var desc = document.getElementById('modal_description') ? document.getElementById('modal_description').value : '';
        var link = document.getElementById('modal_link') ? document.getElementById('modal_link').value : '';
        var image = document.getElementById('modal_image') ? document.getElementById('modal_image').value : '';
        var fileInput = document.getElementById('modal_image_file');
        var fileSelected = fileInput && fileInput.files && fileInput.files.length > 0;
        var changed = fileSelected || title !== snap.title || desc !== snap.desc || link !== snap.link || image !== snap.image;
        var saveBtn = document.getElementById('modal_save_btn'); if(saveBtn) saveBtn.disabled = !changed;
    };

    document.addEventListener('DOMContentLoaded', function(){
    // wire up create form listeners (Programs)
    var ids = ['create_title','create_description','create_link'];
    ids.forEach(function(id){ var el = document.getElementById(id); if(el) el.addEventListener('input', checkCreateForm); });
    var createFile = document.getElementById('create_image_file'); if(createFile) createFile.addEventListener('change', checkCreateForm);
    checkCreateForm();

    // wire up create form listeners (CAS)
    var casIds = ['create_cas_title','create_cas_description','create_cas_link'];
    casIds.forEach(function(id){ var el = document.getElementById(id); if(el) el.addEventListener('input', checkCreateCASForm); });
    var createCasFile = document.getElementById('create_cas_image_file'); if(createCasFile) createCasFile.addEventListener('change', checkCreateCASForm);
    checkCreateCASForm();

        // wire up modal listeners
        ['modal_title','modal_description','modal_link'].forEach(function(id){ var el = document.getElementById(id); if(el) el.addEventListener('input', window.modalFormChanged); });
        var modalFile = document.getElementById('modal_image_file'); if(modalFile) modalFile.addEventListener('change', window.modalFormChanged);
    });

})();
</script>

<script>
// Small toast helper for client-side feedback
function showToast(message, type) {
    type = type || 'success';
    var t = document.createElement('div');
    t.className = 'lp-toast ' + type;
    t.textContent = message || '';
    t.style.position = 'fixed';
    t.style.right = '20px';
    t.style.top = '20px';
    t.style.background = type === 'success' ? '#16a34a' : '#ef4444';
    t.style.color = '#fff';
    t.style.padding = '10px 14px';
    t.style.borderRadius = '8px';
    t.style.boxShadow = '0 6px 18px rgba(2,6,23,0.2)';
    t.style.zIndex = 99999;
    t.style.fontWeight = 600;
    document.body.appendChild(t);
    setTimeout(function(){ t.style.opacity = '0'; t.style.transition = 'opacity 300ms'; }, 2400);
    setTimeout(function(){ try{ document.body.removeChild(t); }catch(e){} }, 3000);
}
</script>


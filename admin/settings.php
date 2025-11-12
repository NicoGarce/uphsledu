<?php
/**
 * UPHSL Admin Settings
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Administrative interface for managing website settings (Super Admin only)
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

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
    if (isset($_POST['action']) && $_POST['action'] === 'save_general_settings') {
        // Require password verification for saving settings
        $password = $_POST['settings_password'] ?? '';
        
        if (empty($password)) {
            $error = 'Password verification is required to save settings';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Invalid password. Please enter your current password to save settings.';
        } else {
            // Password verified, proceed with saving settings
            $settings_map = [
            'site_name' => ['type' => 'text', 'value' => sanitizeInput($_POST['site_name'] ?? 'University of Perpetual Help System Laguna')],
            'site_tagline' => ['type' => 'text', 'value' => sanitizeInput($_POST['site_tagline'] ?? 'Character Building is Nation Building')],
            'contact_address' => ['type' => 'text', 'value' => sanitizeInput($_POST['contact_address'] ?? 'UPH Compound, National Highway, Sto. Niño, City of Biñan, Laguna')],
            'contact_phone' => ['type' => 'text', 'value' => sanitizeInput($_POST['contact_phone'] ?? '02-779-5310')],
            'contact_email_primary' => ['type' => 'text', 'value' => sanitizeInput($_POST['contact_email_primary'] ?? 'marketing@uphsl.edu.ph')],
            'contact_email_secondary' => ['type' => 'text', 'value' => sanitizeInput($_POST['contact_email_secondary'] ?? 'info@uphsl.edu.ph')],
            'facebook_url' => ['type' => 'text', 'value' => sanitizeInput($_POST['facebook_url'] ?? 'https://www.facebook.com/uphsl.info.ph')],
            'youtube_url' => ['type' => 'text', 'value' => sanitizeInput($_POST['youtube_url'] ?? 'https://www.youtube.com/@uphsltv1397')],
            'instagram_url' => ['type' => 'text', 'value' => sanitizeInput($_POST['instagram_url'] ?? 'https://www.instagram.com/uphs.laguna')],
            'tiktok_url' => ['type' => 'text', 'value' => sanitizeInput($_POST['tiktok_url'] ?? 'https://tiktok.com/@uphs.laguna')],
            'posts_per_page' => ['type' => 'integer', 'value' => (int)($_POST['posts_per_page'] ?? 12)],
            'homepage_recent_posts' => ['type' => 'integer', 'value' => (int)($_POST['homepage_recent_posts'] ?? 6)],
            'news_carousel_posts' => ['type' => 'integer', 'value' => (int)($_POST['news_carousel_posts'] ?? 5)],
            'default_post_status' => ['type' => 'text', 'value' => sanitizeInput($_POST['default_post_status'] ?? 'draft')]
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
        $maintenance_message = sanitizeInput($_POST['maintenance_message'] ?? 'We are currently performing scheduled maintenance. Please check back soon.');
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
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <!-- Maintenance Mode Section -->
        <div class="settings-section">
            <div class="settings-card">
                <div class="settings-card-header">
                    <h2>
                        <i class="fas fa-tools"></i>
                        Maintenance Mode
                    </h2>
                    <p class="settings-description">Enable maintenance mode to temporarily restrict public access to the website. Admin and authentication pages will remain accessible.</p>
                </div>
                
                <form method="POST" action="" class="settings-form">
                    <input type="hidden" name="action" value="toggle_maintenance">
                    
                    <div class="form-group">
                        <label class="switch-label">
                            <div class="switch-container">
                                <input type="checkbox" 
                                       name="maintenance_mode" 
                                       id="maintenance_mode" 
                                       value="1" 
                                       <?php echo $maintenance_mode === '1' ? 'checked' : ''; ?>
                                       onchange="updateSwitchLabel()">
                                <span class="switch-slider"></span>
                            </div>
                            <span class="switch-text">
                                <strong id="switch-status"><?php echo $maintenance_mode === '1' ? 'Enabled' : 'Disabled'; ?></strong>
                                <small id="switch-description"><?php echo $maintenance_mode === '1' ? 'Website is currently in maintenance mode' : 'Website is currently accessible to all users'; ?></small>
                            </span>
                        </label>
                    </div>
                    
                    <div class="form-group" id="password-verification-group" style="display: none;">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password Verification
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-input" 
                            placeholder="Enter your password to enable maintenance mode"
                            autocomplete="current-password">
                        <small class="form-help">
                            <i class="fas fa-info-circle"></i>
                            Password verification is required to enable maintenance mode for security purposes.
                        </small>
                    </div>
                    
                    <div class="form-group" id="maintenance-message-group" style="<?php echo $maintenance_mode === '1' ? '' : 'display: none;'; ?>">
                        <label for="maintenance_message" class="form-label">
                            <i class="fas fa-comment"></i>
                            Maintenance Message
                        </label>
                        <textarea 
                            name="maintenance_message" 
                            id="maintenance_message" 
                            class="form-textarea" 
                            rows="4" 
                            placeholder="Enter a message to display to users during maintenance mode..."><?php echo htmlspecialchars($maintenance_message); ?></textarea>
                        <small class="form-help">This message will be displayed on the maintenance page to inform users about the maintenance.</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="site_name" class="form-label">
                            <i class="fas fa-university"></i>
                            Site Name
                        </label>
                        <input type="text" name="site_name" id="site_name" class="form-input" value="<?php echo htmlspecialchars($site_name); ?>" required>
                        <small class="form-help">The official name of the university displayed in headers, footers, and page titles.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="site_tagline" class="form-label">
                            <i class="fas fa-quote-left"></i>
                            Site Tagline
                        </label>
                        <input type="text" name="site_tagline" id="site_tagline" class="form-input" value="<?php echo htmlspecialchars($site_tagline); ?>">
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
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="contact_address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Address
                        </label>
                        <textarea name="contact_address" id="contact_address" class="form-textarea" rows="3"><?php echo htmlspecialchars($contact_address); ?></textarea>
                        <small class="form-help">Physical address of the university displayed in the footer.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_phone" class="form-label">
                            <i class="fas fa-phone"></i>
                            Phone Number
                        </label>
                        <input type="text" name="contact_phone" id="contact_phone" class="form-input" value="<?php echo htmlspecialchars($contact_phone); ?>">
                        <small class="form-help">Main contact phone number displayed in the footer.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email_primary" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Primary Email
                        </label>
                        <input type="email" name="contact_email_primary" id="contact_email_primary" class="form-input" value="<?php echo htmlspecialchars($contact_email_primary); ?>">
                        <small class="form-help">Primary contact email address (e.g., marketing@uphsl.edu.ph).</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_email_secondary" class="form-label">
                            <i class="fas fa-envelope-open"></i>
                            Secondary Email
                        </label>
                        <input type="email" name="contact_email_secondary" id="contact_email_secondary" class="form-input" value="<?php echo htmlspecialchars($contact_email_secondary); ?>">
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
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="facebook_url" class="form-label">
                            <i class="fab fa-facebook"></i>
                            Facebook URL
                        </label>
                        <input type="url" name="facebook_url" id="facebook_url" class="form-input" value="<?php echo htmlspecialchars($facebook_url); ?>" placeholder="https://www.facebook.com/yourpage">
                        <small class="form-help">Facebook page URL used in footer, news carousel, and homepage.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="youtube_url" class="form-label">
                            <i class="fab fa-youtube"></i>
                            YouTube URL
                        </label>
                        <input type="url" name="youtube_url" id="youtube_url" class="form-input" value="<?php echo htmlspecialchars($youtube_url); ?>" placeholder="https://www.youtube.com/@channel">
                        <small class="form-help">YouTube channel URL.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="instagram_url" class="form-label">
                            <i class="fab fa-instagram"></i>
                            Instagram URL
                        </label>
                        <input type="url" name="instagram_url" id="instagram_url" class="form-input" value="<?php echo htmlspecialchars($instagram_url); ?>" placeholder="https://www.instagram.com/username">
                        <small class="form-help">Instagram profile URL.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="tiktok_url" class="form-label">
                            <i class="fab fa-tiktok"></i>
                            TikTok URL
                        </label>
                        <input type="url" name="tiktok_url" id="tiktok_url" class="form-input" value="<?php echo htmlspecialchars($tiktok_url); ?>" placeholder="https://tiktok.com/@username">
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
                    <input type="hidden" name="action" value="save_general_settings">
                    
                    <div class="form-group">
                        <label for="posts_per_page" class="form-label">
                            <i class="fas fa-list"></i>
                            Posts Per Page
                        </label>
                        <input type="number" name="posts_per_page" id="posts_per_page" class="form-input" value="<?php echo htmlspecialchars($posts_per_page); ?>" min="1" max="50" required>
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
    </style>

    <script>
        const currentMaintenanceMode = <?php echo $maintenance_mode === '1' ? 'true' : 'false'; ?>;
        
        function updateSwitchLabel() {
            const checkbox = document.getElementById('maintenance_mode');
            const status = document.getElementById('switch-status');
            const description = document.getElementById('switch-description');
            const messageGroup = document.getElementById('maintenance-message-group');
            const passwordGroup = document.getElementById('password-verification-group');
            const passwordInput = document.getElementById('password');
            
            if (checkbox.checked) {
                status.textContent = 'Enabled';
                description.textContent = 'Website is currently in maintenance mode';
                messageGroup.style.display = 'block';
                
                // Show password field only if enabling (was previously disabled)
                if (!currentMaintenanceMode) {
                    passwordGroup.style.display = 'block';
                    passwordInput.required = true;
                } else {
                    passwordGroup.style.display = 'none';
                    passwordInput.required = false;
                    passwordInput.value = '';
                }
            } else {
                status.textContent = 'Disabled';
                description.textContent = 'Website is currently accessible to all users';
                messageGroup.style.display = 'none';
                passwordGroup.style.display = 'none';
                passwordInput.required = false;
                passwordInput.value = '';
            }
        }
        
        // Validate form before submission
        document.querySelectorAll('.settings-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                // Check if this is the maintenance form
                const maintenanceCheckbox = form.querySelector('#maintenance_mode');
                if (maintenanceCheckbox) {
                    const passwordInput = form.querySelector('#password');
                    // If enabling maintenance mode and it was previously disabled, require password
                    if (maintenanceCheckbox.checked && !currentMaintenanceMode) {
                        if (!passwordInput.value || passwordInput.value.trim() === '') {
                            e.preventDefault();
                            alert('Password verification is required to enable maintenance mode.');
                            passwordInput.focus();
                            return false;
                        }
                    }
                } else {
                    // For all other settings forms, check if password is required
                    const passwordInput = form.querySelector('input[name="settings_password"]');
                    const passwordGroup = form.querySelector('[id$="-password-verification-group"]');
                    
                    if (passwordGroup && passwordGroup.style.display !== 'none' && passwordGroup.style.display !== '') {
                        if (!passwordInput.value || passwordInput.value.trim() === '') {
                            e.preventDefault();
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
            // Skip maintenance form
            if (form.querySelector('#maintenance_mode')) {
                return;
            }
            
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
    </script>

<?php include '../app/includes/admin-footer.php'; ?>


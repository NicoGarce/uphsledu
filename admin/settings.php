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
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_maintenance') {
        $maintenance_mode = isset($_POST['maintenance_mode']) ? '1' : '0';
        $maintenance_message = sanitizeInput($_POST['maintenance_message'] ?? 'We are currently performing scheduled maintenance. Please check back soon.');
        
        if (setSetting('maintenance_mode', $maintenance_mode, 'boolean', 'Enable/disable maintenance mode for the entire website', $_SESSION['user_id'])) {
            setSetting('maintenance_message', $maintenance_message, 'text', 'Message displayed to users during maintenance mode', $_SESSION['user_id']);
            $success = $maintenance_mode === '1' ? 'Maintenance mode enabled successfully!' : 'Maintenance mode disabled successfully!';
        } else {
            $error = 'Failed to update maintenance mode setting';
        }
    }
}

// Get current settings
$maintenance_mode = getSetting('maintenance_mode', '0');
$maintenance_message = getSetting('maintenance_message', 'We are currently performing scheduled maintenance. Please check back soon.');
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
    </style>

    <script>
        function updateSwitchLabel() {
            const checkbox = document.getElementById('maintenance_mode');
            const status = document.getElementById('switch-status');
            const description = document.getElementById('switch-description');
            const messageGroup = document.getElementById('maintenance-message-group');
            
            if (checkbox.checked) {
                status.textContent = 'Enabled';
                description.textContent = 'Website is currently in maintenance mode';
                messageGroup.style.display = 'block';
            } else {
                status.textContent = 'Disabled';
                description.textContent = 'Website is currently accessible to all users';
                messageGroup.style.display = 'none';
            }
        }
    </script>

<?php include '../app/includes/admin-footer.php'; ?>


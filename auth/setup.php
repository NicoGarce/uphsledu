<?php
/**
 * UPHSL System Setup Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Displays system setup information and user account details
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if users already exist
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$result = $stmt->fetch();
$usersExist = $result['count'] > 0;

// Get all users if they exist
$users = [];
if ($usersExist) {
    $stmt = $pdo->query("SELECT username, email, first_name, last_name, role, created_at FROM users ORDER BY created_at ASC");
    $users = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <title>UPHSL - System Setup</title>
    <link rel="icon" type="image/png" href="../assets/images/Logos/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .setup-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .setup-header h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .setup-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        .account-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid var(--primary-color);
        }
        
        .account-card.super-admin {
            border-left-color: #dc3545;
        }
        
        .account-card.admin {
            border-left-color: #fd7e14;
        }
        
        .account-card.author {
            border-left-color: #20c997;
        }
        
        .account-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .account-detail {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .account-detail i {
            color: var(--primary-color);
            width: 20px;
        }
        
        .credentials {
            background: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .credentials h4 {
            margin-bottom: 10px;
            color: var(--text-dark);
        }
        
        .credential-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .credential-item:last-child {
            border-bottom: none;
        }
        
        .credential-label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .credential-value {
            font-family: 'Courier New', monospace;
            background: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        
        .role-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-super-admin {
            background: #dc3545;
            color: white;
        }
        
        .role-admin {
            background: #fd7e14;
            color: white;
        }
        
        .role-author {
            background: #20c997;
            color: white;
        }
        
        .setup-actions {
            text-align: center;
            margin-top: 40px;
        }
        
        .btn-setup {
            display: inline-block;
            padding: 15px 30px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 10px;
        }
        
        .btn-setup:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .warning-box h4 {
            color: #856404;
            margin-bottom: 10px;
        }
        
        .warning-box p {
            color: #856404;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-cog"></i> System Setup Complete</h1>
            <p>Your UPHSL website has been initialized with default user accounts</p>
        </div>
        
        <?php if ($usersExist): ?>
            <div class="warning-box">
                <h4><i class="fas fa-exclamation-triangle"></i> Important Security Notice</h4>
                <p>Please change the default passwords immediately after first login. These credentials are visible to anyone with access to this setup page.</p>
            </div>
            
            <h2><i class="fas fa-users"></i> Created User Accounts</h2>
            
            <?php foreach ($users as $user): ?>
                <div class="account-card <?php echo str_replace('_', '-', $user['role']); ?>">
                    <div class="account-info">
                        <div class="account-detail">
                            <i class="fas fa-user"></i>
                            <span><strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong></span>
                        </div>
                        <div class="account-detail">
                            <i class="fas fa-tag"></i>
                            <span class="role-badge role-<?php echo str_replace('_', '-', $user['role']); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                            </span>
                        </div>
                        <div class="account-detail">
                            <i class="fas fa-envelope"></i>
                            <span><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="account-detail">
                            <i class="fas fa-calendar"></i>
                            <span><?php echo formatDate($user['created_at']); ?></span>
                        </div>
                    </div>
                    
                    <div class="credentials">
                        <h4><i class="fas fa-key"></i> Login Credentials</h4>
                        <div class="credential-item">
                            <span class="credential-label">Username:</span>
                            <span class="credential-value"><?php echo htmlspecialchars($user['username']); ?></span>
                        </div>
                        <div class="credential-item">
                            <span class="credential-label">Email:</span>
                            <span class="credential-value"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                        <div class="credential-item">
                            <span class="credential-label">Password:</span>
                            <span class="credential-value">
                                <?php 
                                switch($user['role']) {
                                    case 'super_admin':
                                        echo 'SuperAdmin@123';
                                        break;
                                    case 'admin':
                                        echo 'MarketingAdmin@123';
                                        break;
                                    case 'author':
                                        echo 'Marketing@123';
                                        break;
                                    default:
                                        echo 'N/A';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="setup-actions">
                <a href="login.php" class="btn-setup">
                    <i class="fas fa-sign-in-alt"></i> Go to Login
                </a>
                <a href="index.php" class="btn-setup btn-secondary">
                    <i class="fas fa-home"></i> View Website
                </a>
            </div>
            
        <?php else: ?>
            <div class="account-card">
                <h3><i class="fas fa-info-circle"></i> No Users Found</h3>
                <p>The system has not been initialized yet. Please refresh this page to create the default user accounts.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

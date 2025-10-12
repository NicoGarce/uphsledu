# UPHSL Education Website with Posting System - Project Structure

## Overview
This is a comprehensive educational website for the University of Perpetual Help System Laguna (UPHSL) featuring a complete content management system with posting capabilities. The website includes both public-facing pages and an administrative panel for content management.

## Prerequisites

Before installing the UPHSL Education Website, ensure you have the following installed on your system:

### **Required Software**
- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: Version 7.4 or higher (8.0+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Operating System**: Windows, Linux, or macOS

### **PHP Extensions**
- PDO MySQL
- GD (for image processing)
- OpenSSL
- Mbstring
- Fileinfo
- JSON

### **Recommended Development Environment**
- **XAMPP** (Windows/Mac/Linux) - Includes Apache, PHP, and MySQL
- **WAMP** (Windows)
- **MAMP** (Mac)
- **LAMP** (Linux)

## Installation Instructions

### **Step 1: Download and Setup**
1. Clone or download the project files to your web server directory
2. For XAMPP: Place files in `C:\xampp\htdocs\uphsledu\` (Windows) or `/Applications/XAMPP/htdocs/uphsledu/` (Mac)
3. Ensure your web server is running (Apache and MySQL)

### **Step 2: Database Configuration**
1. Open phpMyAdmin or your preferred MySQL client
2. Create a new database named `uphsledu`
3. Import the database schema (if provided) or let the system auto-create tables

### **Step 3: Configure Database Connection**
1. Open `app/config/database.php`
2. Update the database credentials if needed:
   ```php
   $host = 'localhost';
   $dbname = 'uphsledu';
   $username = 'root';        // Default XAMPP username
   $password = '';            // Default XAMPP password (empty)
   ```

### **Step 4: Set Permissions**
1. Ensure the `uploads/` directory is writable:
   - **Windows**: Right-click → Properties → Security → Full Control
   - **Linux/Mac**: `chmod 755 uploads/`

### **Step 5: Initialize the System**
1. Navigate to `http://localhost/uphsledu/auth/setup.php`
2. Follow the setup instructions
3. Note the default login credentials provided

### **Step 6: Default Login Credentials**
After setup, you can log in with these default accounts:

| Role | Username | Password | Access Level |
|------|----------|----------|--------------|
| Super Admin | superadmin | 123 | Full access to all features |
| Admin | web-admin | 123 | Dashboard and post management |
| Marketing Admin | marketing-admin | 123 | Dashboard and post management |
| Author | author | 123 | Post creation and management only |

### **Step 7: Verify Installation**
1. Visit `http://localhost/uphsledu/` to see the homepage
2. Test the admin panel at `http://localhost/uphsledu/auth/login.php`
3. Create a test post to verify the posting system works

## Key Features
- **Content Management System**: Full CRUD operations for posts and pages
- **User Authentication**: Role-based access control (Super Admin, Admin, Author)
- **Posting System**: Create, edit, delete, and publish posts with images
- **Responsive Design**: Mobile-first approach with modern UI/UX
- **Clean URLs**: SEO-friendly URLs without .php extensions
- **Multi-role Dashboard**: Different interfaces for different user roles

## New Organized Structure

```
uphsledu/
├── index.php                    # Main homepage with news slider
├── about.php                    # About page (moved from about/about.php)
├── post.php                     # Individual post view page
├── posts.php                    # All posts listing page
├── search.php                   # Search functionality
├── campuses.php                 # Campus information
├── programs.php                 # Programs overview
├── sdg-initiatives.php          # SDG Initiatives page
├── ols_instructions.php         # Online services instructions
├── privacy-policy.php           # Privacy policy page
├── terms-of-service.php         # Terms of service page
├── accessibility.php            # Accessibility page
├── 404.php                      # Error page
├── PROJECT_STRUCTURE.md         # This documentation
├── .htaccess                    # URL rewriting rules
│
├── auth/                        # Authentication system
│   ├── login.php                # User login with role-based redirect
│   ├── logout.php               # User logout with session cleanup
│   ├── setup.php                # Initial setup with default credentials
│   └── init.php                 # Database initialization
│
├── admin/                       # Administrative panel
│   ├── dashboard.php            # Main admin dashboard (Super Admin/Admin)
│   ├── author-dashboard.php     # Author-specific dashboard
│   ├── create-post.php          # Post creation and editing
│   ├── posts.php                # Post management (all roles)
│   └── accounts.php             # User account management (Super Admin/Admin)
│
├── app/                        # Application core
│   ├── config/
│   │   └── database.php        # Database configuration and schema
│   ├── includes/
│   │   ├── header.php          # Public site header with navigation
│   │   ├── footer.php          # Public site footer
│   │   ├── admin-header.php    # Admin panel header with sidebar
│   │   ├── admin-footer.php    # Admin panel footer
│   │   ├── functions.php       # Core utility functions
│   │   ├── coming-soon.php     # Coming soon template
│   │   └── general-coming-soon.php
│   └── functions/              # Additional function files (future)
│
├── assets/                     # Static assets
│   ├── css/                    # Stylesheets
│   │   ├── style.css           # Main public site styles
│   │   ├── admin.css           # Admin panel styles
│   │   ├── auth.css            # Authentication page styles
│   │   ├── dashboard.css       # Dashboard and sidebar styles
│   │   ├── editor.css          # Post editor styles
│   │   ├── post.css            # Individual post page styles
│   │   └── posts.css           # Posts listing styles
│   ├── js/                     # JavaScript files
│   │   ├── script.js           # Main site JavaScript
│   │   └── post.js             # Post-specific JavaScript
│   ├── images/                 # Images (reorganized)
│   │   ├── logos/              # Logo files
│   │   ├── banners/            # Banner images
│   │   ├── programs/           # Program-related images
│   │   ├── news/               # News images
│   │   ├── support/            # Support service images
│   │   ├── ui/                 # UI elements
│   │   ├── campuses/           # Campus images
│   │   ├── environment/        # Environment images
│   │   ├── GTI/                # GTI-related images
│   │   ├── library/            # Library images
│   │   ├── map/                # Map images
│   │   ├── moodle/             # Moodle images
│   │   ├── news-updates/       # News update images
│   │   ├── olservices/         # Online services images
│   │   ├── research/           # Research images
│   │   ├── sps/                # SPS images
│   │   └── support-services/   # Support services content
│   └── documents/              # Document files
│       ├── pdfs/               # PDF documents
│       └── handbooks/          # Handbook files
│
├── uploads/                    # User uploads and post images
│   └── [uploaded files]        # Post featured images and attachments
│
├── programs/                   # Program pages
│   ├── index.php
│   ├── senior-high-school.php
│   ├── junior-high-school.php
│   ├── grade-school.php
│   ├── aviation.php
│   ├── arts-sciences.php
│   ├── business-accountancy.php
│   ├── computer-studies.php
│   ├── criminology.php
│   ├── education.php
│   ├── engineering-architecture.php
│   ├── hospitality-management.php
│   ├── maritime.php
│   ├── law.php
│   └── graduate-school.php
│
├── support-services/           # Support service pages
│   ├── index.php
│   ├── careers.php
│   ├── clinic.php
│   ├── cod.php
│   ├── iea.php
│   ├── library.php
│   ├── quality-assurance.php
│   ├── research.php
│   └── sps/
│
├── about/                      # About section pages
│   ├── index.php               # Redirects to about.php
│   ├── contact.php             # Contact information
│   ├── environmental-policy.php # Environmental policy
│   ├── university-policy.php   # University policies
│   └── map.php                 # Campus map
│
├── calendar/                   # Academic calendar pages
│   ├── college-academic-calendar.php
│   └── bed-shs-academic-calendar.php
```

## Posting System Features

### **Content Management**
- **Post Creation**: Rich text editor with image upload capabilities
- **Post Editing**: Full CRUD operations for all user roles
- **Image Management**: Upload, display, and delete post images
- **Publishing Control**: Draft/Published status with date scheduling
- **SEO-Friendly**: Clean URLs and meta descriptions

### **User Roles & Permissions**
- **Super Admin**: Full access to all features (Dashboard, Posts, Accounts)
- **Admin**: Content management access (Dashboard, Posts)
- **Author**: Post creation and management only

### **Database Schema**
- **Users Table**: User authentication and role management
- **Posts Table**: Content storage with published_at timestamps
- **Images Table**: Post image associations and metadata

### **Frontend Features**
- **News Slider**: Homepage carousel with recent posts
- **Post Listing**: Paginated posts with search functionality
- **Individual Posts**: Full post view with image galleries
- **Responsive Design**: Mobile-first approach across all devices

## Key Improvements

### 1. **Separation of Concerns**
- **Public files**: All user-facing pages in `/public/`
- **Authentication**: All auth-related files in `/auth/`
- **Admin panel**: Administrative functions in `/admin/`
- **Application logic**: Core functionality in `/app/`

### 2. **Better Asset Organization**
- **Logos**: All logo files in `/assets/images/logos/`
- **Banners**: Banner images in `/assets/images/banners/`
- **Programs**: Program-related images in `/assets/images/programs/`
- **Documents**: PDFs and handbooks in `/assets/documents/`

### 3. **Consistent Path Structure**
- All includes use relative paths from their new locations
- Asset references updated to new organized structure
- Admin files properly reference public and auth directories

### 4. **Maintained Functionality**
- All file references updated to maintain functionality
- Database connections preserved
- User authentication flow intact
- Admin panel functionality preserved

## Benefits

1. **Complete CMS**: Full content management system with posting capabilities
2. **Role-Based Access**: Secure multi-user system with different permission levels
3. **Modern UI/UX**: Responsive design with intuitive admin interface
4. **SEO Optimized**: Clean URLs and proper meta tags for better search visibility
5. **Image Management**: Built-in image upload and management system
6. **Cleaner Structure**: Logical separation of different types of files
7. **Better Maintainability**: Easier to find and modify specific functionality
8. **Improved Security**: Clear separation between public and private areas
9. **Scalability**: Structure supports future growth and additions
10. **Developer Experience**: More intuitive file organization

## Technical Implementation

### **URL Rewriting**
- Clean URLs without .php extensions using Apache .htaccess
- Automatic redirects from .php to clean URLs
- SEO-friendly URL structure

### **Database Integration**
- MySQL database with PDO connections
- User authentication and session management
- Post storage with image associations
- Role-based access control

### **File Structure**
- Root-level pages for main functionality
- Organized asset structure with logical categorization
- Modular include system for headers and footers
- Separate admin and public interfaces

### **Security Features**
- Password hashing and verification
- Session management and cleanup
- Role-based access control
- Input validation and sanitization

## Troubleshooting

### **Common Issues and Solutions**

#### **1. Database Connection Error**
- **Problem**: "Connection failed" or database errors
- **Solution**: 
  - Verify MySQL is running
  - Check database credentials in `app/config/database.php`
  - Ensure database `uphsledu` exists

#### **2. Image Upload Not Working**
- **Problem**: Images not uploading or displaying
- **Solution**:
  - Check `uploads/` directory permissions (must be writable)
  - Verify PHP GD extension is enabled
  - Check file size limits in `php.ini`

#### **3. Clean URLs Not Working**
- **Problem**: URLs showing `.php` extensions or 404 errors
- **Solution**:
  - Ensure Apache mod_rewrite is enabled
  - Check `.htaccess` file is present and readable
  - Verify Apache configuration allows .htaccess overrides

#### **4. Login Issues**
- **Problem**: Cannot log in or session errors
- **Solution**:
  - Clear browser cookies and cache
  - Check PHP session configuration
  - Verify user exists in database

#### **5. Permission Denied Errors**
- **Problem**: File permission errors
- **Solution**:
  - Set proper permissions on `uploads/` directory
  - Ensure web server has read/write access
  - Check file ownership

### **Development Tips**

#### **Enabling Error Reporting (Development Only)**
Add to the top of PHP files for debugging:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

#### **Checking PHP Extensions**
Create a `phpinfo.php` file to verify all required extensions:
```php
<?php phpinfo(); ?>
```

#### **Database Debugging**
Enable PDO error mode in `app/config/database.php`:
```php
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

## Migration Notes

- All path references have been updated to work with current structure
- Asset paths updated to reflect new organization
- Database configuration centralized in `app/config/database.php`
- All include/require statements updated to new paths
- Clean URL implementation with .htaccess rewrite rules


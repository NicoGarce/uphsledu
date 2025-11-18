# UPHSL Education Website with Advanced Features - Project Structure

## Overview
This is a comprehensive educational website for the University of Perpetual Help System Laguna (UPHSL) featuring a complete content management system with posting capabilities, advanced search functionality, responsive design, and modern UI/UX. The website includes both public-facing pages and an administrative panel for content management.

## Recent Updates & Features (2025)

### **🎨 UI/UX Improvements**
- ✅ **Modern Design System**: Updated with consistent color schemes and typography
- ✅ **Responsive Navigation**: Mobile-first navbar with dropdown menus
- ✅ **Advanced Search**: Real-time AJAX search with dropdown results
- ✅ **Shimmer Loading**: Smooth loading animations for images
- ✅ **Glass Effects**: Modern backdrop-filter effects throughout the site

### **🔍 Search & Navigation**
- ✅ **Global AJAX Search**: Real-time search across all pages and posts
- ✅ **Smart Navigation**: Active menu detection for subdirectory pages
- ✅ **Mobile Search**: Optimized mobile search experience
- ✅ **Filter System**: Advanced post filtering with date ranges and categories

### **📱 Mobile Optimization**
- ✅ **Responsive Design**: Fully optimized for all device sizes
- ✅ **Touch-Friendly**: Mobile-optimized interactions
- ✅ **Performance**: Optimized loading and preloading strategies
- ✅ **Accessibility**: Improved accessibility features

### **🎯 Content Management**
- ✅ **News Stand Layout**: Modern posts page with grid layout
- ✅ **Program Pages**: Comprehensive program listings with logos
- ✅ **SDG Initiatives**: Interactive SDG goals with modals
- ✅ **Support Services**: Organized service pages with navigation

### **🔒 Security Features (Laravel-like)**
- ✅ **CSRF Protection**: Cross-Site Request Forgery protection for all forms
- ✅ **XSS Prevention**: Comprehensive XSS protection and output escaping
- ✅ **SQL Injection Prevention**: Prepared statements and query sanitization
- ✅ **Rate Limiting**: Protection against brute force attacks
- ✅ **Session Security**: Secure session management with regeneration
- ✅ **Security Headers**: CSP, HSTS, X-Frame-Options, and more
- ✅ **Input Validation**: Comprehensive input validation and sanitization
- ✅ **Password Security**: Strong password requirements and hashing

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
- OpenSSL (for encryption)
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
4. Test the search functionality and navigation

## Production Deployment (cPanel)

### **Pre-Deployment Checklist**
✅ **Automatic Path Detection**: The system automatically detects development vs production environment
✅ **No Manual Path Changes**: All paths are automatically configured
✅ **Database Ready**: Uses the same database configuration
✅ **Clean URLs**: .htaccess rules work in production
✅ **File Permissions**: Uploads directory will be automatically writable
✅ **Search Functionality**: AJAX search works in production
✅ **Mobile Optimization**: Responsive design works across all devices

### **Deployment Steps**

#### **Step 1: Upload Files**
1. Upload all files to your cPanel `public_html` directory
2. Ensure the file structure is maintained exactly as in development

#### **Step 2: Database Setup**
1. Create a MySQL database in cPanel
2. Update `app/config/database.php` with your production database credentials:
   ```php
   $host = 'localhost';  // Usually localhost in cPanel
   $dbname = 'your_database_name';
   $username = 'your_database_username';
   $password = 'your_database_password';
   ```

#### **Step 3: Set Permissions**
1. Set `uploads/` directory permissions to 755 or 777
2. Ensure all PHP files have 644 permissions

#### **Step 4: Initialize System**
1. Visit `https://yourdomain.com/auth/setup.php`
2. Follow the setup instructions
3. The system will automatically detect it's in production mode

#### **Step 5: Test Everything**
1. Visit your domain to see the homepage
2. Test admin login at `https://yourdomain.com/auth/login.php`
3. Create a test post to verify functionality
4. Test search functionality and mobile responsiveness

### **Production Features**
- **Automatic Path Detection**: No manual configuration needed
- **Clean URLs**: Works automatically with cPanel
- **SSL Ready**: Works with HTTPS
- **Mobile Responsive**: Optimized for all devices
- **SEO Friendly**: Clean URLs and proper meta tags
- **Advanced Search**: Real-time search functionality
- **Performance Optimized**: Preloading and caching strategies

## Key Features

### **🎯 Core Functionality**
- **Content Management System**: Full CRUD operations for posts and pages
- **User Authentication**: Role-based access control (Super Admin, Admin, Author)
- **Posting System**: Create, edit, delete, and publish posts with images
- **Responsive Design**: Mobile-first approach with modern UI/UX
- **Clean URLs**: SEO-friendly URLs without .php extensions
- **Multi-role Dashboard**: Different interfaces for different user roles

### **🔍 Advanced Search & Navigation**
- **Global AJAX Search**: Real-time search across all pages and posts
- **Smart Navigation**: Active menu detection for subdirectory pages
- **Filter System**: Advanced post filtering with date ranges and categories
- **Mobile Search**: Optimized mobile search experience
- **Search Results**: Dropdown results with proper categorization

### **📱 Mobile & Performance**
- **Responsive Design**: Fully optimized for all device sizes
- **Touch-Friendly**: Mobile-optimized interactions
- **Performance**: Optimized loading and preloading strategies
- **Shimmer Loading**: Smooth loading animations for images
- **Accessibility**: Improved accessibility features

### **🎨 Modern UI/UX**
- **Glass Effects**: Modern backdrop-filter effects
- **Consistent Design**: Unified color schemes and typography
- **Interactive Elements**: Hover effects and smooth transitions
- **News Stand Layout**: Modern posts page with grid layout
- **Program Pages**: Comprehensive program listings with logos

## New Organized Structure

```
uphsledu/
├── index.php                    # Main homepage with news slider
├── about.php                    # About page (moved from about/about.php)
├── post.php                     # Individual post view page
├── posts.php                    # All posts listing page (News Stand Layout)
├── search.php                   # Search functionality
├── campuses.php                 # Campus information
├── sdg-initiatives.php          # SDG Initiatives page with interactive modals
├── ols_instructions.php         # Online services instructions
├── privacy-policy.php           # Privacy policy page
├── terms-of-service.php         # Terms of service page
├── accessibility.php            # Accessibility page
├── 404.php                      # Error page
├── ajax-search.php              # AJAX search endpoint
├── ajax-navbar-search.php       # Navbar search endpoint
├── deploy-to-production.php     # Production deployment script
├── robots.txt                   # SEO robots file
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
│   ├── create-sdg-post.php      # SDG post creation
│   ├── posts.php                # Post management (all roles)
│   ├── accounts.php             # User account management (Super Admin/Admin)
│   └── sdg-initiatives.php      # SDG initiatives management
│
├── app/                        # Application core
│   ├── config/
│   │   ├── database.php         # Database configuration and schema
│   │   └── paths.php            # Path configuration and base path detection
│   ├── includes/
│   │   ├── header.php           # Public site header with navigation and AJAX search
│   │   ├── footer.php           # Public site footer
│   │   ├── admin-header.php     # Admin panel header with sidebar
│   │   ├── admin-footer.php     # Admin panel footer
│   │   ├── functions.php        # Core utility functions
│   │   ├── coming-soon.php      # Coming soon template
│   │   └── general-coming-soon.php
│   └── functions/               # Additional function files (future)
│
├── assets/                     # Static assets
│   ├── css/                    # Stylesheets
│   │   ├── style.css           # Main public site styles
│   │   ├── admin.css           # Admin panel styles
│   │   ├── auth.css            # Authentication page styles
│   │   ├── dashboard.css        # Dashboard and sidebar styles
│   │   ├── editor.css          # Post editor styles
│   │   ├── post.css            # Individual post page styles
│   │   └── posts.css           # Posts listing styles with shimmer loading
│   ├── js/                     # JavaScript files
│   │   ├── script.js           # Main site JavaScript
│   │   └── post.js             # Post-specific JavaScript
│   ├── images/                 # Images (reorganized)
│   │   ├── Logos/              # Logo files
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
│   ├── documents/              # Document files
│   │   ├── pdfs/               # PDF documents
│   │   └── handbooks/          # Handbook files
│   └── video/                  # Video files
│       └── AD2025.mp4          # Promotional video
│
├── uploads/                    # User uploads and post images
│   └── [uploaded files]        # Post featured images and attachments
│
├── programs/                   # Program pages
│   ├── index.php               # Programs overview with modern design
│   ├── img/                    # Program logos and images
│   │   └── logo/               # Program-specific logos
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
│   ├── index.php               # Support services overview
│   ├── sps-assets/             # SPS-specific assets
│   ├── careers.php
│   ├── clinic.php
│   ├── cod.php
│   ├── iea.php
│   ├── library.php
│   ├── quality-assurance.php
│   ├── research.php
│   └── sps.php
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

## Advanced Features

### **🔍 Search & Navigation System**
- **Global AJAX Search**: Real-time search across all pages and posts
- **Smart Navigation**: Active menu detection for subdirectory pages
- **Mobile Search**: Optimized mobile search experience
- **Filter System**: Advanced post filtering with date ranges and categories
- **Search Results**: Dropdown results with proper categorization

### **📱 Mobile Optimization**
- **Responsive Design**: Fully optimized for all device sizes
- **Touch-Friendly**: Mobile-optimized interactions
- **Performance**: Optimized loading and preloading strategies
- **Shimmer Loading**: Smooth loading animations for images
- **Accessibility**: Improved accessibility features

### **🎨 Modern UI/UX**
- **Glass Effects**: Modern backdrop-filter effects
- **Consistent Design**: Unified color schemes and typography
- **Interactive Elements**: Hover effects and smooth transitions
- **News Stand Layout**: Modern posts page with grid layout
- **Program Pages**: Comprehensive program listings with logos

### **🎯 Content Management**
- **Post Creation**: Rich text editor with image upload capabilities
- **Post Editing**: Full CRUD operations for all user roles
- **Image Management**: Upload, display, and delete post images
- **Publishing Control**: Draft/Published status with date scheduling
- **SEO-Friendly**: Clean URLs and meta descriptions
- **Shimmer Loading**: Smooth loading animations for images

### **👥 User Roles & Permissions**
- **Super Admin**: Full access to all features (Dashboard, Posts, Accounts)
- **Admin**: Content management access (Dashboard, Posts)
- **Author**: Post creation and management only

### **🗄️ Database Schema**
- **Users Table**: User authentication and role management
- **Posts Table**: Content storage with published_at timestamps
- **Images Table**: Post image associations and metadata
- **SDG Initiatives**: SDG-specific content management

### **🌐 Frontend Features**
- **News Slider**: Homepage carousel with recent posts
- **Post Listing**: Paginated posts with search functionality
- **Individual Posts**: Full post view with image galleries
- **Responsive Design**: Mobile-first approach across all devices
- **AJAX Search**: Real-time search with dropdown results
- **Filter System**: Advanced post filtering capabilities

## Key Improvements (2025)

### 1. **Advanced Search System**
- **Global AJAX Search**: Real-time search across all pages and posts
- **Smart Navigation**: Active menu detection for subdirectory pages
- **Mobile Search**: Optimized mobile search experience
- **Filter System**: Advanced post filtering with date ranges and categories

### 2. **Modern UI/UX Design**
- **Glass Effects**: Modern backdrop-filter effects throughout the site
- **Consistent Design**: Unified color schemes and typography
- **Interactive Elements**: Hover effects and smooth transitions
- **News Stand Layout**: Modern posts page with grid layout
- **Program Pages**: Comprehensive program listings with logos

### 3. **Mobile Optimization**
- **Responsive Design**: Fully optimized for all device sizes
- **Touch-Friendly**: Mobile-optimized interactions
- **Performance**: Optimized loading and preloading strategies
- **Shimmer Loading**: Smooth loading animations for images
- **Accessibility**: Improved accessibility features

### 4. **Performance Enhancements**
- **Preloading Strategy**: Smart preloading of critical resources
- **Image Optimization**: Shimmer loading and lazy loading
- **AJAX Implementation**: Real-time search and filtering
- **Caching**: Optimized asset loading and caching

### 5. **Content Management**
- **SDG Initiatives**: Interactive SDG goals with modals
- **Program Pages**: Comprehensive program listings with logos
- **Support Services**: Organized service pages with navigation
- **News Stand Layout**: Modern posts page with grid layout

## Benefits

1. **Complete CMS**: Full content management system with posting capabilities
2. **Role-Based Access**: Secure multi-user system with different permission levels
3. **Modern UI/UX**: Responsive design with intuitive admin interface
4. **SEO Optimized**: Clean URLs and proper meta tags for better search visibility
5. **Image Management**: Built-in image upload and management system
6. **Advanced Search**: Real-time search functionality across all content
7. **Mobile Optimized**: Fully responsive design for all devices
8. **Performance**: Optimized loading and caching strategies
9. **Accessibility**: Improved accessibility features
10. **Scalability**: Structure supports future growth and additions

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

### **AJAX Implementation**
- Real-time search functionality
- Dynamic content loading
- Mobile-optimized search experience
- Filter system with date ranges and categories

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

#### **4. Search Not Working**
- **Problem**: AJAX search not functioning
- **Solution**:
  - Check JavaScript console for errors
  - Verify AJAX endpoints are accessible
  - Ensure proper file permissions

#### **5. Mobile Issues**
- **Problem**: Mobile search or navigation not working
- **Solution**:
  - Check mobile-specific CSS
  - Verify touch event handlers
  - Test on actual mobile devices

#### **6. Login Issues**
- **Problem**: Cannot log in or session errors
- **Solution**:
  - Clear browser cookies and cache
  - Check PHP session configuration
  - Verify user exists in database

#### **7. Permission Denied Errors**
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

#### **AJAX Debugging**
Check browser console for JavaScript errors and network tab for AJAX requests.

## Security Features

The website includes comprehensive Laravel-like security features to protect against common web vulnerabilities.

### **Implemented Security Features**

1. **CSRF Protection**
   - All forms are protected with CSRF tokens
   - Automatic token generation and verification
   - Token expiration and regeneration

2. **XSS Prevention**
   - Output escaping for all user-generated content
   - HTML sanitization functions
   - JavaScript and attribute escaping

3. **SQL Injection Prevention**
   - Prepared statements for all database queries
   - Query parameter binding
   - LIKE query escaping

4. **Rate Limiting**
   - Login attempt limiting (5 attempts per 15 minutes)
   - Configurable rate limits per endpoint
   - Automatic retry-after calculation

5. **Session Security**
   - Secure session cookies (HttpOnly, SameSite)
   - Session ID regeneration
   - Session timeout (30 minutes)
   - Automatic session cleanup

6. **Security Headers**
   - Content Security Policy (CSP)
   - X-Frame-Options
   - X-XSS-Protection
   - X-Content-Type-Options
   - HSTS (when using HTTPS)
   - Referrer Policy

7. **Input Validation**
   - Email validation
   - Password strength validation
   - String length validation
   - Numeric validation
   - Comprehensive sanitization

8. **Password Security**
   - Strong password requirements
   - Secure password hashing (password_hash)
   - Password verification

### **Using Security Features**

See `SECURITY.md` for detailed documentation on using all security features.

**Quick Example:**
```php
// In forms
<form method="POST">
    <?php echo CSRF::field(); ?>
    <!-- form fields -->
</form>

// In form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!CSRF::verify()) {
        die('CSRF token mismatch');
    }
    // Process form...
}

// Output escaping
echo XSS::clean($userInput);

// Rate limiting
$key = 'login_' . $_SERVER['REMOTE_ADDR'];
if (!RateLimiter::check($key, 5, 900)) {
    die('Too many attempts');
}
```

### **Security Configuration**

Security settings can be configured in `app/config/security.php`:
- CSRF token lifetime
- Rate limiting thresholds
- Session timeout
- Password requirements
- Security headers
- Content Security Policy

### **Security Checklist**

- ✅ CSRF protection on all forms
- ✅ XSS protection on all output
- ✅ SQL injection prevention
- ✅ Rate limiting on sensitive endpoints
- ✅ Secure session management
- ✅ Security headers configured
- ✅ Input validation and sanitization
- ✅ Password hashing

## Migration Notes

- All path references have been updated to work with current structure
- Asset paths updated to reflect new organization
- Database configuration centralized in `app/config/database.php`
- All include/require statements updated to new paths
- Clean URL implementation with .htaccess rewrite rules
- AJAX search functionality implemented
- Mobile optimization completed
- Performance enhancements applied
- **Security features added (2025)**

## Support

For technical support or questions about the UPHSL Education Website, please refer to the troubleshooting section above or contact the development team.

---

**Last Updated**: January 2025
**Version**: 2.0
**Author**: Nico Roell D. Garce - UPHSL Web Administrator 2025
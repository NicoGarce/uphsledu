# UPHSL Education Website - Project Structure

## New Organized Structure

```
uphsledu/
├── index.php                    # Root redirect to public/index.php
├── 404.php                      # Error page
├── PROJECT_STRUCTURE.md         # This documentation
│
├── public/                      # Public-facing files
│   ├── index.php               # Main homepage
│   ├── post.php                # Individual post view
│   ├── posts.php               # Posts listing
│   ├── search.php              # Search functionality
│   ├── campuses.php            # Campus information
│   ├── programs.php            # Programs overview
│   ├── ols_instructions.php    # Online services instructions
│   ├── create-post.php         # Post creation (auth required)
│   └── dashboard.php           # User dashboard (auth required)
│
├── auth/                       # Authentication files
│   ├── login.php               # User login
│   ├── logout.php              # User logout
│   ├── setup.php               # Initial setup
│   └── init.php                # Database initialization
│
├── admin/                      # Admin panel
│   ├── posts.php               # Post management
│   ├── users.php               # User management
│   └── accounts.php            # Account management
│
├── app/                        # Application logic
│   ├── config/
│   │   └── database.php        # Database configuration
│   ├── includes/
│   │   ├── header.php          # Site header
│   │   ├── footer.php          # Site footer
│   │   ├── functions.php       # Utility functions
│   │   ├── coming-soon.php     # Coming soon template
│   │   └── general-coming-soon.php
│   └── functions/              # Additional function files (future)
│
├── assets/                     # Static assets
│   ├── css/                    # Stylesheets
│   │   ├── style.css
│   │   ├── admin.css
│   │   ├── auth.css
│   │   ├── dashboard.css
│   │   ├── editor.css
│   │   ├── post.css
│   │   └── posts.css
│   ├── js/                     # JavaScript files
│   │   ├── script.js
│   │   └── post.js
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
├── uploads/                    # User uploads
│   └── [uploaded files]
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
└── about/                      # About pages
    ├── about.php
    ├── contact.php
    └── index.php
```

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

1. **Cleaner Structure**: Logical separation of different types of files
2. **Better Maintainability**: Easier to find and modify specific functionality
3. **Improved Security**: Clear separation between public and private areas
4. **Scalability**: Structure supports future growth and additions
5. **Developer Experience**: More intuitive file organization

## Migration Notes

- Root `index.php` now redirects to `public/index.php`
- All path references have been updated to work with new structure
- Asset paths updated to reflect new organization
- Database configuration moved to `app/config/database.php`
- All include/require statements updated to new paths


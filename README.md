# Prime Fleet Services (PFS) - Full PHP Clone & Content Management System (CMS)

A complete, production-ready clone of the **Prime Fleet Services** website built with a modern **PHP backend**, zero-configuration database architecture, and a full-featured **Admin Control Panel** to manage all contents, banners, services, certificates, contact inquiries, and site branding.

---

## 🚀 Quick Start (Local Run)

### Option 1: One-Click Launcher (Windows)
Simply double-click the included launcher:
```
run-server.bat
```
This automatically starts the website server using the bundled portable PHP environment at `http://localhost:8000`.

### Option 2: Command Line (Any PHP Environment)
If you have PHP installed globally or using XAMPP/WAMP/Laragon:
```powershell
php -S 127.0.0.1:8000
```
Then open:
- **Main Website**: [http://localhost:8000/index.php](http://localhost:8000/index.php)
- **Admin Control Panel**: [http://localhost:8000/admin/](http://localhost:8000/admin/)

---

## 🔐 Admin CMS Credentials

- **URL**: `http://localhost:8000/admin/` (or `http://localhost:8000/pfsadmin/`)
- **Default Username**: `admin`
- **Default Password**: `admin123`

*(You can change your username, email, and password anytime under **Admin Profile & Security**).*

---

## 🛠 Features & Content Management Modules

1. **Dashboard Overview (`/admin/index.php`)**:
   - Real-time stats: Total Inquiries, Unread Leads, Active Services, Certificates, Newsletter Subscribers.
   - Recent leads table with 1-click view and status tracking.
2. **Site Settings & Branding (`/admin/settings.php`)**:
   - Company identity: Site title, tagline, SEO meta descriptions.
   - Logo & Favicon file upload with instant live preview.
   - Contact numbers: India Support (`+91-9679897979`) and US Support (`(747) 236-2181`).
   - Support and info email addresses.
   - Physical office / workshop address (`WEBEL IT PARK`).
   - Working hours text (`24 X 7 Live`).
   - Social links: Facebook, Instagram, Twitter/X, YouTube, Reddit, LinkedIn.
   - WhatsApp widget number and customizable pre-filled message.
   - Copyright and footer narrative.
3. **Hero Banner Sliders (`/admin/sliders.php`)**:
   - Add, edit, reorder, or toggle homepage banner slides.
   - Banner background image upload, headlines, badges, and button links.
4. **Services Manager (`/admin/services.php`)**:
   - Categorized under **BPO & Customer Support** or **Digital Marketing**.
   - Title, slug, Bootstrap icon class, short card summary, full details, and card image upload.
5. **Certificates & Accreditation (`/admin/certificates.php`)**:
   - Upload and manage compliance certificates (e.g. NLA Certificates) displayed in the homepage carousel.
6. **Why Choose Us & Statistics (`/admin/statistics.php`)**:
   - Manage the 6 core pillars (Modern Workspace, Trained Staff, Standardized SOPs, QA, 24/7 Redundancy, Security).
   - Manage KPI metrics (100% Satisfied, 100% Result, 24/7 Support, 5 Continents).
7. **Challenges vs Solutions (`/admin/challenges.php`)**:
   - Full control over the BPO Problem vs PFS Solution comparison cards.
8. **CEO Message & Company Story (`/admin/about_content.php`)**:
   - Rich narrative editor for the CEO Message and Company Overview.
9. **Inquiries & Leads Inbox (`/admin/inquiries.php`)**:
   - Complete contact form submissions inbox with unread indicator badge.
   - Read full message, reply via email client, mark read/unread, and **1-Click Export to CSV**.
10. **Newsletter Subscribers (`/admin/subscribers.php`)**:
    - Manage email subscribers with **1-Click Export to CSV**.

---

## 🌐 Public Website Pages (1:1 Clone)

- **`index.php`**: Dynamic Homepage (Hero Slider, Services, Marquee, Business Model, Why Choose Us, Certificates, Challenge vs Solution, CEO Message, Quick Contact Form).
- **`about.php`**: About Us, Operational Infrastructure, Vision & Mission.
- **`services.php`**: Dedicated BPO & Customer Support Services showcase.
- **`digital.php`**: Full Digital Marketing agency breakdown (SEO, PPC, Meta Ads, Copywriting, Graphic Design, Video Production).
- **`contact.php`**: Interactive Contact Us page with phone cards, office location, interactive Google Map, and live AJAX form submission.

---

## 🗄 Database Configuration

### SQLite (Default - Out of the Box)
The project is configured by default to use SQLite (`data/pfs.db`). It requires **zero configuration or database installation**—all tables and default data from Prime Fleet Services are already pre-populated!

### MySQL / MariaDB (For Web Hosts / cPanel / phpMyAdmin)
To switch to MySQL:
1. Create a MySQL database (e.g. `pfs_db`) and import `database.sql`.
2. Open `includes/config.php` and update:
   ```php
   define('DB_DRIVER', 'mysql');
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'pfs_db');
   define('DB_USER', 'your_db_username');
   define('DB_PASS', 'your_db_password');
   ```

---

## 📁 File Structure

```
PFS/
├── assets/                       # Stylesheets, JavaScript, fonts, and images
│   ├── css/                      # Bootstrap, animations, and theme styles
│   ├── js/                       # jQuery, bootstrap, theme plugins
│   ├── img/                      # Logos, backgrounds, services, and slider banners
│   └── fonts/                    # Apfel Grotezk & Bootstrap Icons
├── plugins/                      # Swiper slider, Fancybox, Nice Select, Odometer
├── uploads/                      # Uploaded images (branding, certificates, banners)
├── data/                         # SQLite database file (pfs.db)
├── includes/                     # Modular backend includes
│   ├── config.php                # App and database configuration
│   ├── db.php                    # PDO connection layer
│   ├── functions.php             # Sanitization, auth, upload, and CSRF helpers
│   ├── header.php                # Navigation, branding, and color mode toggle
│   ├── footer.php                # Dynamic footer, contact cards, and WhatsApp button
│   ├── preloader.php             # Animated loading screen
│   └── seed.php                  # Database schema and seed data
├── admin/                        # Full-featured Admin CMS
│   ├── index.php                 # Dashboard overview
│   ├── login.php                 # Secure login
│   ├── logout.php                # Session logout
│   ├── settings.php              # Global site settings & branding
│   ├── sliders.php               # Hero banner manager
│   ├── services.php              # Services manager
│   ├── certificates.php          # Certificates manager
│   ├── statistics.php            # Why Choose Us & Statistics
│   ├── challenges.php            # Challenges vs Solutions
│   ├── about_content.php         # CEO Message & Company Story
│   ├── inquiries.php             # Lead generation inbox & CSV export
│   ├── subscribers.php           # Newsletter list & CSV export
│   ├── profile.php               # Admin profile & password change
│   └── includes/                 # Admin header, sidebar, auth guard
├── index.php                     # Dynamic Homepage
├── about.php                     # Dynamic About Us
├── services.php                  # Dynamic Services
├── digital.php                   # Dynamic Digital Marketing
├── contact.php                   # Dynamic Contact Us
├── submit-inquiry.php            # Contact API endpoint
├── subscribe.php                 # Newsletter API endpoint
├── database.sql                  # MySQL/MariaDB schema dump
└── run-server.bat                # 1-Click Windows server launcher
```

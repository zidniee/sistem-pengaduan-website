<p align="center">
    <img src="public/images/diskominfosp.png" width="150" alt="Logo Diskominfo SP">
</p>

<h1 align="center">Complaint Management System</h1>

<p align="center">
    Official web-based reporting platform for digital content moderation
</p>

<p align="center">
    <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel" alt="Laravel 11">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php" alt="PHP 8.2+">
   <img src="https://img.shields.io/badge/Tailwind-4.0-38B2AC?style=flat&logo=tailwind-css" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/License-Proprietary-red.svg" alt="License">
</p>

---

## 📋 About

A comprehensive digital complaint management platform designed to streamline the reporting and tracking of online content violations. The system provides a secure, professional, and transparent mechanism for handling public reports with real-time status updates and analytics.

### Key Objectives

- 🎯 Provide an official channel for reporting inappropriate online content
- ⚡ Ensure fast and accurate complaint processing
- 🔒 Maintain data security and reporter confidentiality
- 📊 Deliver transparent status tracking to reporters
- 📈 Facilitate complaint data management and analytics

---

## ✨ Key Features

### Public Reporting Portal

| Feature | Description |
|---------|-------------|
| **Information Hub** | Comprehensive system guidance and usage instructions |
| **Complaint Submission** | Complete reporting form with:<br>• Platform selection (Facebook, TikTok, Instagram, etc.)<br>• Content URL/link<br>• Account username<br>• Detailed description<br>• Screenshot/evidence attachment |
| **Complaint Tracking** | Real-time status lookup using unique tracking code |
| **Complaint History** | Full report archive for registered users |
| **User Dashboard** | Personal analytics dashboard with recent complaint overview |
| **Status Updates** | Transparent workflow: Under Review → Verification → Resolved/Closed |

### Administrative Dashboard

| Feature | Description |
|---------|-------------|
| **Analytics Dashboard** | Comprehensive reporting metrics with daily/monthly/yearly trends |
| **Complaint Management** | Advanced complaint queue with:<br>• Keyword, status, and period filtering<br>• Sorting and pagination controls<br>• Optimized data viewing interface |
| **Complaint Processing** | Detailed view with status update capabilities |
| **Platform Management** | Social media platform configuration and maintenance |
| **PDF Export** | Custom report generation with filters:<br>• Search keywords, status, and semester period<br>• Maximum 500 records per document |
| **Excel Import** | Bulk complaint import from spreadsheet files |
| **Data Visualization** | Statistical reports including:<br>• Daily complaint counts<br>• Monthly performance snapshots<br>• Yearly trend analysis |

---

## 🛠 Technology Stack

### Backend
- **Framework:** Laravel 12
- **ORM:** Eloquent
- **Database:** MySQL 8.0+
- **PDF Generation:** Barryvdh DomPDF
- **Excel Processing:** Maatwebsite Excel
- **Authentication:** Laravel Breeze with role-based access

### Frontend
- **Styling:** Tailwind CSS v4.0.7
- **JavaScript:** Alpine.js
- **Icons:** SVG inline assets
- **Build Tool:** Vite
- **UI Enhancement:** Custom scrollbar styling

### Testing & Quality Assurance
- **Testing Framework:** Pest PHP
- **Code Formatting:** Laravel Pint
- **Development Environment:** Laravel Sail (optional)

### Security & Performance Features
- Rate limiting protection
- Responsive design (Mobile, Tablet, Desktop)
- Real-time analytics dashboard
- Advanced filtering and search capabilities
- Encrypted data storage
- Smooth user experience enhancements

---

## 📦 Installation & Setup

### System Requirements
- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ and npm
- MySQL 8.0 or higher

### Installation Steps

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd sistem-pengaduan-web-prostitusi
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Edit `.env` file with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Database Migration**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build Frontend Assets**
   ```bash
   npm run build
   ```

6. **Start Application**
   ```bash
   php artisan serve
   ```
   
   For development with hot module replacement:
   ```bash
   npm run dev
   ```

### Quick Setup Scripts

**Automated setup:**
```bash
composer run setup
```

**Development mode:**
```bash
composer run dev
```

---

## 📁 Database Structure

### Core Tables

**users** - User accounts and authentication
```sql
id, name, email, password, role (user/operator), created_at, updated_at
```

**complaints** - Complaint records
```sql
id, user_id, platform_id, ticket, username, 
account_url, description, screenshot_url, 
submitted_at, created_at, updated_at
```

**platforms** - Social media platforms
```sql
id, name, created_at, updated_at
```

**inspections** - Complaint status audit log
```sql
id, complaint_id, old_status, new_status, 
inspected_at, created_at, updated_at
```

**daily_reports_count** - Daily statistics aggregation
```sql
id, report_date, count, created_at, updated_at
```

**monthly_snapshot** - Monthly performance metrics
```sql
id, year_month, total_reports, created_at
```

**yearly_snapshot** - Annual trend data
```sql
id, year, total_reports, created_at
```

---

## 🔒 Security Features

- **CSRF Protection:** Token validation on all state-changing requests
- **Rate Limiting:** Endpoint-specific throttling to prevent abuse
- **Password Policy:** Minimum 8 characters with complexity requirements
- **Data Encryption:** Sensitive information protected at rest
- **Role-Based Access Control:** Segregated user and operator permissions
- **Input Validation:** Comprehensive sanitization and validation rules
- **Secure Authentication:** Bcrypt password hashing with Laravel Breeze

### API Architecture

The system provides RESTful API endpoints for public reporting, user dashboard access, and administrative operations with proper authentication and authorization controls.

---

## 🎨 User Experience

- **Dynamic Page Titles** - Context-aware page titles
- **Branding** - Custom favicon and consistent visual identity
- **Smooth Animations** - Polished transitions and interactions
- **Loading States** - Skeleton loaders during data fetches
- **Modal Dialogs** - Intuitive popups for forms and confirmations
- **Custom Scrollbar** - Styled scrollbars matching theme
- **Scroll-to-Top** - Quick navigation button
- **Password Visibility Toggle** - Secure yet user-friendly password fields

---

## 🚀 Performance Optimization

- Fast build times with Vite bundling
- Optimized CSS output with Tailwind v4
- Lazy loading for media assets
- Efficient pagination for large datasets
- Database query optimization with indexes
- Rate limiting to prevent abuse

---

## 📋 Complaint Status Workflow

**Status Types:**
- **Under Review** - Initial processing stage
- **In Verification** - Content verification in progress
- **Resolved** - Valid complaint successfully processed
- **Closed** - Complaint rejected or does not meet criteria

---

## 🤝 Contributing

We welcome contributions from the community. Please submit pull requests or open issues for improvements and bug reports.

---

<p align="center">
    © 2026 Diskominfo SP Kota Surakarta. All rights reserved.
</p>


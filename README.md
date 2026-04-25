# Modulify - Learning Management System

> A modern, secure, and scalable learning management platform built with CodeIgniter 4 and TailwindCSS

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7-EF4223?logo=codeigniter&logoColor=white)](https://codeigniter.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active%20Development-blue)]()

## 📋 Daftar Isi

- [Tentang Project](#tentang-project)
- [Fitur Utama](#fitur-utama)
- [Tech Stack](#tech-stack)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Struktur Project](#struktur-project)
- [Database Setup](#database-setup)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Testing](#testing)
- [API Documentation](#api-documentation)
- [Kontribusi](#kontribusi)
- [License](#license)

## 📚 Tentang Project

**Modulify** adalah sistem manajemen pembelajaran berbasis web yang memungkinkan:

- **Pengajar** untuk membuat dan mengelola konten pembelajaran
- **Mahasiswa** untuk mengakses dan mempelajari materi secara terstruktur

Dibangun dengan CodeIgniter 4 untuk performa tinggi dan keamanan maksimal.

## ✨ Fitur Utama

- 🔐 **Sistem Autentikasi** - Login aman untuk mahasiswa dan pengajar
- 📖 **Manajemen Materi** - Buat, update, dan publikasikan konten pembelajaran
- 👥 **Multi-User** - Dukungan untuk mahasiswa, pengajar, dan admin
- 🎨 **UI Modern** - Interface responsif dengan TailwindCSS
- 🔒 **CSRF Protection** - Keamanan built-in CodeIgniter 4
- 📊 **Database Migrations** - Version control untuk database schema
- ✅ **Unit Testing** - Comprehensive test coverage dengan PHPUnit

## 🛠 Tech Stack

### Backend & Framework

| Komponen              | Teknologi     | Versi |
| --------------------- | ------------- | ----- |
| **Backend Framework** | CodeIgniter 4 | 4.7+  |
| **Language**          | PHP           | 8.2+  |
| **Database**          | MySQL/MariaDB | 5.7+  |
| **Testing**           | PHPUnit       | 10.5+ |

### Frontend & UI

| Komponen                 | Teknologi             | Versi  | Kegunaan                         |
| ------------------------ | --------------------- | ------ | -------------------------------- |
| **Styling**              | TailwindCSS           | 3.4+   | Responsive UI framework          |
| **Rich Text Editor**     | Quill.js              | Latest | Editor untuk konten pembelajaran |
| **Notification & Alert** | SweetAlert2           | Latest | Modal alerts yang user-friendly  |
| **CSS Processing**       | PostCSS, Autoprefixer | Latest | CSS optimization                 |

## 📋 Persyaratan Sistem

### Minimum Requirements

- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.0 atau lebih tinggi
- **Node.js**: 16.0+ (untuk TailwindCSS development)
- **Database**: MySQL 5.7+ atau MariaDB 10.2+

### PHP Extensions (Required)

- `intl` - Internationalization
- `mbstring` - Multi-byte String
- `curl` - HTTP Client
- `json` - JSON handling (default)
- `mysqlnd` - MySQL Native Driver (jika menggunakan MySQL)

### Rekomendasi Production

- PHP OPcache enabled
- Zend Guard Loader atau ionCube Loader
- Redis untuk session/caching (optional)

## 🚀 Instalasi

### Step 1: Clone Repository

```bash
git clone <repository-url>
cd modulify
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies (untuk TailwindCSS)

```bash
npm install
```

### Step 4: Environment Setup

```bash
# Copy file environment
cp env .env

# Generate application key
php spark key:generate
```

## ⚙️ Konfigurasi

### 1. Database Configuration (`.env`)

```env
database.default.hostname = localhost
database.default.database = modulify
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 2. Application Configuration (`.env`)

```env
app.baseURL = 'http://localhost:8080/'
app.environment = development
app.forceGlobalSecureRequests = false
app.CSPEnabled = false
```

### 3. Session Configuration

Ubah pada `app/Config/Session.php` sesuai kebutuhan production

## � Frontend Libraries

### 1. Quill.js - Rich Text Editor

Editor WYSIWYG untuk membuat konten pembelajaran interaktif

```html
<!-- Include Quill Library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />

<!-- Initialize Quill -->
<div id="editor"></div>
<script>
  const quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
      toolbar: [
        ['bold', 'italic', 'underline'],
        ['link', 'image', 'video'],
        [{ list: 'ordered' }, { list: 'bullet' }],
      ],
    },
  });
</script>
```

### 2. SweetAlert2 - User-Friendly Alerts

Notification modal yang lebih user-friendly dari alert() standar

```html
<!-- Include SweetAlert2 -->
<script src="/js/sweetalert2.min.js"></script>

<!-- Example Usage -->
<script>
  // Success Alert
  Swal.fire('Sukses!', 'Data berhasil disimpan', 'success');

  // Confirmation Dialog
  Swal.fire({
    title: 'Apakah Anda yakin?',
    text: 'Data tidak bisa dikembalikan!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus!',
  });
</script>
```

### 3. TailwindCSS - Utility-First CSS

```html
<!-- Development -->
<link href="/css/input.css" rel="stylesheet" />

<!-- Build untuk development -->
npm run dev

<!-- Production build (minified) -->
npm run build
```

## �📁 Struktur Project

```
modulify/
├── app/
│   ├── Config/              # Konfigurasi aplikasi
│   ├── Controllers/         # MVC Controllers
│   │   ├── api/            # API endpoints
│   │   ├── web/            # Web routes
│   │   └── BaseController.php
│   ├── Models/             # Database models
│   │   ├── MasterTutorialModel.php
│   │   └── TutorialContentModel.php
│   ├── Views/              # View templates
│   │   ├── auth/           # Authentication pages
│   │   ├── mahasiswa/      # Student pages
│   │   ├── master/         # Admin pages
│   │   └── layout/         # Shared layouts
│   ├── Database/
│   │   ├── Migrations/     # Database migrations
│   │   └── Seeds/          # Database seeders
│   ├── Filters/            # Route filters
│   └── Helpers/            # Helper functions
├── public/                  # Web root (DocumentRoot)
│   ├── css/                # Stylesheet
│   ├── js/                 # JavaScript
│   └── uploads/            # User uploads
├── tests/                   # Unit & Integration tests
├── vendor/                  # Composer dependencies
├── writable/               # Cache, logs, sessions
├── composer.json
├── package.json
├── tailwind.config.js
└── README.md
```

## 🗄️ Database Setup

### Step 1: Buat Database

```sql
CREATE DATABASE modulify CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Run Migrations

```bash
php spark migrate
```

### Step 3: (Optional) Seed Database

```bash
php spark db:seed DatabaseSeeder
```

## ▶️ Menjalankan Aplikasi

### Development Server

```bash
php spark serve
```

Akses di: `http://localhost:8080`

### Compile TailwindCSS

```bash
# Development (watch mode)
npm run dev

# Production (minified)
npm run build
```

### Production Deployment

```bash
# Install dependencies
composer install --no-dev
npm install --production

# Set environment
sed -i 's/CI_ENVIRONMENT = development/CI_ENVIRONMENT = production/' .env

# Migrate database
php spark migrate --no-interaction

# Set proper permissions
chmod -R 755 writable/
chmod -R 755 public/
```

## ✅ Testing

### Run All Tests

```bash
composer test
# atau
php spark test
```

### Run Specific Test File

```bash
php spark test tests/unit/HealthTest.php
```

### Generate Coverage Report

```bash
php spark test --coverage
```

## 🔌 API Documentation

### Base URL

```
http://localhost:8080/api/v1
```

### Authentication

API menggunakan session-based authentication. Login terlebih dahulu sebelum mengakses endpoint.

### Endpoints (Placeholder)

Dokumentasi lengkap API akan ditambahkan di `docs/API.md`

| Method | Endpoint          | Description                  |
| ------ | ----------------- | ---------------------------- |
| GET    | `/tutorials`      | List semua tutorial          |
| GET    | `/tutorials/{id}` | Detail tutorial              |
| POST   | `/tutorials`      | Create tutorial (Admin only) |
| PUT    | `/tutorials/{id}` | Update tutorial (Admin only) |
| DELETE | `/tutorials/{id}` | Delete tutorial (Admin only) |

## 🤝 Kontribusi

Kami menerima kontribusi dari komunitas! Berikut cara berkontribusi:

### Step 1: Fork Repository

```bash
git clone <your-fork-url>
cd project_pwl
git checkout -b feature/nama-fitur
```

### Step 2: Buat Changes

```bash
# Pastikan code Anda mengikuti PSR-12 standard
# Run tests sebelum commit
composer test
```

### Step 3: Push & Create Pull Request

```bash
git add .
git commit -m "feat: tambah fitur baru"
git push origin feature/nama-fitur
```

### Code Standards

- Follow PSR-12 coding standard
- Gunakan type hints
- Tambahkan dokumentasi PHPDoc
- Write unit tests untuk fitur baru

## 📝 License

Project ini dilisensikan di bawah [MIT License](LICENSE)

## 📞 Support

Untuk pertanyaan atau issue:

- Buka [GitHub Issues](https://github.com/codeigniter4/CodeIgniter4/issues)
- Kunjungi [CodeIgniter Forum](https://forum.codeigniter.com/)
- Slack: [CodeIgniter Chat](https://codeigniterchat.slack.com)

## 📚 Dokumentasi Lebih Lanjut

- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)
- [PHPUnit Documentation](https://phpunit.readthedocs.io/)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

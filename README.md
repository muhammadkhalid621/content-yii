# Multi-Tenant Content Management System (Yii2 CMS Prototype)

This project is a **multi-tenant CMS prototype** built on the **Yii2 Framework** with full **role-based access control (RBAC)**, **file management**, and **REST API integration**.

It supports:
- Multi-tenant user management
- Role-based access (Admin, Editor, Viewer)
- Content creation (articles, posts)
- File upload & serving
- Audit logging
- REST API (v1) for external integrations

---

## 🚀 Features

### 1. Authentication & Access
- Login / Logout with session handling
- Password reset via email token
- Role-based access control (RBAC)
- Tenant-based user isolation

### 2. User Management
- CRUD operations for users
- Assign roles (Admin, Editor, Viewer)
- Manage tenants
- Search and filter by username or email

### 3. Content Management
- Create, edit, delete, and view articles
- Rich text editor for content body
- Audit log for all user actions

### 4. File Management
- Upload and organize media files
- Auto-creates tenant-specific upload directories
- Validates file type and size
- Serve or download files securely

### 5. Audit Logging
- Tracks all CRUD actions and user activity
- Logs stored in DB with timestamps and user references

### 6. RESTful API
- Versioned API (`/api/v1/`)
- Endpoints for:
  - `/api/v1/auth/login`
  - `/api/v1/user`
  - `/api/v1/content`
  - `/api/v1/file`
- Secured with Bearer tokens
- Follows Yii2 REST best practices

---

## 🧱 Technology Stack

| Layer | Tech |
|-------|------|
| Backend | Yii2 Framework (PHP 8.2+) |
| Frontend | Yii2 Views + Bootstrap 5 |
| Database | MySQL / MariaDB |
| Storage | Local filesystem (`@app/runtime/uploads/`) |
| Auth | RBAC (`yii\rbac\DbManager`) |
| API | REST (Yii2 REST controllers) |

---

## ⚙️ Installation

### 1. Clone the Repository
```bash
git clone https://github.com/muhammadkhalid621/content-yii
cd content-management
composer install


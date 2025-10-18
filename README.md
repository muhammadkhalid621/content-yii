# Yii2 Multi-Tenant CMS Prototype

A complete prototype CMS built on **Yii2**, featuring **multi-tenant isolation**, **role-based access control**, **file management**, **audit logging**, and a **REST API (v1)**.  
This project is intended as a learning and demonstration tool for Yii2 architecture, RBAC, and modular design.

---

## 🚀 Features
- Multi-tenant architecture (`tenant_id`-based isolation)
- Roles & Permissions: **Admin**, **Editor**, **Viewer**
- User management (CRUD, login/logout, password hash)
- Content management (create, edit, publish, archive)
- File uploads with validation and metadata
- Audit logging of user actions and CRUD events
- REST API (`/api/v1`) with Bearer authentication
- Responsive Bootstrap 5 UI with top-bar search and pagination
- Unified search filters above every table

---

## 🧰 Tech Stack
| Component | Technology |
|------------|-------------|
| Backend | Yii2 Framework |
| Frontend | Yii2 Views + Bootstrap 5 |
| Database | MySQL / MariaDB |
| API | RESTful endpoints (`/api/v1`) |
| Auth | Sessions (web) + Bearer Tokens (API) |
| File Storage | Local (runtime/uploads) |
| Audit Logs | Database logging via `AuditBehavior` |

---

## 🧑‍💻 Requirements
- PHP **8.1+**
- Composer
- MySQL (e.g., via **MAMP**)
- OpenSSL, mbstring, PDO extensions
- NodeJS optional (for asset builds)

---

## ⚙️ Setup Instructions

### 1️⃣ Clone and Install Dependencies
```bash
git clone https://github.com/muhammadkhalid621/content-yii
cd content-management
composer install

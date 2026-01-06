# Humanity Foundation Dashboard

A secure, hierarchy-driven backend system for managing NGO members, approvals, and internal operations.

## 🚀 Features
- **Strict Hierarchy**: Super Admin → DM → BM → RM → RO. i made every thing like before after this changes
- **Controlled Access**: Users can only see and manage their own downline tree.
- **Data Recovery BIN**: 30-day recovery period for deleted users.
- **Auto-generated Employee IDs**: Format `HF[Designation][DDMMYY]XXXX`.
- **Premium UI**: TailAdmin-inspired responsive dashboard with glassmorphism.
- **Activity Logging**: Full audit trail for all system actions.

## 🛠 Prerequisites
- PHP 8.2+
- MySQL 5.7+
- Composer
- OpenSSL & Curl PHP extensions enabled

## 📦 Installation Steps

1. **Clone the repository** (or download files) to your `htdocs` folder.
2. **Enable PHP Extensions**:
   - Open your `php.ini` file.
   - Uncomment (remove `;`) these lines:
     ```ini
     extension=openssl
     extension=curl
     ```
   - Restart your Apache server.
3. **Database Setup**:
   - Create a database named `hf_database` in MySQL.
4. **Environment Setup**:
   - Copy `.env.example` to `.env` (already done).
   - Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`.
5. **Install Dependencies**:
   ```bash
   php composer.phar install
   ```
6. **Generate Key & Migrate**:
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```

## 🔐 Credentials
- **Super Admin**: `admin@humanityfoundation.org`
- **Password**: `Admin@123`

## 📂 Project Structure
- `app/Models/User.php`: Core hierarchy logic and permission scopes.
- `app/Http/Controllers/UserController.php`: Downline and BIN management.
- `resources/views/layouts/app.blade.php`: Premium dashboard layout.
- `app/Console/Commands/CleanupBin.php`: Automated 30-day trash cleanup.

---
© 2026 Humanity Foundation. All Rights Reserved.

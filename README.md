# Humanity Foundation Dashboard 🌍

A high-performance, secure, and hierarchy-driven backend system designed for the **Humanity Foundation**. This platform manages NGO operations, field surveys, patient appointments, and real-time data synchronization with advanced AI integrations.

---

## 🤖 AI-Powered Intelligence (Core Highlight)
The platform integrates cutting-edge AI features (Powered by OpenRouter/Mistral) to streamline operations:

- **AI Smart Assistant**: A real-time, context-aware chatbot integrated into every dashboard page. It handles:
  - **Dynamic Knowledge Base**: Answers staff queries about foundation protocols and operational steps.
  - **Contextual Help**: Understands where the user is in the app and provides relevant guidance.
  - **Natural Language Data Insights**: (Planned) Querying patient and field data using plain English/Bengali.
- **Smart Data Formatting**: Intelligent parsing of complex inputs (like appointment times and dates) to ensure database integrity and consistent synchronization.
- **AI Health Flagging**: (Coming Soon) Automated risk assessment of patient survey data to prioritize critical medical cases.

---

## 🚀 Core Features

### 📡 Real-Time Data Sync
- **Google Sheets Integration**: Automated, bi-directional sync of Users, Patients, and Appointments.
- **Event-Driven Observers**: Every CRUD operation triggers an instant update to the foundation's central Google Sheets for external reporting.
- **Force Sync Tools**: CLI commands to bulk-sync existing database records to the cloud.

### 🏢 Governance & Hierarchy
- **Strict Organizational Structure**: Super Admin → DM (District) → BM (Block) → RM (Regional) → RO (Relative).
- **Scoped Visibility**: Users can only view, manage, and report on their own downline tree, ensuring data privacy and operational focus.
- **Automated Employee IDs**: Intelligent generation system with format `HF[Designation][Sequence]`.

### 🏥 Patient & Appointment Management
- **Centralized Patient Profiles**: Detailed medical survey records with demographic and health history.
- **Clinic Scheduling**: Robust appointment system with status tracking (Scheduled → Successful/Missed).
- **Validation Rules**: Smart validation for Aadhar, PAN, Phone Numbers, and Address formatting.

### 🛡️ Security & Recovery
- **Data Recovery BIN**: 30-day "Soft Delete" period for users with a dedicated restoration interface for Super Admins.
- **Audit Trails**: Full activity logging for critical system actions.
- **Secure Authentication**: Role-based access control (RBAC) with hashed credentials.

### 🎨 Premium User Experience
- **TailAdmin Glassmorphism**: High-end UI design with a modern dark/light mode toggle.
- **Responsive Tree View**: Visual hierarchy representation of the team structure.
- **Dynamic Dashboards**: Real-time stats counting team members, patients, and pending tasks.

---

## 🛠 Prerequisites
- PHP 8.2+
- MySQL 5.7+
- Composer
- OpenSSL & Curl PHP extensions enabled
- OpenRouter API Key (for AI features)

## 📦 Installation Steps

1. **Clone the repository** to your server root.
2. **Enable PHP Extensions**:
   - Open `php.ini`.
   - Uncomment `extension=openssl` and `extension=curl`.
   - Restart Apache.
3. **Database Setup**:
   - Create a database named `hf_database`.
4. **Environment Setup**:
   - Rename `.env.example` to `.env`.
   - Update `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
   - Add `OPENROUTER_API_KEY="..."` and `GOOGLE_SHEET_WEB_APP_URL="..."`.
5. **Install & Initialize**:
   ```bash
   php composer.phar install
   php artisan key:generate
   php artisan migrate --seed
   ```

## 🔐 Admin Credentials
- **Super Admin**: `admin@humanityfoundation.org`
- **Password**: `Admin@123`

---


Project Setup & Launch Plan
Goal Description
Start the Laravel project HF located in c:\xampp\htdocs\HF and make it accessible at http://localhost/HF/public.

User Review Required
None currently.
Proposed Changes
Setup Steps
Install PHP Dependencies: Run composer install to ensure vendor directory is up to date.
Install Node Dependencies: Run npm install.
Database Setup:
Check if database hf_database exists.
Run php artisan migrate to create tables.
Frontend Build:
Run npm run build to generate static assets for production/local serving.
Verification:
Check if the URL http://localhost/HF/public is accessible.

-----


© 2026 Humanity Foundation | *Empowering Health through Technology*



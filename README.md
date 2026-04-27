# PERPUSQU — Integrated Digital Library System

**PERPUSQU** is a professional, modular, and enterprise-grade library management system built with the latest Laravel framework. It provides a comprehensive suite of tools for managing physical collections, digital repositories, member services, and public discovery.

---

## 🚀 Key Modules & Features

The application is architected using a **Domain-Driven Modular Structure**, ensuring high maintainability and scalability.

### 🔍 1. OPAC (Online Public Access Catalog)
The public-facing portal for patrons to discover library resources.
- **Advanced Search**: Keyword-based discovery for bibliographic records.
- **Record Details**: Comprehensive view of metadata and item availability.
- **Digital Preview**: Integrated viewer for digital assets.
- **Public Information**: Dedicated About and Help sections.

### 📚 2. Cataloging (Bibliographic Management)
The core engine for metadata management.
- **Bibliographic Records**: Full CRUD for book and resource metadata.
- **Publication Lifecycle**: State transitions (Draft, Published, Archived, Reactivated).
- **Authority Control**: Integration with Master Data for consistency.

### 🏢 3. Collection Management
Granular tracking of physical library assets.
- **Physical Items**: Individual copy management (Barcoding, SKU).
- **Status Lifecycle**: Real-time tracking (Available, Damaged, Lost, Repair).
- **History Tracking**: Audit trail for every physical item.

### 💾 4. Digital Repository
Management of electronic resources and institutional assets.
- **Digital Assets**: Support for various file formats and metadata.
- **OCR Integration**: Automated text extraction from digital documents.
- **Access Control**: Secure publication and archival of digital materials.

### 🔄 5. Circulation
Complete lifecycle management for loans and returns.
- **Loan Processing**: Streamlined check-out with validation logic.
- **Active Monitoring**: Real-time view of all outstanding loans.
- **Renewals & Returns**: Easy processing of extensions and check-ins.
- **Fines Management**: Automated fine calculation, settlement, and waivers.

### 👥 6. Member & Identity Management
User-centric management for both patrons and staff.
- **Member Profiles**: Detailed registration, history, and status tracking.
- **Status Control**: Activation, Deactivation, and Blocking mechanisms.
- **Granular RBAC**: Permission-based access control (Spatie Permission).
- **User Security**: Profile management, password resets, and session control.

### ⚙️ 7. System Administration (Core)
The nerve center of the application.
- **Admin Dashboard**: Real-time analytics and system overview.
- **Institution Profile**: Configurable institutional metadata.
- **Operational Rules**: Customizable settings for library policies.

---

## 🛠 Technical Stack

- **Framework**: [Laravel 13](https://laravel.com) (PHP 8.3+)
- **Architecture**: Modular Monolith (Domain-Driven)
- **Frontend**: [Tailwind CSS 4](https://tailwindcss.com), [Vite 6](https://vitejs.dev)
- **Database**: Eloquent ORM with robust relationships
- **Security**: Spatie Laravel Permission (RBAC) & Activity Log (Audit)
- **Queues**: Laravel Horizon for background processing

---

## 💻 Development & Installation

### Prerequisites
- PHP 8.3 or higher
- Composer
- Node.js & NPM

### Quick Setup
```bash
# Install dependencies, generate keys, and migrate database
composer run setup
```

### Running Locally
```bash
# Start the full development stack (Server, Queue, Vite, Logs)
composer run dev
```

### Testing
```bash
# Run the test suite
composer run test
```

---

## 📂 Project Structure

```text
app/Modules/
├── Audit/             # Activity logging and audit trails
├── Catalog/           # Bibliographic and metadata management
├── Circulation/       # Loan, return, and fine processing
├── Collection/        # Physical item tracking
├── Core/              # Dashboard and system settings
├── DigitalRepository/ # Digital asset and OCR management
├── Identity/          # Authentication and RBAC
├── MasterData/        # Reference data (Authors, Publishers, etc.)
├── Member/            # Patron/Member management
├── Opac/              # Public discovery portal
├── Profile/           # User profile management
└── Reporting/         # Analytics and reporting services
```

---

## 📄 License

This project is proprietary software. All rights reserved.


# Panduan Deploy PERPUSQU di Shared Hosting cPanel

Panduan ini menjelaskan langkah-langkah untuk mendeploy aplikasi **PERPUSQU** (Laravel 13) ke shared hosting menggunakan domain **perpus.gawiqu.com**.

## 📋 Persyaratan Sistem
*   **PHP Version**: 8.3 atau lebih tinggi (Wajib untuk Laravel 13).
*   **Database**: MySQL atau MariaDB.
*   **Extensions**: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML.

---

## 1. Persiapan Lokal (Local Environment)

Sebelum mengunggah, pastikan aset frontend sudah di-build dan file dibersihkan.

1.  **Build Assets**:
    Jalankan perintah ini di komputer lokal Anda:
    ```bash
    npm run build
    ```
2.  **Hapus Folder yang Tidak Diperlukan**:
    Hapus folder `node_modules` (tidak diperlukan di hosting).
3.  **Kompres Proyek**:
    Kompres seluruh folder proyek menjadi file `.zip` (misalnya `perpusqu.zip`). 
    > [!TIP]
    > Jika hosting Anda mendukung terminal (SSH), Anda bisa mengunggah tanpa folder `vendor` lalu menjalankan `composer install` di hosting. Jika tidak, sertakan folder `vendor` dalam zip.

---

## 2. Konfigurasi di cPanel

### A. Membuat Subdomain
1.  Login ke cPanel.
2.  Cari menu **Domains** > **Subdomains**.
3.  Buat subdomain:
    *   **Subdomain**: `perpus`
    *   **Domain**: `gawiqu.com`
    *   **Document Root**: `public_html/perpus.gawiqu.com/public` (PENTING: Harus mengarah ke folder public).

### B. Mengatur Versi PHP
1.  Cari menu **MultiPHP Manager** atau **Select PHP Version**.
2.  Pilih domain `perpus.gawiqu.com`.
3.  Set versi PHP ke **8.3**.

### C. Membuat Database
1.  Buka **MySQL® Database Wizard**.
2.  Buat database (misal: `u123_perpusqu`).
3.  Buat user database (misal: `u123_userqu`) dan catat passwordnya.
4.  Berikan **All Privileges** kepada user tersebut terhadap database yang dibuat.

---

## 3. Upload dan Penataan File

Dengan Document Root yang diarahkan langsung ke folder `/public`, Anda dapat meletakkan seluruh file proyek Laravel di dalam folder utama subdomain.

1.  **Upload ZIP**:
    Gunakan **File Manager**, unggah `perpusqu.zip` ke folder `public_html/perpus.gawiqu.com`.
2.  **Ekstrak**:
    Ekstrak file tersebut di tempat. Pastikan struktur foldernya adalah:
    *   `public_html/perpus.gawiqu.com/app`
    *   `public_html/perpus.gawiqu.com/public` (Ini yang diakses publik)
    *   `public_html/perpus.gawiqu.com/vendor`
    *   ... file lainnya.
3.  **Keamanan**:
    Karena folder utama berisi file sensitif (seperti `.env`), pastikan akses ke file selain folder `public` dibatasi (biasanya otomatis oleh cPanel jika Document Root diatur ke subfolder).

---

## 4. Konfigurasi Environment (`.env`)

1.  Cari file `.env.example` di folder `~/perpusqu_app`, rename menjadi `.env`.
2.  Edit file `.env` dan sesuaikan nilainya:
    ```env
    APP_NAME=PERPUSQU
    APP_ENV=production
    APP_KEY=base64:xxx... (Gunakan php artisan key:generate jika via SSH)
    APP_DEBUG=false
    APP_URL=https://perpus.gawiqu.com

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=u123_perpusqu
    DB_USERNAME=u123_userqu
    DB_PASSWORD=password_anda_tadi
    ```

---

## 5. Migrasi Database dan Optimasi

### Opsi A: Jika memiliki akses SSH (Terminal)
1.  Masuk ke direktori aplikasi: `cd ~/public_html/perpus.gawiqu.com`
2.  Jalankan perintah berikut:
    ```bash
    php artisan migrate --force
    php artisan db:seed --class=SuperAdminSeeder
    php artisan db:seed --class=AdminUserSeeder
    php artisan db:seed --class=MemberUserSeeder
    php artisan storage:link
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

### Opsi B: Jika tidak ada SSH (Manual via PHP/Cron)
1.  **Import Database**: Export database lokal via phpMyAdmin, lalu import ke phpMyAdmin cPanel.
2.  **Storage Link**: Buat file `symlink.php` di `public_html/perpus.gawiqu.com` berisi:
    ```php
    <?php
    symlink('/home/username/perpusqu_app/storage/app/public', '/home/username/public_html/perpus.gawiqu.com/storage');
    echo "Symlink created!";
    ```
    Akses `https://perpus.gawiqu.com/symlink.php` satu kali, lalu hapus filenya.

---

## 6. Penyelesaian & Troubleshooting

*   **Folder Permissions**: Pastikan folder `storage` dan `bootstrap/cache` memiliki izin **775** atau **777** (jika diperlukan).
*   **HTTPS**: Pastikan SSL (AutoSSL) sudah aktif di cPanel untuk domain tersebut.
*   **Symlink Storage**: Pastikan folder `storage` di document root mengarah ke `storage/app/public` aplikasi agar gambar/file muncul.

---
**PERPUSQU** sekarang seharusnya sudah online di [https://perpus.gawiqu.com](https://perpus.gawiqu.com).

# Daftar Kredensial Login User PERPUSQU

Berikut adalah data kredensial login user yang tersedia secara default di sistem PERPUSQU (setelah menjalankan seeder):

| No | Role | Username | Password | Email | Status |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 1 | Super Admin | `superadmin` | `Admin@123` | `superadmin@perpusqu.local` | Aktif |
| 2 | Admin Perpustakaan | `admin_perpus` | `Admin@123` | `admin_perpus@perpusqu.local` | Aktif |
| 3 | Pustakawan | `pustakawan` | `Admin@123` | `pustakawan@perpusqu.local` | Aktif |
| 4 | Petugas Sirkulasi | `petugas_sirkulasi` | `Admin@123` | `petugas_sirkulasi@perpusqu.local` | Aktif |
| 5 | Operator Repositori Digital | `operator_digital` | `Admin@123` | `operator_digital@perpusqu.local` | Aktif |
| 6 | Pimpinan Perpustakaan | `pimpinan` | `Admin@123` | `pimpinan@perpusqu.local` | Aktif |

> [!NOTE]
> - Password default `Admin@123` digunakan untuk seluruh akun seeder di atas.
> - Seluruh user didefinisikan dalam `SuperAdminSeeder.php` dan `AdminUserSeeder.php`.
> - User baru dapat dibuat atau dimodifikasi melalui menu User Management setelah login sebagai Super Admin.

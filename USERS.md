# Daftar Kredensial Login User PERPUSQU

Berikut adalah data kredensial login user yang tersedia secara default di sistem PERPUSQU (setelah menjalankan seeder):

## Admin & Staf

| No | Role | Username | Password | Email | Status |
|:---:|:---:|:---:|:---:|:---:|:---:|
| 1 | Super Admin | `superadmin` | `Admin@123` | `superadmin@perpusqu.local` | Aktif |
| 2 | Admin Perpustakaan | `admin_perpus` | `Admin@123` | `admin_perpus@perpusqu.local` | Aktif |
| 3 | Pustakawan | `pustakawan` | `Admin@123` | `pustakawan@perpusqu.local` | Aktif |
| 4 | Petugas Sirkulasi | `petugas_sirkulasi` | `Admin@123` | `petugas_sirkulasi@perpusqu.local` | Aktif |
| 5 | Operator Repositori Digital | `operator_digital` | `Admin@123` | `operator_digital@perpusqu.local` | Aktif |
| 6 | Pimpinan Perpustakaan | `pimpinan` | `Admin@123` | `pimpinan@perpusqu.local` | Aktif |

## Anggota Perpustakaan

| No | Username | Password | Email | Tipe | No. Anggota | Status |
|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| 1 | `ahmad.fauzi` | `Admin@123` | `ahmad.fauzi@perpusqu.local` | Mahasiswa | `AGT-2026-0001` | Aktif |
| 2 | `siti.nurhaliza` | `Admin@123` | `siti.nurhaliza@perpusqu.local` | Mahasiswa | `AGT-2026-0002` | Aktif |
| 3 | `budi.santoso` | `Admin@123` | `budi.santoso@perpusqu.local` | Dosen | `AGT-2026-0003` | Aktif |
| 4 | `rina.wulandari` | `Admin@123` | `rina.wulandari@perpusqu.local` | Mahasiswa | `AGT-2026-0004` | Aktif |
| 5 | `hendra.pratama` | `Admin@123` | `hendra.pratama@perpusqu.local` | Umum | `AGT-2026-0005` | Aktif |

> [!NOTE]
> - Password default `Admin@123` digunakan untuk seluruh akun seeder di atas.
> - Data user admin didefinisikan dalam `SuperAdminSeeder.php` dan `AdminUserSeeder.php`.
> - Data anggota didefinisikan dalam `MemberUserSeeder.php` (membuat akun User + record Member).
> - User baru dapat dibuat melalui menu User Management setelah login sebagai Super Admin.

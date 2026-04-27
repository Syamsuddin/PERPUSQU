# 24_NOTIFICATION_RULES.md

## 1. Nama Dokumen

Notification Rules Sistem Informasi Perpustakaan Hibrid Kampus PERPUSQU

## 2. Identitas Dokumen

### 2.1 Nama Sistem

PERPUSQU

### 2.2 Jenis Dokumen

Dokumen blueprint aturan notifikasi, alert, dan umpan balik pengguna

### 2.3 Status Dokumen

Resmi, acuan wajib pengembangan notifikasi sistem, flash message, alert operasional, email opsional, event trigger, prioritas pesan, dan aturan pengiriman notifikasi

### 2.4 Tujuan Dokumen

Dokumen ini menetapkan aturan resmi notifikasi pada PERPUSQU agar seluruh pesan sistem, umpan balik aksi, dan alert operasional berjalan konsisten, tidak berlebihan, tidak membingungkan pengguna, dan tetap sesuai dengan ruang lingkup fase 1 yang telah ditetapkan dalam blueprint sebelumnya. Dokumen ini menjadi acuan wajib bagi AI Agent, developer, reviewer, dan tester agar notifikasi yang dibangun selaras dengan role, workflow, validation, UI UX, audit, dan arsitektur sistem.

## 3. Dokumen Acuan Wajib

Dokumen ini wajib konsisten dengan:

1. 01_EXECUTIVE_SUMMARY.md
2. 02_STACK_TEKNOLOGI.md
3. 03_ARSITEKTUR_MODULAR.md
4. 04_PRD.md
5. 05_SRS.md
6. 06_USE_CASE.md
7. 07_ROLE_PERMISSION_MATRIX.md
8. 08_MENU_MAP.md
9. 09_ROUTE_MAP.md
10. 10_VIEW_MAP.md
11. 11_CONTROLLER_MAP.md
12. 12_SERVICE_LAYER.md
13. 13_MODEL_MAP.md
14. 14_SCHEMA.sql
15. 15_SEED.sql
16. 16_VALIDATION_RULES.md
17. 17_WORKFLOW_STATE_MACHINE.md
18. 18_UI_UX_STANDARD.md
19. 19_OPAC_UX_FLOW.md
20. 20_API_CONTRACT.md
21. 21_SEARCH_INDEXING_SPEC.md
22. 22_STORAGE_FILE_POLICY.md
23. 23_OCR_AND_DIGITAL_PROCESSING.md

Aturan wajib:

1. Notifikasi fase 1 tidak boleh bertentangan dengan schema yang sudah ditetapkan.
2. Fase 1 tidak mewajibkan tabel notifikasi baru.
3. Notifikasi utama fase 1 berfokus pada flash message, alert operasional, dan email opsional.
4. Semua notifikasi harus tunduk pada permission, role, dan visibilitas data.
5. Notifikasi tidak boleh membocorkan data privat.
6. Event notifikasi harus mengikuti workflow state machine resmi.
7. Audit log tetap menjadi kanal jejak resmi untuk aksi sensitif, bukan diganti oleh notifikasi.

## 4. Ruang Lingkup Dokumen

Dokumen ini mencakup:

1. Definisi notifikasi pada PERPUSQU
2. Jenis notifikasi fase 1
3. Kanal notifikasi yang didukung
4. Event pemicu notifikasi
5. Prioritas notifikasi
6. Aturan konten pesan
7. Aturan role dan penerima
8. Aturan frekuensi dan throttling
9. Aturan queue untuk notifikasi async
10. Aturan notifikasi UI
11. Aturan email opsional
12. Aturan alert operasional
13. Aturan keamanan notifikasi
14. Aturan testing notifikasi

## 5. Definisi Notifikasi di PERPUSQU

Notifikasi dalam PERPUSQU adalah segala bentuk pesan sistem yang memberi tahu pengguna tentang:

1. hasil aksi yang baru dilakukan
2. status proses yang sedang berjalan
3. keberhasilan atau kegagalan proses
4. kondisi operasional yang membutuhkan perhatian
5. informasi sistem yang relevan bagi role tertentu

Pada fase 1, notifikasi tidak dimaksudkan sebagai pusat komunikasi kompleks. Fokus utamanya adalah:

1. memberi umpan balik cepat ke pengguna internal
2. memberi alert operasional yang berguna
3. mendukung pengelolaan OCR, indexing, sirkulasi, dan akses aset digital
4. tetap ringan dan konsisten dengan arsitektur web monolith modular

## 6. Prinsip Umum Notifikasi

Prinsip resmi notifikasi PERPUSQU adalah:

1. Relevan
2. Singkat
3. Tepat sasaran
4. Tidak berlebihan
5. Aman
6. Konsisten
7. Dapat ditelusuri
8. Tidak menggantikan audit log
9. Tidak membuat distraksi yang tidak perlu
10. Selaras dengan UI UX standard

## 7. Jenis Notifikasi Fase 1

Jenis notifikasi resmi fase 1 adalah:

1. Flash message sinkron
2. Inline validation error
3. Status badge dan alert status proses
4. Dashboard operational alert
5. Queue and processing alert
6. Email opsional terbatas
7. Access denial message
8. Empty state and informational state message

Catatan:

1. Fase 1 belum mewajibkan notification center persisten berbasis database.
2. Fase 1 belum mewajibkan WhatsApp, SMS, atau push notification.
3. Fase 1 belum mewajibkan reminder otomatis berkala ke anggota.

## 8. Kanal Notifikasi Resmi

### 8.1 Kanal A, Flash Message UI

Dipakai untuk:

1. hasil simpan
2. hasil update
3. hasil hapus
4. hasil publish atau unpublish
5. hasil block atau unblock
6. hasil pinjam, kembali, perpanjang
7. hasil unggah file
8. hasil dispatch OCR
9. hasil dispatch reindex
10. gagal proses bisnis

Karakter:

1. sinkron
2. satu sesi
3. tidak persisten jangka panjang

### 8.2 Kanal B, Inline Validation Message

Dipakai untuk:

1. error validasi form
2. error field pada filter
3. error input upload
4. error parameter search atau API

Karakter:

1. dekat dengan field
2. spesifik
3. ringkas

### 8.3 Kanal C, Operational Alert di Halaman

Dipakai untuk:

1. status OCR gagal
2. status indexing gagal
3. pinjaman overdue
4. asset embargoed
5. item tidak available
6. anggota diblokir
7. akses aset tidak diizinkan

Karakter:

1. kontekstual
2. tampil pada halaman relevan
3. tidak selalu berupa popup

### 8.4 Kanal D, Dashboard Alert

Dipakai untuk:

1. jumlah OCR failed
2. jumlah indexing failed
3. jumlah pinjaman aktif
4. jumlah pinjaman overdue
5. alert operasional ringan untuk admin atau pimpinan

Karakter:

1. agregat
2. per role
3. berbentuk widget atau badge ringkas

### 8.5 Kanal E, Email Opsional

Dipakai secara terbatas pada fase 1 bila infrastruktur email diaktifkan.

Kandidat penggunaan:

1. notifikasi ekspor laporan selesai
2. notifikasi error proses berat tertentu ke admin
3. notifikasi operasional penting terbatas

Catatan:

1. email bukan keharusan aktif pada go live fase 1
2. email boleh disabled by default

### 8.6 Kanal F, Access Message

Dipakai untuk:

1. akses preview publik ditolak
2. akses download privat ditolak
3. asset tidak tersedia
4. record tidak ditemukan
5. user tidak punya izin aksi

Karakter:

1. aman
2. tidak teknis
3. tidak membocorkan detail internal

## 9. Kanal yang Tidak Wajib pada Fase 1

Kanal berikut belum menjadi bagian wajib fase 1:

1. WhatsApp
2. SMS
3. Mobile push notification
4. Browser push
5. Notification center database persisten
6. Reminder due date otomatis ke anggota
7. Newsletter atau broadcast

Catatan:

1. kanal tersebut dapat masuk fase berikutnya melalui revisi blueprint resmi
2. dokumen ini hanya menyiapkan fondasi aturan, bukan mewajibkan implementasi kanal tersebut saat ini

## 10. Struktur Tingkat Prioritas Notifikasi

Notifikasi dibagi ke 4 tingkat prioritas:

### 10.1 INFO

Untuk:

1. informasi biasa
2. hasil baca data
3. penjelasan status ringan
4. petunjuk operasional ringan

### 10.2 SUCCESS

Untuk:

1. simpan berhasil
2. update berhasil
3. publish berhasil
4. pengembalian berhasil
5. OCR berhasil dijadwalkan
6. reindex berhasil dijadwalkan

### 10.3 WARNING

Untuk:

1. data tidak lengkap tetapi masih dapat diproses
2. asset embargoed
3. anggota mendekati batas pinjam
4. item tidak available
5. hasil pencarian kosong
6. OCR belum dijalankan

### 10.4 ERROR

Untuk:

1. validasi gagal
2. proses bisnis ditolak
3. akses ditolak
4. OCR gagal
5. indexing gagal
6. file tidak tersedia
7. operasi storage gagal

## 11. Prinsip Pesan Notifikasi

Semua notifikasi harus:

1. memakai Bahasa Indonesia
2. singkat
3. jelas
4. tidak teknis berlebihan
5. menyebut aksi atau objek bila relevan
6. tidak mengandung data sensitif yang tidak perlu
7. tidak menggunakan path internal, stack trace, atau nama bucket

Contoh gaya yang benar:

1. Data berhasil disimpan.
2. Katalog berhasil diterbitkan.
3. Anggota berhasil diblokir.
4. Proses OCR berhasil dijadwalkan.
5. File tidak tersedia untuk akses publik.
6. Item tidak dapat dipinjam karena sedang dipinjam pengguna lain.

## 12. Anti Gaya Pesan

Notifikasi tidak boleh:

1. terlalu panjang
2. terlalu teknis
3. menampilkan SQL error
4. menampilkan stack trace
5. menampilkan path storage
6. menampilkan bucket name
7. menampilkan checksum
8. menampilkan seluruh isi exception ke user biasa

## 13. Penerima Notifikasi Berdasarkan Role

### 13.1 Super Admin

Dapat menerima:

1. semua flash message aksi yang ia lakukan
2. alert monitoring sistem
3. alert OCR dan indexing failed
4. alert queue retry
5. email operasional opsional

### 13.2 Admin Perpustakaan

Dapat menerima:

1. semua flash message operasionalnya
2. alert katalog
3. alert koleksi
4. alert member
5. alert sirkulasi
6. alert repositori digital
7. alert OCR dan indexing failed
8. email operasional opsional

### 13.3 Pustakawan

Dapat menerima:

1. flash message katalog dan koleksi
2. alert metadata tidak lengkap
3. alert aset digital yang relevan
4. alert OCR bila terkait aset yang dikelola

### 13.4 Petugas Sirkulasi

Dapat menerima:

1. flash message pinjam, kembali, perpanjang
2. alert member diblokir
3. alert item tidak available
4. alert keterlambatan
5. alert denda terbentuk

### 13.5 Operator Repositori Digital

Dapat menerima:

1. flash message unggah file
2. alert OCR queued, success, failed
3. alert reindex queued, success, failed
4. alert akses asset tidak valid
5. email operasional opsional bila diaktifkan

### 13.6 Pimpinan Perpustakaan

Dapat menerima:

1. ringkasan dashboard alert
2. informasi agregat overdue
3. informasi agregat OCR failed
4. informasi agregat digital access bila diaktifkan
5. tidak perlu notifikasi operasional rinci tiap aksi

### 13.7 Pengguna OPAC Publik

Dapat menerima:

1. empty state search
2. access denied message aman
3. preview unavailable message
4. bantuan pencarian
5. tidak menerima alert internal admin

## 14. Klasifikasi Notifikasi Menurut Tujuan

### 14.1 Transaction Feedback

Tujuan:
memberi umpan balik langsung setelah aksi berhasil atau gagal

Contoh:

1. simpan data
2. update data
3. block member
4. publish record
5. upload asset

### 14.2 Operational Alert

Tujuan:
memberi tahu kondisi proses yang perlu perhatian

Contoh:

1. OCR failed
2. index failed
3. item tidak tersedia
4. anggota diblokir
5. asset embargoed

### 14.3 Informational Guidance

Tujuan:
membantu pengguna memahami kondisi halaman

Contoh:

1. hasil pencarian kosong
2. belum ada data
3. file tidak memiliki preview publik
4. OCR belum pernah dijalankan

### 14.4 Administrative Exception Alert

Tujuan:
memberi tahu admin tentang kondisi exception

Contoh:

1. retry queue berhasil
2. full reindex diperlukan
3. storage file tidak ditemukan
4. checksum mismatch, bila fitur itu diaktifkan kelak

## 15. Event Pemicu Notifikasi Utama

Event berikut dapat memicu notifikasi pada fase 1:

1. login berhasil
2. login gagal
3. logout berhasil
4. create user
5. update user
6. activate user
7. reset password user
8. update profile
9. update settings
10. create master data
11. update master data
12. create bibliographic record
13. update bibliographic record
14. publish record
15. unpublish record
16. create physical item
17. update physical item
18. change item status
19. create member
20. update member
21. block member
22. unblock member
23. create loan
24. return loan
25. renew loan
26. create fine
27. upload digital asset
28. update digital asset
29. publish digital asset
30. unpublish digital asset
31. update access rule
32. request OCR
33. OCR success
34. OCR failed
35. request reindex
36. indexing success
37. indexing failed
38. queue retry
39. export report selesai, opsional
40. akses preview ditolak
41. akses download ditolak

## 16. Matriks Event ke Kanal Notifikasi

| Event | Flash Message | Inline Error | Page Alert | Dashboard Alert | Email Opsional |
|---|---|---|---|---|---|
| login berhasil | ya | tidak | tidak | tidak | tidak |
| login gagal | tidak | ya | tidak | tidak | tidak |
| simpan data berhasil | ya | tidak | tidak | tidak | tidak |
| validasi gagal | tidak | ya | tidak | tidak | tidak |
| anggota diblokir | ya | tidak | ya | opsional | tidak |
| item tidak available saat loan | ya | tidak | ya | tidak | tidak |
| pinjam berhasil | ya | tidak | tidak | opsional | tidak |
| kembali berhasil | ya | tidak | opsional | opsional | tidak |
| denda terbentuk | ya | tidak | ya | opsional | tidak |
| OCR dijadwalkan | ya | tidak | ya | opsional | tidak |
| OCR gagal | ya atau alert | tidak | ya | ya | opsional |
| reindex dijadwalkan | ya | tidak | ya | opsional | tidak |
| indexing gagal | ya atau alert | tidak | ya | ya | opsional |
| akses preview ditolak | ya atau page alert | tidak | ya | tidak | tidak |
| queue retry | ya | tidak | ya | ya | opsional |
| ekspor selesai | ya | tidak | opsional | opsional | opsional |

## 17. Aturan Flash Message

Flash message adalah kanal utama fase 1.

### 17.1 Aturan Umum

1. Ditampilkan setelah redirect
2. Hanya terkait aksi terakhir
3. Singkat
4. Tampil sekali
5. Bisa ditutup

### 17.2 Kategori Flash Message

1. success
2. error
3. warning
4. info

### 17.3 Contoh Success Message

1. Data pengguna berhasil disimpan.
2. Katalog berhasil diperbarui.
3. Item berhasil ditambahkan.
4. Anggota berhasil diaktifkan.
5. Proses peminjaman berhasil.
6. Aset digital berhasil diunggah.
7. Proses OCR berhasil dijadwalkan.
8. Reindex berhasil dijadwalkan.

### 17.4 Contoh Warning Message

1. Item tidak tersedia untuk dipinjam.
2. Asset sedang dalam masa embargo.
3. Belum ada hasil OCR untuk aset ini.
4. Belum ada data yang sesuai filter.

### 17.5 Contoh Error Message

1. Data gagal disimpan.
2. Pengguna tidak memiliki akses.
3. File tidak tersedia.
4. OCR gagal dijalankan.
5. Reindex gagal dijadwalkan.

## 18. Aturan Inline Validation Error

Inline validation error wajib mengikuti 16_VALIDATION_RULES.md.

Aturan:

1. muncul dekat field
2. tidak diganti oleh flash message umum
3. satu field dapat menampilkan lebih dari satu error bila perlu
4. password dan file tidak menampilkan nilai lama

Contoh:

1. Nama wajib diisi.
2. Email tidak valid.
3. Barcode sudah digunakan item lain.
4. File harus berformat PDF.

## 19. Aturan Page Alert

Page alert dipakai untuk kondisi kontekstual halaman.

Contoh penggunaan:

1. halaman detail asset digital menampilkan OCR failed
2. halaman detail member menampilkan status blocked
3. halaman detail item menampilkan status lost
4. halaman OPAC preview menampilkan access denied

Aturan:

1. tampil dekat konteks
2. tidak perlu popup
3. dapat persisten selama state masih berlaku
4. gunakan warna sesuai tingkat prioritas

## 20. Aturan Dashboard Alert

Dashboard alert adalah ringkasan, bukan detail semua event.

Sumber data dapat berasal dari:

1. digital_assets.ocr_status
2. digital_assets.index_status
3. loans.loan_status dan due_date
4. fines.status
5. activity_logs bila diperlukan agregat

Contoh dashboard alert:

1. 5 aset gagal OCR
2. 12 pinjaman melewati jatuh tempo
3. 3 aset gagal indexing
4. 7 denda outstanding

Aturan:

1. tampil per role
2. tidak menampilkan data yang tidak berhak dilihat role tersebut
3. cukup ringkas dan actionable

## 21. Aturan Email Opsional Fase 1

Email pada fase 1 bersifat opsional dan tidak wajib aktif saat awal go live.

### 21.1 Tujuan Email Opsional

1. pemberitahuan ekspor laporan selesai
2. pemberitahuan OCR failed massal ke admin
3. pemberitahuan indexing failed penting
4. pemberitahuan proses berat selesai

### 21.2 Aturan Umum Email

1. hanya dikirim bila mailer diaktifkan
2. hanya untuk event yang memang layak async
3. tidak dikirim untuk setiap aksi CRUD kecil
4. tidak menjadi satu satunya kanal informasi kritis
5. tetap harus ada fallback UI

### 21.3 Penerima Email Opsional

1. Super Admin
2. Admin Perpustakaan
3. Operator Repositori Digital
4. Pimpinan Perpustakaan, bila ringkasan tertentu diperlukan

### 21.4 Event Email yang Direkomendasikan

1. OCR failed batch atau berulang
2. indexing failed batch atau berulang
3. report export selesai
4. full reindex selesai, bila fitur itu diaktifkan

## 22. Event yang Tidak Perlu Email pada Fase 1

Event berikut tidak perlu email pada fase 1:

1. login berhasil
2. create user biasa
3. update master data
4. create loan
5. return loan
6. renew loan
7. publish record
8. upload cover
9. perubahan kecil yang cukup lewat flash message

## 23. Aturan Queue untuk Notifikasi Async

Notifikasi async, terutama email opsional, harus dijalankan melalui queue.

Job yang direkomendasikan:

1. `SendOperationalEmailNotificationJob`
2. `SendReportExportReadyEmailJob`, bila diaktifkan

Aturan:

1. tidak dijalankan langsung di request web
2. retry mengikuti aturan queue umum
3. gagal kirim email tidak boleh merusak proses utama yang sudah sukses

## 24. Aturan Konten Pesan per Domain

### 24.1 User dan Access

Contoh:

1. Akun pengguna berhasil diaktifkan.
2. Password pengguna berhasil direset.
3. Anda tidak memiliki izin untuk aksi ini.

### 24.2 Master Data

Contoh:

1. Pengarang berhasil ditambahkan.
2. Data penerbit berhasil diperbarui.
3. Data tidak dapat dihapus karena masih digunakan.

### 24.3 Katalog

Contoh:

1. Katalog berhasil dibuat.
2. Katalog berhasil diterbitkan.
3. Katalog tidak dapat diterbitkan karena metadata belum lengkap.

### 24.4 Koleksi Fisik

Contoh:

1. Item fisik berhasil ditambahkan.
2. Status item berhasil diperbarui.
3. Item tidak dapat dipinjam karena status tidak tersedia.

### 24.5 Anggota

Contoh:

1. Anggota berhasil ditambahkan.
2. Anggota berhasil diblokir.
3. Anggota tidak dapat meminjam karena sedang diblokir.

### 24.6 Sirkulasi

Contoh:

1. Peminjaman berhasil diproses.
2. Pengembalian berhasil diproses.
3. Perpanjangan berhasil diproses.
4. Denda sebesar Rp 5.000 tercatat.

### 24.7 Repositori Digital

Contoh:

1. Aset digital berhasil diunggah.
2. Akses aset berhasil diperbarui.
3. Proses OCR berhasil dijadwalkan.
4. Proses OCR gagal. Silakan coba ulang.
5. Proses reindex berhasil dijadwalkan.

### 24.8 OPAC Publik

Contoh:

1. Koleksi yang Anda cari belum ditemukan.
2. Preview file tidak tersedia untuk akses publik.
3. Koleksi ini memiliki 2 eksemplar tersedia.

## 25. Aturan Pesan Berdasarkan State Machine

Notifikasi wajib tunduk pada 17_WORKFLOW_STATE_MACHINE.md.

Contoh:

1. record draft tidak boleh menghasilkan pesan seolah sudah published
2. item loaned tidak boleh menghasilkan pesan item tersedia
3. member blocked harus memunculkan pesan penolakan loan
4. asset embargoed harus menampilkan warning yang tepat
5. OCR failed harus menampilkan status gagal, bukan masih diproses

## 26. Aturan Keamanan Konten Notifikasi

Notifikasi tidak boleh berisi:

1. file_path privat
2. bucket name
3. checksum
4. access rule internal detail yang sensitif
5. SQL error
6. stack trace
7. token internal
8. kredensial
9. data pengguna lain yang tidak relevan

Untuk notifikasi publik:

1. jangan tampilkan alasan teknis detail
2. gunakan kalimat aman dan netral
3. fokus pada apa yang pengguna boleh ketahui

## 27. Aturan Frekuensi dan Throttling

Agar notifikasi tidak berlebihan, berlaku aturan:

1. satu aksi utama menghasilkan satu flash message utama
2. satu halaman tidak boleh penuh alert duplikat
3. OCR failed berulang untuk aset yang sama tidak perlu membanjiri dashboard tanpa agregasi
4. email opsional untuk failure massal harus dapat digabung per periode
5. notifikasi yang sama berulang dalam waktu singkat sebaiknya di-throttle di level service atau queue

## 28. Aturan Aggregated Alert

Alert agregat disarankan untuk:

1. total OCR failed
2. total indexing failed
3. total overdue loans
4. total outstanding fines

Aturan:

1. dipakai di dashboard
2. tidak perlu kirim satu pesan untuk setiap objek secara terpisah
3. lebih sesuai untuk pimpinan atau admin

## 29. Aturan Notifikasi OPAC Publik

Karena pengguna OPAC publik belum memiliki akun internal fase 1, notifikasi publik terbatas pada:

1. search empty state
2. access denied message
3. preview unavailable message
4. informational guidance

Tidak ada:

1. inbox notifikasi publik
2. email reminder publik otomatis
3. push notification publik

## 30. Aturan Notifikasi API

API response bukan pengganti notifikasi UI, tetapi kontrak API harus mendukung pesan yang konsisten.

Aturan:

1. response JSON memakai field `message`
2. `success` menentukan kategori dasar
3. validation error masuk ke `errors`
4. UI frontend atau Livewire dapat memetakan message ke toast atau alert
5. API publik tidak boleh mengembalikan pesan teknis mentah

## 31. Aturan Notifikasi pada OCR dan Indexing

### 31.1 Saat Request OCR

Kanal:

1. flash message
2. page alert status queued

Pesan:

1. Proses OCR berhasil dijadwalkan.

### 31.2 Saat OCR Success

Kanal:

1. page alert atau badge status
2. dashboard aggregate, opsional

Pesan:

1. Hasil OCR tersedia.
2. Teks hasil ekstraksi berhasil diperbarui.

### 31.3 Saat OCR Failed

Kanal:

1. page alert
2. dashboard alert
3. email opsional ke admin tertentu

Pesan:

1. Proses OCR gagal. Silakan coba ulang.
2. Sebagian aset digital gagal diproses OCR.

### 31.4 Saat Reindex Requested

Kanal:

1. flash message
2. page alert

Pesan:

1. Proses reindex berhasil dijadwalkan.

### 31.5 Saat Index Failed

Kanal:

1. page alert
2. dashboard alert
3. email opsional

Pesan:

1. Proses indexing gagal. Silakan coba ulang.

## 32. Aturan Notifikasi pada Sirkulasi

### 32.1 Loan Success

Kanal:

1. flash message

Pesan:

1. Peminjaman berhasil diproses.

### 32.2 Loan Rejected

Kanal:

1. flash message
2. page alert kontekstual

Penyebab umum:

1. anggota diblokir
2. anggota tidak aktif
3. item tidak available
4. batas pinjam tercapai

Pesan contoh:

1. Peminjaman ditolak karena anggota sedang diblokir.
2. Peminjaman ditolak karena item tidak tersedia.
3. Peminjaman ditolak karena batas pinjam aktif sudah tercapai.

### 32.3 Return Success

Pesan:

1. Pengembalian berhasil diproses.
2. Denda keterlambatan tercatat sebesar Rp X, bila ada.

### 32.4 Renewal Success

Pesan:

1. Perpanjangan berhasil diproses.

### 32.5 Renewal Rejected

Pesan:

1. Perpanjangan tidak dapat diproses untuk pinjaman ini.

## 33. Aturan Notifikasi pada Katalog dan Koleksi

### 33.1 Publish Record

Pesan:

1. Katalog berhasil diterbitkan.

### 33.2 Publish Rejected

Pesan:

1. Katalog tidak dapat diterbitkan karena metadata belum lengkap.

### 33.3 Change Item Status

Pesan:

1. Status item berhasil diperbarui.
2. Item tidak dapat diubah ke status tersebut, bila transisi ditolak.

## 34. Aturan Notifikasi pada Access Control

Event:

1. role assignment
2. permission update
3. access denied
4. reset password

Pesan:

1. Role pengguna berhasil diperbarui.
2. Hak akses role berhasil diperbarui.
3. Anda tidak memiliki akses untuk halaman ini.
4. Password pengguna berhasil direset.

## 35. Aturan Notifikasi untuk Hasil Kosong dan Kondisi Normal

Tidak semua kondisi butuh warning atau error. Beberapa cukup memakai pesan informasional.

Contoh:

1. Belum ada data pengarang.
2. Belum ada aset digital untuk katalog ini.
3. Belum ada histori perpanjangan.
4. Belum ada hasil OCR untuk aset ini.
5. Belum ada hasil yang cocok dengan pencarian Anda.

## 36. Aturan Integrasi dengan Audit Log

Notifikasi tidak menggantikan audit log.

Aturan:

1. flash message sukses tidak cukup sebagai jejak resmi
2. aksi sensitif tetap harus dicatat di ActivityLog
3. email opsional tidak menjadi bukti audit
4. dashboard alert tidak menjadi bukti audit

Aksi yang wajib audit meski ada notifikasi:

1. reset password
2. block member
3. publish record
4. change item status
5. upload asset
6. update access rule
7. retry OCR
8. retry queue
9. reindex trigger sensitif

## 37. Aturan Integrasi dengan UI UX Standard

Semua notifikasi harus mengikuti 18_UI_UX_STANDARD.md.

Aturan UI:

1. flash message berada di area konsisten
2. inline validation dekat field
3. alert status berada pada konteks halaman
4. badge status bukan notifikasi singkat, tetapi indikator keadaan
5. dashboard alert bersifat agregat
6. warna mengikuti prioritas notifikasi

## 38. Aturan Integrasi dengan Storage Policy

Saat storage gagal atau file tidak tersedia:

1. user internal menerima pesan aman
2. publik menerima pesan yang lebih umum
3. notifikasi tidak boleh menampilkan file_path atau object key

Contoh internal:

1. File aset tidak tersedia. Silakan unggah ulang atau hubungi admin.

Contoh publik:

1. Preview file tidak tersedia untuk saat ini.

## 39. Aturan Integrasi dengan Search dan OCR

Saat search kosong:

1. gunakan empty state informasional
2. jangan gunakan error state berlebihan

Saat OCR gagal:

1. tampilkan alert kontekstual pada asset detail
2. dashboard dapat menampilkan jumlah gagal
3. email opsional hanya untuk admin tertentu

Saat reindex diperlukan:

1. tampilkan status queued atau pending
2. jangan tampilkan pesan sukses indexing final bila belum selesai

## 40. Aturan Implementasi Teknis

Notifikasi fase 1 disarankan diimplementasikan melalui:

1. flash session Laravel
2. validation error bawaan Form Request
3. badge dan alert dari data state di view
4. queue job untuk email opsional
5. dashboard widget agregat dari query service

Tidak diwajibkan pada fase 1:

1. tabel database notifications
2. Laravel notification database channel
3. notification center dropdown real time

## 41. Komponen Teknis yang Direkomendasikan

Komponen yang direkomendasikan:

1. `FlashMessagePresenter`
2. `OperationalAlertResolver`
3. `NotificationMessageFactory`
4. `SendOperationalEmailNotificationJob`, opsional
5. `DashboardAlertQueryService`

Catatan:

1. nama class final dapat menyesuaikan
2. fungsinya wajib tetap konsisten
3. jangan jadikan satu class super besar untuk semua pesan

## 42. Templating Pesan

Pesan notifikasi harus memakai template konsisten.

Pola sederhana:

1. objek + aksi + hasil
2. proses + status
3. alasan singkat bila gagal

Contoh:

1. Aset digital berhasil diunggah.
2. Status item berhasil diperbarui.
3. Proses OCR gagal. Silakan coba ulang.
4. Katalog tidak dapat diterbitkan karena metadata belum lengkap.

## 43. Notifikasi yang Tidak Perlu Ditampilkan

Sistem tidak perlu menampilkan notifikasi untuk semua hal kecil, misalnya:

1. load halaman sukses
2. baca detail data biasa
3. toggle filter biasa
4. pagination biasa
5. refresh list biasa

Prinsip:

1. notifikasi hanya bila ada nilai operasional
2. hindari gangguan yang tidak perlu

## 44. Skenario Utama Notifikasi

### 44.1 Skenario A, Simpan Katalog Berhasil

1. user menyimpan katalog
2. service sukses
3. redirect ke halaman relevan
4. tampil flash success
5. audit log dicatat

### 44.2 Skenario B, Publish Katalog Gagal

1. user klik publish
2. service memeriksa metadata
3. guard rule gagal
4. redirect kembali
5. tampil flash error atau warning
6. audit log opsional sesuai kebijakan

### 44.3 Skenario C, Loan Ditolak

1. petugas input anggota dan barcode
2. service memeriksa eligibility
3. rule tidak lolos
4. tampil flash error
5. page alert dapat menampilkan alasan kontekstual

### 44.4 Skenario D, OCR Gagal

1. operator jalankan OCR
2. job gagal
3. badge asset menjadi failed
4. detail asset menampilkan alert
5. dashboard agregat bertambah
6. email opsional dapat dikirim bila diaktifkan

### 44.5 Skenario E, Preview Publik Ditolak

1. pengguna publik membuka preview
2. service menolak akses
3. tampil halaman aman dengan pesan singkat
4. tidak ada detail teknis tampil

## 45. Testing Requirement Notifikasi

Pengujian minimum wajib mencakup:

1. flash success tampil setelah simpan data
2. flash error tampil setelah proses bisnis ditolak
3. validation error tampil di field yang benar
4. item blocked memunculkan pesan penolakan loan
5. OCR failed menampilkan badge dan alert yang benar
6. indexing failed muncul di dashboard alert yang tepat
7. access denied publik tidak membocorkan data teknis
8. email opsional, bila aktif, hanya dikirim untuk event yang tepat
9. notifikasi tidak tampil untuk aksi biasa yang tidak perlu
10. permission membatasi siapa yang melihat alert tertentu
11. role pimpinan hanya melihat agregat, bukan detail operasional berlebihan
12. hasil pencarian kosong memakai empty state, bukan error teknis

## 46. Anti Pattern yang Dilarang

Sistem notifikasi tidak boleh:

1. mengirim email untuk setiap aksi kecil
2. menampilkan popup berlebihan
3. menampilkan notifikasi ganda untuk satu aksi yang sama tanpa alasan
4. membocorkan file_path atau stack trace
5. menampilkan pesan sukses sebelum proses async benar benar selesai, bila konteksnya belum selesai
6. mengganti audit log dengan notifikasi biasa
7. menampilkan alert yang tidak relevan dengan role
8. menampilkan warning permanen yang sudah tidak relevan

## 47. Prioritas Implementasi Notifikasi

### Prioritas P1

1. flash message CRUD inti
2. inline validation error
3. access denied message
4. alert status OCR
5. alert status indexing
6. alert loan rejection
7. alert member blocked
8. dashboard alert agregat dasar

### Prioritas P2

1. email opsional untuk proses berat
2. alert ekspor laporan selesai
3. alert reindex failure agregat

### Prioritas P3

1. reminder anggota otomatis
2. notifikasi terjadwal
3. notification center persisten
4. kanal WhatsApp atau push

## 48. Dampak ke Dokumen Berikutnya

Dokumen ini menjadi acuan wajib untuk:

1. 25_REPORTING_SPEC.md
2. 28_SECURITY_POLICY.md
3. 29_AUDIT_LOG_SPEC.md
4. 30_ERROR_CODE.md
5. 31_TEST_PLAN.md
6. 32_TEST_SCENARIO.md
7. 38_TREE.md
8. 39_TRACEABILITY_MATRIX.md
9. 41_BACKEND_CHECKLIST.md
10. 42_FRONTEND_CHECKLIST.md
11. 45_SMOKE_TEST_CHECKLIST.md
12. 46_UAT_CHECKLIST.md

Aturan:

1. reporting spec dapat memanfaatkan alert agregat yang didefinisikan di sini
2. security policy harus mengatur perlindungan konten notifikasi
3. audit log spec harus membedakan audit trail dan notification message
4. error code document harus menjaga konsistensi pesan sistem
5. test plan wajib mencakup notifikasi utama lintas modul

## 49. Checklist Verifikasi Konsistensi

Dokumen ini dianggap konsisten bila:

1. kanal notifikasi fase 1 sesuai dengan schema yang sudah ada
2. tidak memerlukan tabel notifikasi baru secara wajib
3. event pemicu selaras dengan workflow state machine
4. role penerima sesuai permission matrix
5. UI notifikasi selaras dengan UI UX standard
6. konten notifikasi aman dan tidak membocorkan data privat
7. audit log tetap dipisahkan dari notifikasi

## 50. Kesimpulan

Dokumen Notification Rules ini menetapkan aturan resmi notifikasi PERPUSQU secara lengkap dan konsisten dengan blueprint 01 sampai 23. Dokumen ini memastikan bahwa hasil aksi, error validasi, alert OCR, alert indexing, penolakan akses, dan umpan balik operasional lainnya tampil secara tepat, aman, dan tidak berlebihan, tanpa melanggar keputusan arsitektural fase 1 yang belum mewajibkan notification center persisten atau kanal pesan eksternal penuh. Semua implementasi notifikasi PERPUSQU wajib merujuk dokumen ini.

END OF 24_NOTIFICATION_RULES.md

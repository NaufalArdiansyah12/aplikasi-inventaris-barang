# Laporan Dokumentasi Proyek Aplikasi Inventaris Barang dengan Sistem Rental Gratis dan Denda Keterlambatan

# NAMA: 				NOMOR ABSEN:

# Kelas: 				MAPEL :

## I. Pendahuluan

### 1.1 Latar Belakang
Manajemen inventaris barang merupakan aspek penting dalam operasional berbagai organisasi, institusi pendidikan, atau perusahaan. Sistem peminjaman barang yang efisien dapat meningkatkan produktivitas dan mengurangi kesalahan administratif. Dalam era digital ini, proses peminjaman, pengembalian, dan tracking barang seharusnya dapat dilakukan dengan lebih efisien, terukur, dan transparan.

Pada proyek ini, kami membangun Aplikasi Inventaris Barang berbasis web dengan fitur sistem rental gratis, di mana pengguna dapat meminjam barang tanpa biaya sewa. Namun, jika barang dikembalikan melebihi jadwal pengembalian yang telah ditentukan, maka akan dikenakan denda per hari untuk setiap hari keterlambatan. Denda dihitung secara otomatis berdasarkan: **Denda = Jumlah Hari Terlambat × Denda Per Hari × Jumlah Barang yang Dipinjam**.

### 1.2 Tujuan
Tujuan utama dari proyek ini adalah:

- Mempermudah proses peminjaman dan pengembalian barang secara online.
- Mengelola stok barang secara real-time dan menampilkan ketersediaan barang.
- Memberikan sistem rental yang gratis untuk memudahkan pengguna meminjam barang.
- Menerapkan sistem denda otomatis untuk keterlambatan pengembalian barang.
- Meningkatkan efisiensi administrasi pihak admin dalam mengelola peminjaman, pengembalian, dan denda.
- Memberikan transparansi kepada pengguna mengenai jadwal pengembalian dan perhitungan denda.

### 1.3 Ruang Lingkup
Ruang lingkup proyek ini mencakup:

- Pembuatan aplikasi web untuk peminjaman dan pengembalian barang.
- Manajemen stok dan ketersediaan barang.
- Sistem perhitungan denda otomatis berdasarkan keterlambatan.
- Interface admin untuk mengelola barang, melihat peminjaman, dan memproses pengembalian.
- Interface user untuk melihat katalog barang, melakukan peminjaman, dan memonitor status peminjaman.

## II. Deskripsi Proyek

### 2.1 Fitur Utama

1. **Katalog Barang**: Pengguna dapat melihat daftar barang yang tersedia untuk dipinjam, beserta foto, jumlah stok, dan denda per hari untuk keterlambatan.

2. **Peminjaman Barang**: Pengguna dapat melakukan peminjaman barang dengan menentukan:
   - Barang yang akan dipinjam
   - Jumlah barang yang dipinjam
   - Lama peminjaman (dalam hari)
   - Sistem secara otomatis akan menghitung tanggal pengembalian yang dijadwalkan

3. **Status Peminjaman**: Pengguna dapat melihat status peminjaman mereka, yaitu:
   - **Dipinjam**: Barang sedang dalam proses peminjaman oleh pengguna
   - **Dikembalikan**: Barang telah dikembalikan (dengan atau tanpa denda)

4. **Perhitungan Denda Otomatis**: Sistem secara otomatis menghitung denda keterlambatan:
   - Jika barang dikembalikan tepat waktu atau lebih awal = Tidak ada denda (Rp 0)
   - Jika barang dikembalikan terlambat = Denda otomatis = (Tanggal Kembali Aktual - Tanggal Kembali Dijadwalkan) × Denda Per Hari × Jumlah Barang

5. **Manajemen Admin**: Admin sekolah/organisasi dapat:
   - Menambah, mengedit, dan menghapus barang inventaris
   - Menetapkan denda per hari untuk setiap barang
   - Melihat daftar peminjaman yang sedang berjalan
   - Memproses pengembalian barang dan melihat denda yang tertagih
   - Melihat total denda terkumpul per hari

6. **Monitoring Dashboard**: Admin dapat melihat ringkasan:
   - Total jenis barang
   - Total stok barang tersedia
   - Jumlah barang yang habis (stok = 0)
   - Jumlah peminjaman yang sedang aktif
   - Jumlah peminjaman yang sudah dikembalikan
   - Total denda yang terkumpul pada hari tersebut

### 2.2 Teknologi yang Digunakan

- **Frontend**: HTML, CSS, JavaScript, Tailwind CSS
- **Backend**: PHP dengan Framework Laravel 13
- **Database**: MySQL
- **ORM**: Eloquent (Laravel)
- **Templating**: Blade Template Engine
- **Authentication**: Sistem login dengan role berbeda (Admin & User)
- **Build Tool**: Vite
- **Testing**: PHPUnit

### 2.3 Arsitektur Sistem

```
┌─────────────────────────────────────────────────┐
│          User/Browser Interface                 │
├─────────────────────────────────────────────────┤
│  Frontend (Blade Template + Tailwind + JS)      │
├─────────────────────────────────────────────────┤
│  Laravel Router (routes/web.php)                │
├─────────────────────────────────────────────────┤
│  Controllers                                    │
│  - BarangController (CRUD Barang)               │
│  - PeminjamanController (Rental & Return)       │
│  - DashboardController (Admin Dashboard)        │
├─────────────────────────────────────────────────┤
│  Models (Eloquent ORM)                          │
│  - Barang (Item katalog)                        │
│  - Peminjaman (Transaksi rental)                │
│  - User (Pengguna sistem)                       │
├─────────────────────────────────────────────────┤
│  Database (MySQL)                               │
│  - barang table                                 │
│  - peminjaman table                             │
│  - users table                                  │
└─────────────────────────────────────────────────┘
```

### 2.4 Alur Sistem

**Alur Peminjaman:**
1. User membuka aplikasi dan melihat katalog barang
2. User memilih barang dan durasi peminjaman
3. Sistem menghitung jadwal pengembalian otomatis (hari ini + lama_hari)
4. Rental GRATIS - tidak ada biaya sewa
5. Admin memproses persetujuan peminjaman
6. Barang masuk status "Dipinjam"

**Alur Pengembalian:**
1. User mengembalikan barang
2. Sistem membandingkan tanggal kembali aktual dengan jadwal pengembalian
3. Jika tepat waktu: Denda = Rp 0 ✓
4. Jika terlambat: Denda = (selisih hari) × denda_per_hari × jumlah_pinjam
5. Admin memproses pengembalian dan denda tercatat otomatis
6. Barang masuk status "Dikembalikan"
7. Total denda terakumulasi di dashboard admin

### 2.5 Proses Pengembangan
Proyek ini dimulai dengan analisis kebutuhan dan dikembangkan secara iteratif dengan tahapan sebagai berikut:

- Analisis Kebutuhan (2 jam)
- Desain Database & UI (4 jam)
- Pengembangan Aplikasi Backend (6 jam)
- Pengembangan Interface Frontend (4 jam)
- Testing & Bug Fixing (3 jam)
- Optimasi & Dokumentasi (2 jam)

**Total Waktu Pengembangan: ~21 jam**

## III. Hasil yang Dicapai

### 3.1 Halaman User - Katalog Barang
Pada halaman utama user, ditampilkan daftar lengkap barang yang tersedia untuk dipinjam, beserta:
- Foto barang
- Nama barang
- Jumlah stok tersedia
- Denda per hari untuk keterlambatan
- Tombol aksi untuk peminjaman

**Fitur:**
- Pencarian dan filter barang
- Sorting berdasarkan nama atau stok
- Status real-time ketersediaan barang

### 3.2 Halaman User - Formulir Peminjaman
Ketika user memilih barang untuk dipinjam, form peminjaman menampilkan:
- Nama barang dan foto
- Denda per hari barang tersebut
- Input untuk jumlah barang yang akan dipinjam
- Input untuk lama peminjaman (dalam hari)
- Informasi otomatis: "✅ Sewa Gratis" (tampil hijau)
- Jadwal pengembalian yang dihitung otomatis
- Informasi denda: "Rp [denda]/hari × hari terlambat" (jika terlambat)

**Fitur JavaScript:**
- Update jadwal pengembalian secara real-time saat durasi berubah
- Validasi input
- Preview informasi denda dan jadwal

### 3.3 Halaman User - Dashboard & Riwayat Peminjaman
User dapat melihat:
- Daftar barang dengan denda per hari
- Riwayat peminjaman dengan status:
  - Status: "Dipinjam" atau "Dikembalikan"
  - Tanggal peminjaman
  - Tanggal kembali yang dijadwalkan
  - Biaya sewa: Ditampilkan "Gratis" (warna hijau)
  - Denda (jika ada keterlambatan)

### 3.4 Halaman Admin - Dashboard
Admin dapat melihat ringkasan operasional:

**Statistics Cards:**
- **Jenis Barang**: Total jumlah jenis barang dalam sistem
- **Total Stok**: Jumlah total keseluruhan barang
- **Stok Habis**: Jumlah barang dengan stok = 0
- **Sedang Dipinjam**: Peminjaman yang masih aktif
- **Dikembalikan**: Peminjaman yang sudah selesai
- **Total Denda Hari Ini**: Total denda yang terkumpul pada hari ini

**Tabel Peminjaman Terbaru:**
- Tampil 5 peminjaman terbaru dengan detail lengkap
- Link untuk melihat semua peminjaman

### 3.5 Halaman Admin - Manajemen Barang
Admin dapat mengelola katalog barang:

**Fitur:**
- Lihat daftar semua barang
- Tambah barang baru (input: nama, jumlah, kondisi, denda per hari, foto)
- Edit barang (update informasi dan denda per hari)
- Hapus barang
- Filter dan pencarian barang
- Upload foto barang

**Form Input:**
- Nama Barang
- Jumlah Stok
- Kondisi Barang (dropdown)
- Denda Per Hari (untuk keterlambatan)
- Foto Barang

### 3.6 Halaman Admin - Daftar Peminjaman
Admin dapat melihat semua peminjaman:

**Tampilan Tabel:**
- Nama Peminjam
- Barang yang Dipinjam
- Jumlah dan Lama Hari
- Tanggal Peminjaman
- Jadwal Pengembalian
- Status Peminjaman
- Aksi

**Fitur:**
- Filter berdasarkan status (Dipinjam/Dikembalikan)
- Search peminjaman berdasarkan nama user atau barang
- Sorting berdasarkan tanggal
- Modal preview detail peminjaman

### 3.7 Halaman Admin - Proses Pengembalian
Admin memproses pengembalian barang:

**Alur Proses:**
1. Admin membuka halaman pengembalian
2. Pilih peminjaman yang akan dikembalikan
3. Masukkan kondisi pengembalian barang
4. **Sistem otomatis menghitung denda** berdasarkan:
   - Tanggal kembali aktual vs jadwal
   - Denda per hari × hari terlambat × jumlah barang
5. Admin melihat preview denda yang dihitung otomatis
6. Konfirmasi pengembalian
7. Status berubah ke "Dikembalikan" dan denda tersimpan

**Display Denda Otomatis:**
- Box biru dengan label "Denda Keterlambatan Otomatis"
- Menampilkan: "Rp [total_denda]" (jika ada keterlambatan)
- Menampilkan: "Rp 0 - Tepat Waktu" (jika tidak ada keterlambatan)

### 3.8 Sistem Perhitungan Denda
Perhitungan denda dilakukan sepenuhnya otomatis tanpa input manual:

**Formula:**
```
Denda = (Tanggal Kembali Aktual - Jadwal Kembali) × Denda Per Hari × Jumlah Barang
```

**Method di Model Peminjaman:**
```php
public function hitungDendaKeterlambatan($tanggalKembaliAktual = null): float
{
    // Hitung selisih hari terlambat
    // Jika terlambat = hitung denda
    // Jika tepat waktu = 0
    // Return: hari_terlambat × denda_per_hari × jumlah_pinjam
}
```

### 3.9 Database Schema
**Tabel Barang:**
```
- id_barang (Primary Key)
- nama_barang
- jumlah
- kondisi_barang
- denda_per_hari (decimal - untuk keterlambatan)
- foto (path file gambar)
- created_at, updated_at
```

**Tabel Peminjaman:**
```
- id_pinjam (Primary Key)
- id_user (Foreign Key)
- id_barang (Foreign Key)
- jumlah_pinjam
- lama_hari
- tanggal_pinjam
- tanggal_kembali (jadwal/rencana)
- status_pinjam (dipinjam/dikembalikan)
- denda (decimal - hasil kalkulasi otomatis)
- kondisi_pengembalian
- created_at, updated_at
```

**Tabel Users:**
```
- id (Primary Key)
- name
- email
- password
- role (admin/user)
- phone_number
- created_at, updated_at
```

## IV. Kesimpulan

### Keberhasilan Implementasi

Aplikasi Inventaris Barang ini berhasil dibangun dengan fitur-fitur utama yang mencakup:

1. ✅ **Sistem Rental Gratis**: Pengguna dapat meminjam barang tanpa biaya sewa apapun
2. ✅ **Perhitungan Denda Otomatis**: Denda keterlambatan dihitung secara otomatis tanpa input manual
3. ✅ **Real-time Inventory Management**: Stok barang dapat dipantau secara real-time
4. ✅ **Interface User-Friendly**: Tampilan yang intuitif untuk user dan admin
5. ✅ **Manajemen Admin Lengkap**: Admin dapat mengelola barang, peminjaman, dan pengembalian
6. ✅ **Tracking Peminjaman**: User dapat melihat riwayat dan status peminjaman mereka
7. ✅ **Laporan Denda**: Dashboard menampilkan total denda terkumpul per hari

### Keunggulan Sistem

- **Otomasi Penuh**: Perhitungan denda sepenuhnya otomatis, mengurangi kesalahan manual
- **Transparency**: User dapat melihat jadwal pengembalian dan estimasi denda
- **Efficiency**: Admin dapat fokus pada verifikasi kondisi barang, bukan perhitungan
- **Scalability**: Dapat dengan mudah menambah barang dan pengguna baru
- **Data Integrity**: Database MySQL menjamin integritas data
- **Security**: Sistem autentikasi dan role-based access control (Admin & User)

### Saran Pengembangan Lanjutan

1. **Fitur Pembayaran Online**: Tambahkan integrasi pembayaran (Midtrans, PayPal, dll) untuk memudahkan pembayaran denda
2. **Notifikasi Email/WhatsApp**: Kirim notifikasi ke user ketika mendekati jadwal pengembalian atau ketika ada denda
3. **Laporan & Analytics**: Tambahkan laporan detail tentang barang paling sering dipinjam, user dengan denda terbanyak, dll
4. **QR Code Integration**: Gunakan QR code untuk check-in dan check-out barang (faster process)
5. **Mobile App**: Kembangkan aplikasi mobile untuk meningkatkan aksesibilitas
6. **Export Data**: Fitur export data ke Excel atau PDF untuk laporan
7. **Advanced Search**: Tambah filter dan pencarian advanced dengan multiple criteria
8. **Approval Workflow**: Admin approval sebelum peminjaman dikonfirmasi
9. **Return Prediction**: Notifikasi otomatis X hari sebelum jadwal pengembalian
10. **Audit Log**: Catat semua aktivitas untuk keperluan audit

## V. Lampiran

### 5.1 Struktur Folder Proyek
```
aplikasi-inventaris-barang/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── BarangController.php
│   │       ├── PeminjamanController.php
│   │       └── DashboardController.php
│   └── Models/
│       ├── Barang.php
│       ├── Peminjaman.php
│       └── User.php
├── database/
│   └── migrations/
│       ├── *_create_users_table.php
│       ├── *_create_barang_table.php
│       ├── *_create_peminjaman_table.php
│       └── *_add_denda_per_hari_to_barang_table.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php
│       │   ├── barang/
│       │   ├── peminjaman/
│       │   └── pengembalian/
│       └── user/
│           ├── dashboard.blade.php
│           └── peminjaman/
├── routes/
│   └── web.php
└── public/
    └── storage/ (untuk upload foto barang)
```

### 5.2 Cara Instalasi & Setup

**Prasyarat:**
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & npm (untuk Vite)

**Langkah Instalasi:**
1. Clone repository: `git clone [repo-url]`
2. Install dependencies: `composer install && npm install`
3. Copy .env: `cp .env.example .env`
4. Generate key: `php artisan key:generate`
5. Setup database di .env
6. Run migrations: `php artisan migrate`
7. Seed data (opsional): `php artisan db:seed`
8. Build assets: `npm run build`
9. Jalankan server: `php artisan serve`

### 5.3 Fitur-Fitur Utama yang Diimplementasikan

| Fitur | Admin | User | Status |
|-------|-------|------|--------|
| Lihat Katalog Barang | ✅ | ✅ | ✅ Implemented |
| Tambah/Edit/Hapus Barang | ✅ | ❌ | ✅ Implemented |
| Peminjaman Barang | ✅ | ✅ | ✅ Implemented |
| Lihat Status Peminjaman | ✅ | ✅ | ✅ Implemented |
| Proses Pengembalian | ✅ | ❌ | ✅ Implemented |
| Perhitungan Denda Otomatis | ✅ (Otomatis) | ✅ (Lihat) | ✅ Implemented |
| Dashboard & Analytics | ✅ | ❌ | ✅ Implemented |
| Riwayat Peminjaman | ✅ | ✅ | ✅ Implemented |
| Upload Foto Barang | ✅ | ❌ | ✅ Implemented |

### 5.4 Daftar Pustaka & Referensi

1. Laravel Documentation - https://laravel.com/docs
2. Laravel Eloquent ORM - https://laravel.com/docs/eloquent
3. Blade Template Engine - https://laravel.com/docs/blade
4. Tailwind CSS - https://tailwindcss.com/docs
5. MySQL Documentation - https://dev.mysql.com/doc/
6. PHP Documentation - https://www.php.net/manual/
7. Carbon Date Library - https://carbon.nesbot.com/

### 5.5 Developer Notes

- **Database**: Semua field tanggal menggunakan tipe `date` dan di-cast sebagai Carbon instance
- **Denda Calculation**: Menggunakan method `diffInDays()` dari Carbon untuk menghitung selisih hari
- **Status Peminjaman**: Hanya ada 2 status: "dipinjam" dan "dikembalikan"
- **Rental Gratis**: Tidak ada kolom `harga_per_hari` di tabel barang, hanya `denda_per_hari`
- **Stok Management**: Stok otomatis berkurang saat peminjaman disetujui dan bertambah saat pengembalian diproses

---

**Dokumen ini siap untuk di-print dan di-submit sebagai laporan dokumentasi proyek.**

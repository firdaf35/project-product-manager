# Florist Product Manager
Dokumentasi Tugas Akhir Praktikum Pemrograman Web

---

## 1. Deskripsi Proyek
Florist Product Manager adalah aplikasi web berbasis PHP Native dan database MySQL yang dirancang untuk mengelola data inventaris toko bunga (Florist). Aplikasi ini dibuat tanpa menggunakan framework eksternal untuk menerapkan konsep dasar pemrograman web modern, pemisahan struktur file yang rapi, serta prinsip keamanan web dasar (Security First).

---

## 2. Tujuan Proyek
1. Memenuhi tugas akhir praktikum Pemrograman Web.
2. Mengimplementasikan operasi dasar CRUD (Create, Read, Update, Delete) data produk secara dinamis.
3. Menerapkan standar keamanan dasar web untuk mencegah celah SQL Injection, Cross-Site Scripting (XSS), dan Cross-Site Request Forgery (CSRF).
4. Menyediakan antarmuka pengguna yang bersih, elegan, dan responsif menggunakan CSS Grid serta Flexbox.

---

## 3. Struktur Proyek

product-manager/
├── assets/
│   └── style.css          # Styling CSS 
├── config/
│   └── database.php       # Koneksi database menggunakan PHP Data Objects (PDO)
├── includes/
│   └── csrf.php           # Helper proteksi token CSRF dan fungsi sanitasi XSS
├── database.sql           # Schema tabel & data awal (sample dataset florist)
├── index.php              # Halaman utama (Daftar Produk & Pencarian GET)
├── create.php             # Form Tambah Produk (Validasi Server-Side & Anti-Duplikasi)
├── edit.php               # Form Edit Produk berbasis ID
├── delete.php             # Action Hapus Produk (Proteksi POST & CSRF)
└── README.md              # Dokumentasi resmi proyek

---

## 4. Kriteria dan Fitur Utama

| No | Fitur | Deskripsi Implementasi |
| :--- | :--- | :--- |
| 1 | Create | Menambah data bunga baru dengan validasi panjang nama (min. 3 karakter), harga/stok non-negatif, dan cek duplikasi nama. |
| 2 | Read | Menampilkan katalog produk dalam bentuk Card Layout responsif. |
| 3 | Update | Mengubah data produk berbasis ID dengan tetap menerapkan aturan validasi. |
| 4 | Delete | Menghapus data produk secara aman menggunakan metode POST dan verifikasi token. |
| 5 | Search & Filter | Fitur pencarian kata kunci berdasarkan nama atau kategori produk via parameter GET. |
| 6 | Responsive Design | Tampilan fleksibel yang otomatis menyesuaikan ukuran layar PC maupun smartphone. |

---

## 5. Refleksi Keamanan Aplikasi

| No | Sisi Keamanan | Jenis Ancaman | Metode Proteksi |
| :--- | :--- | :--- | :--- |
| 1 | Input Validation | Data Invalid / Duplikat | Validasi server-side membatasi nama produk minimal 3 karakter, harga/stok tidak negatif, dan mencegah nama ganda. |
| 2 | Database Query | SQL Injection (SQLi) | Menggunakan PDO Prepared Statements (`$pdo->prepare()`) untuk seluruh query SQL. |
| 3 | Output Rendering | Cross-Site Scripting (XSS) | Seluruh variabel output dirender menggunakan fungsi `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')`. |
| 4 | Request Control | Cross-Site Request Forgery (CSRF) | Menggunakan token acak `$_SESSION['csrf_token']` pada setiap aksi muatan POST. |

---

## 6. Checklist Pengujian Demo

| No | Indikator Pengujian | Ekspektasi Hasil | Status |
| :--- | :--- | :--- | :---: |
| 1 | Tambah Produk Valid | Data tersimpan dan langsung muncul di katalog utama. | PASSED |
| 2 | Input Nama < 3 Karakter | Ditolak oleh sistem dengan pesan peringatan. | PASSED |
| 3 | Input Harga / Stok Negatif | Ditolak oleh sistem dengan pesan error validasi. | PASSED |
| 4 | Refresh Pasca-Create | Mencegah form resubmission atau duplikasi data. | PASSED |
| 5 | Injection HTML (`<b>Promo</b>`) | Di-escape dan ditampilkan sebagai teks biasa (aman XSS). | PASSED |
| 6 | Pengujian Tampilan Mobile | Kartu produk menyusun otomatis ke bawah tanpa scrollbar horizontal. | PASSED |

---

## 7. Panduan Menjalankan Proyek (XAMPP)

1. Jalankan modul Apache dan MySQL melalui XAMPP Control Panel.
2. Salin folder proyek ini ke direktori htdocs: `C:/xampp/htdocs/product-manager`.
3. Buka phpMyAdmin (`http://localhost/phpmyadmin`) dan buat database bernama `db_product_manager`.
4. Impor file `database.sql` ke dalam database tersebut.
5. Akses aplikasi melalui browser di alamat: `http://localhost/product-manager`.
6.
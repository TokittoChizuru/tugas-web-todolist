# To-Do List Project

## Deskripsi Proyek
To-Do List adalah aplikasi manajemen tugas berbasis web yang memungkinkan pengguna untuk mengatur daftar tugas yang harus diselesaikan dengan fitur menambahkan, mengedit, melihat, dan menghapus tugas mereka. Aplikasi ini dirancang untuk membantu pengguna mengatur dan melacak tugas-tugas mereka dengan lebih efisien. Proyek ini menggunakan PHP, MySQL, HTML, CSS, dan JavaScript.

---
## Kelompok 2 
**Anggota:**
1. Nur Kholivah(4123009)
2. Muhammad Genio Brillian(4123009)
3. Dio Ananda Maulana Wijaya(4123031)

---

## Fitur Utama
1. **Registrasi dan Login:**
   - Pengguna dapat membuat akun baru untuk menggunakan aplikasi.
   - Pengguna harus login untuk mengakses dashboard tugas mereka.

2. **Dashboard:**
   - Menampilkan daftar tugas yang dimiliki oleh pengguna.
   - Fitur tambah tugas langsung dari dashboard.
   - Fitur hapus tugas dengan konfirmasi.
   - Notifikasi berupa pesan pop-up untuk operasi yang berhasil (tambah, edit, atau hapus tugas).

3. **Tambah Tugas:**
   - Pengguna dapat menambahkan tugas baru melalui form di dashboard.
   - Data tugas meliputi: nama tugas, deskripsi, dan tanggal deadline.

4. **Edit Tugas:**
   - Pengguna dapat memperbarui informasi tugas yang telah ditambahkan sebelumnya.
   - Form edit menampilkan data tugas sebelumnya untuk diedit.

5. **Hapus Tugas:**
   - Tugas dapat dihapus dengan konfirmasi untuk menghindari penghapusan yang tidak disengaja.

6. **Status Tugas:**
   - Pengguna dapat mengatur status tugas (Belum atau Selesai).
   - Status ditampilkan di dashboard.

---

## Struktur Folder
```
project_root/
├── assets/
│   └── styles/           # Folder untuk file CSS
│      ├── dashboard.css
│      ├── edit_tugas.css
│      ├── login.css
|      ├── index.css
|      └── register.css
│   
├── db/
│   └── to_do_list_db.sql # File yang berisi database
|
├── includes/
│   ├── koneksi.php       # File untuk koneksi database
│   ├── auth.php          # File untuk fungsi autentikasi
│   ├── header.php        # Header HTML yang digunakan bersama
│   └── footer.php        # Footer HTML yang digunakan bersama
│   
├── add_tugas.php         # File logika untuk menambah tugas
├── dashboard.php         # Halaman utama setelah berhasil login
├── delete_tugas.php      # File logika untuk menghapus tugas
├── edit_tugas.php        # File logika untuk mengedit tugas
├── index.php             # Halaman utama
├── login.php             # Halaman untuk login
├── logout.php            # File logika untuk logout
├── register.php          # Halaman untuk registrasi
└── README.md             # File dokumentasi proyek
```

---

## Teknologi yang Digunakan
- **Bahasa Pemrograman:** PHP, HTML, CSS, JavaScript
- **Database:** MySQL
- **Framework:** Tidak ada (PHP native)
- **Library Tambahan:** Tidak ada (pure CSS dan JS)

---

## Instalasi
1. **Clone Repository:**
   ```bash
   git clone <repository_url>
   ```

2. **Konfigurasi Database:**
   - Buat database baru di MySQL (misalnya: `todo_list`).
   - Import file `todo_list.sql` (jika tersedia) untuk membuat tabel otomatis.
   - Pastikan file `includes/koneksi.php` sudah diisi dengan kredensial database Anda:
     ```php
     $host = 'localhost';
     $user = 'root';
     $password = '';
     $database = 'todo_list';
     ```

3. **Konfigurasi Server Lokal:**
   - Tempatkan proyek ini di folder server lokal Anda (misalnya: `htdocs` jika menggunakan XAMPP).
   - Akses melalui browser di alamat: `http://localhost/project_folder`.

4. **Mulai Aplikasi:**
   - Buat akun baru melalui halaman login atau gunakan akun yang sudah ada.
   - Mulai menambahkan, mengedit, dan mengelola tugas Anda!


---

## Kontribusi
Jika Anda ingin berkontribusi pada proyek ini:
1. Fork repository ini.
2. Buat branch baru untuk fitur atau perbaikan Anda.
3. Lakukan pull request ke branch utama.

---

## Lisensi
Proyek ini bebas digunakan untuk tujuan pembelajaran atau pengembangan. Anda dapat memodifikasi dan mendistribusikan ulang proyek ini sesuai kebutuhan.

---

**Dikembangkan oleh [Kelompok 2] - 2025**

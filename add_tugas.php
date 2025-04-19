<?php
session_start();
require 'includes/koneksi.php'; // Koneksi ke database
require 'includes/auth.php'; // Memuat fungsi autentikasi

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

// Ambil data user dari session
$id_user = $_SESSION['id_user'];

// Proses tambah tugas jika form dikirimkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = trim($_POST['nama_tugas']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_deadline = trim($_POST['tanggal_deadline']);

    // Validasi data input
    if (!empty($nama_tugas) && !empty($tanggal_deadline)) {
        // Query untuk menambahkan tugas baru ke database
        $insert_query = "INSERT INTO tugas (id_user, nama_tugas, deskripsi, tanggal_deadline, tanggal_dibuat, status_tugas) 
                        VALUES (?, ?, ?, ?, NOW(), 'belum')";
        $insert_stmt = $koneksi->prepare($insert_query);
        $insert_stmt->bind_param('isss', $id_user, $nama_tugas, $deskripsi, $tanggal_deadline);

        // Eksekusi query dan cek apakah berhasil
        if ($insert_stmt->execute()) {
            header('Location: tambah.php?status=tambah_berhasil');
            exit;
        } else {
            header('Location: tambah.php?status=tambah_gagal');
            exit;
        }        
    } else {
        $error = "Nama tugas dan tanggal deadline wajib diisi.";
    }
} else {
    header('Location: tambah.php');
    exit;
}
?>

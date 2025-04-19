<?php
session_start();
require 'includes/koneksi.php'; // Koneksi ke database
require 'includes/auth.php'; // Memuat fungsi autentikasi

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

$id_user = $_SESSION['id_user']; // ID user dari session

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id_tugas = $_POST['hapus_id'];
    $delete_query = "DELETE FROM tugas WHERE id_tugas = ? AND id_user = ?";
    $delete_stmt = $koneksi->prepare($delete_query);
    $delete_stmt->bind_param('ii', $id_tugas, $id_user);

    if ($delete_stmt->execute()) {
        header('Location: dashboard.php?status=deleted');
        exit;
    } else {
        echo "Gagal menghapus tugas.";
    }
}
?>
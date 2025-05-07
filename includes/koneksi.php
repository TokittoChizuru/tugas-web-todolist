<?php
// Konfigurasi database
$host = "0.0.0.0:3306";
$user = "root";
$password = "root";
$database = "harihebatku";

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi 
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>

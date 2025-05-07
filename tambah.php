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
$nama = $_SESSION['nama'];
$username = $_SESSION['username'];

// Ambil tugas dari database untuk user yang sedang login
$query = "SELECT * FROM tugas WHERE id_user = ? ORDER BY tanggal_deadline ASC";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();
$tugas = $result->fetch_all(MYSQLI_ASSOC);
?>



<?php
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = 'Tugas berhasil ditambahkan.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Task</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/tambah.css">
</head>


<body>
    <button class="mobile-menu-toggle is-hidden-desktop">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="dashboard">
        <!-- Sidebar -->
        <?php include("sidebar.php"); ?>

        <!-- Main Content -->
        <?php if (isset($_GET['status'])): ?>
            <script>
                <?php if ($_GET['status'] === 'tambah_berhasil'): ?>
                    alert('Tugas berhasil ditambahkan!');
                <?php elseif ($_GET['status'] === 'tambah_gagal'): ?>
                    alert('Gagal menambahkan tugas. Silakan coba lagi.');
                <?php elseif ($_GET['status'] === 'edit_berhasil'): ?>
                    alert('Tugas berhasil diperbarui!');
                <?php elseif ($_GET['status'] === 'edit_gagal'): ?>
                    alert('Gagal memperbarui tugas. Silakan coba lagi.');
                <?php endif; ?>
            </script>
        <?php endif; ?>

        <div class="main-content" id="mainContent">
            <div class="task-form">
                <h1>Tambah Task</h1>

                <form method="POST" action="add_tugas.php">
                    <div class="field">
                        <label class="label">Masukan Judul</label>
                        <div class="control has-icons-left">
                            <input class="input" id="nama_tugas" name="nama_tugas" type="text" placeholder="Judul task">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Masukan Deskripsi</label>
                        <div class="control">
                            <textarea class="textarea" id="deskripsi" name="deskripsi"
                                placeholder="Deskripsi task"></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Tanggal</label>
                        <div class="control has-icons-left">
                            <input class="input datepicker" id="tanggal_deadline" name="tanggal_deadline" type="date"
                                placeholder="DD/MM/YYYY">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button type="submit" class="create-btn">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                                <span>Create Task</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const mobileToggleBtn = document.querySelector('.mobile-menu-toggle');
            const toggleDarkMode = document.getElementById('darkModeToggle');
            const icon = document.getElementById('darkModeIcon');
            const body = document.body;

            function setIcon(isDark) {
                icon.innerHTML = isDark
                    ? `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            d="M21.75 12.003c0 5.385-4.365 9.75-9.75 9.75a9.753 9.753 0 01-8.427-4.97
                            0.75 0.75 0 01.477-1.093 7.501 7.501 0 004.66-3.94
                            7.5 7.5 0 00-.334-6.874 0.75 0.75 0 01.97-1.06
                            9.75 9.75 0 0112.404 8.187z" />
                    </svg>`
                    : `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364l-1.06-1.06
                            M6.697 6.697l-1.06-1.06m12.727 0l-1.06 1.06M6.697 17.303l-1.06 1.06
                            M12 8.25A3.75 3.75 0 1112 15a3.75 3.75 0 010-6.75z" />
                    </svg>`;

                // Set icon color
                icon.style.color = isDark ? 'white' : 'inherit';
            }

            // Load state from localStorage
            const darkModeOn = localStorage.getItem('dark-mode') === 'enabled';
            if (darkModeOn) {
                body.classList.add('dark-mode');
                setIcon(true);
            } else {
                setIcon(false);
            }

            // Dark mode toggle
            toggleDarkMode.addEventListener('click', function () {
                const isDark = body.classList.toggle('dark-mode');
                localStorage.setItem('dark-mode', isDark ? 'enabled' : 'disabled');
                setIcon(isDark);
            });

            // Desktop toggle functionality
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');

                // Rotate the arrow icon
                const arrowIcon = toggleBtn.querySelector('svg');
                if (sidebar.classList.contains('collapsed')) {
                    arrowIcon.style.transform = 'rotate(180deg)';
                } else {
                    arrowIcon.style.transform = 'rotate(0deg)';
                }
            });

            // Mobile toggle functionality
            mobileToggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('open');
            });

            // Close sidebar when clicking on main content (mobile only)
            if (window.innerWidth <= 768) {
                mainContent.addEventListener('click', function () {
                    if (sidebar.classList.contains('open')) {
                        sidebar.classList.remove('open');
                    }
                });
            }

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('open');
                }
            });
        });
    </script>
</body>

</html>
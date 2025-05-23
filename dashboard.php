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

$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengguna';
$nama_tugas = isset($_SESSION['nama_tugas']) ? $_SESSION['nama_tugas'] : 'Judul';
$deskripsi = isset($_SESSION['deskripsi']) ? $_SESSION['deskripsi'] : 'deskripsi';
$status_tugas = isset($_SESSION['status_tugas']) ? $_SESSION['status_tugas'] : 'deadline';
?>

<?php
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'deleted') {
        $message = 'Tugas berhasil dihapus.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
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
        <div class="main-content" id="mainContent">
            <br />
            <br />
            <div class="user-greeting">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </span>
                <h1>Hai, <?php echo ($username); ?></h1>
            </div>


            <div class="task-list">
                <h2>Task</h2>

                <?php if (!empty($tugas)): ?>
                    <?php foreach ($tugas as $t): ?>

                        <div class="task-item">
                            <div class="task-title"><?php echo htmlspecialchars($t['nama_tugas']); ?></div>
                            <div class="task-description"><?php echo htmlspecialchars($t['deskripsi']); ?></div>
                            <div class="task-date"><?php echo htmlspecialchars($t['tanggal_deadline']); ?></div>
                            <div class="task-status">
                                <p><b>Status Tugas : </b><?php echo htmlspecialchars($t['status_tugas']); ?></p>
                            </div>

                            <?php if ($status_tugas == 'belum'): ?>
                                <a href="edit_tugas.php?id_tugas=<?php echo $t['id_tugas']; ?>"
                                    class="button is-small is-primary is-light">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </span>
                                    <span>Edit</span>
                                </a>
                            <?php else: ?>
                                <a href="edit_tugas.php?id_tugas=<?php echo $t['id_tugas']; ?>"
                                    class="button is-small is-info is-light">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </span>
                                    <span>Edit</span>
                                </a>
                            <?php endif; ?>

                            <form method="POST" action="delete_tugas.php" style="display: inline;">
                                <input type="hidden" name="hapus_id" value="<?php echo $t['id_tugas']; ?>">
                                <button type="submit" class="button is-small is-danger is-light"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">
                                    <span class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </span>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">Belum ada tugas</td>
                        </tr>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const mobileToggleBtn = document.querySelector('.mobile-menu-toggle');
            const taskList = document.querySelector('.task-list');
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
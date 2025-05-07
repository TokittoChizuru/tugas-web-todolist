<?php
session_start();
require 'includes/koneksi.php';
require 'includes/auth.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];
$error = '';
$success = '';

// Get current user data
$query = "SELECT * FROM users WHERE id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($nama) || empty($email)) {
        $error = "Nama dan email wajib diisi!";
    } else {
        // Check if email is already taken by another user
        $check_email = "SELECT id_user FROM users WHERE email = ? AND id_user != ?";
        $stmt = $koneksi->prepare($check_email);
        $stmt->bind_param('si', $email, $id_user);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            $error = "Email sudah digunakan oleh user lain!";
        } else {
            // Prepare update query
            if (!empty($password)) {
                // Update with password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $update_query = "UPDATE users SET nama = ?, email = ?, password = ? WHERE id_user = ?";
                $stmt = $koneksi->prepare($update_query);
                $stmt->bind_param('sssi', $nama, $email, $hashed_password, $id_user);
            } else {
                // Update without password
                $update_query = "UPDATE users SET nama = ?, email = ? WHERE id_user = ?";
                $stmt = $koneksi->prepare($update_query);
                $stmt->bind_param('ssi', $nama, $email, $id_user);
            }

            if ($stmt->execute()) {
                // Update session data
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                $success = "Profil berhasil diperbarui!";

                // Refresh user data
                $query = "SELECT * FROM users WHERE id_user = ?";
                $stmt = $koneksi->prepare($query);
                $stmt->bind_param('i', $id_user);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            } else {
                $error = "Gagal memperbarui profil: " . $koneksi->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="assets/css/profil.css">
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
            <div class="profile-card">
                <h1>
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </span>
                    <span>Profile</span>
                </h1>

                <?php if ($error): ?>
                    <div class="notification is-danger is-light">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="notification is-success is-light">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="field">
                        <label class="label">Nama</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" name="nama"
                                value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password Baru (biarkan kosong jika tidak ingin mengubah)</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="password" placeholder="Masukkan password baru">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </div>
                        <p class="password-note">Minimal 8 karakter</p>
                    </div>

                    <div class="buttons">
                        <button type="submit" name="update_profile" class="button is-primary">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                </svg>
                            </span>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="dashboard.php" class="button is-light">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                                </svg>
                            </span>
                            <span>Kembali</span>
                        </a>
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
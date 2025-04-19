<?php
session_start();
require 'includes/koneksi.php';
require 'includes/auth.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

$id_user = $_SESSION['id_user'];
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
    <style>
        :root {
            --primary: #76CBBB;
            --primary-light: #CFFFF6;
            --background: #FFFFFF;
        }
        
        body {
            background-color: var(--primary-light);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: all 0.3s ease;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--background);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar.collapsed {
            width: 70px;
            overflow: hidden;
        }
        
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .user-greeting h1 {
            display: none;
        }
        
        .sidebar.collapsed .menu-list a {
            justify-content: center;
        }
        
        .sidebar.collapsed .menu-list .icon {
            margin-right: 0;
        }
        
        .toggle-sidebar {
            position: absolute;
            top: 1rem;
            right: -15px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
            transition: all 0.3s ease;
        }
        
        .profile-card {
            background-color: var(--background);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            margin: 0 auto;
        }
        
        .profile-card h1 {
            color: var(--primary);
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .profile-card h1 .icon {
            margin-right: 0.75rem;
        }
        
        .field:not(:last-child) {
            margin-bottom: 1.5rem;
        }
        
        .label {
            color: var(--primary);
            font-weight: 500;
        }
        
        .input {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: none;
        }
        
        .input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.125em rgba(118, 203, 187, 0.25);
        }
        
        .save-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            margin-top: 1.5rem;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .save-btn:hover {
            background-color: #5fb8aa;
            transform: translateY(-2px);
        }
        
        .save-btn .icon {
            margin-right: 0.5rem;
        }
        
        /* Sidebar styles */
        .user-greeting {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            color: var(--primary);
            white-space: nowrap;
        }
        
        .user-greeting .icon {
            margin-right: 0.75rem;
            background-color: var(--primary-light);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .menu-list a {
            border-radius: 6px;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            color: #555;
            white-space: nowrap;
        }
        
        .menu-list a:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        .menu-list a.is-active {
            background-color: var(--primary);
            color: white;
        }
        
        .menu-list .icon {
            margin-right: 0.75rem;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 100;
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-menu-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 90;
                background-color: var(--primary);
                color: white;
                border: none;
                border-radius: 6px;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-menu-toggle is-hidden-desktop">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="dashboard">
<!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="toggle-sidebar is-hidden-mobile">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>
            
            <div class="user-greeting">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </span>
                <h1>Hi, <?php echo htmlspecialchars($user['nama']); ?></h1>
            </div>
            
            <aside class="menu">
                <ul class="menu-list">
                    <li>
                        <a href="dashboard.php">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                            </span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="tambah.php">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                            <span class="menu-text">Tambah Task</span>
                        </a>
                    </li>
                    <li>
                        <a class="is-active">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <span class="menu-text">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php">
                            <span class="menu-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </aside>
        </div>
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <div class="profile-card">
                <h1>
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
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
                            <input class="input" type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Password Baru (biarkan kosong jika tidak ingin mengubah)</label>
                        <div class="control has-icons-left">
                            <input class="input" type="password" name="password" placeholder="Masukkan password baru">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </div>
                        <p class="password-note">Minimal 8 karakter</p>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" name="update_profile" class="button is-primary">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
                                </svg>
                            </span>
                            <span>Simpan Perubahan</span>
                        </button>
                        <a href="dashboard.php" class="button is-light">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
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
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.querySelector('.toggle-sidebar');
            const mobileToggleBtn = document.querySelector('.mobile-menu-toggle');
            
            // Desktop toggle functionality
            toggleBtn.addEventListener('click', function() {
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
            mobileToggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
            
            // Close sidebar when clicking on main content (mobile only)
            if (window.innerWidth <= 768) {
                mainContent.addEventListener('click', function() {
                    if (sidebar.classList.contains('open')) {
                        sidebar.classList.remove('open');
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('open');
                }
            });
        });
    </script>
</body>
</html>
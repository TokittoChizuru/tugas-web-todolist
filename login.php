<?php
session_start();
require 'includes/koneksi.php';
require 'includes/auth.php';

if (isset($_SESSION['id_user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        if (login($email, $password)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Email atau password salah!";
        }
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <style>
        :root {
            --primary: #76CBBB;
            --primary-light: #CFFFF6;
            --background: #ffffff;
            --text-color: #000000;
            --sidebar-text-color: #555;
            --sidebar-icon-color: #555;
            --task-list-box: 0 2px 10px rgba(0, 0, 0, 0.05);

            --dark-primary: #2d9893;
            --dark-primary-light: #2b7a78;
            --dark-background: #17252a;
            --dark-text-color: #ffffff;
            --dark-sidebar-text-color: #ffffff;
            --dark-sidebar-icon-color: #ffffff;
            --dark-task-list-box: 0 2px 10px rgba(255, 255, 255, 0.15);
        }

        body.dark-mode {
            --primary: var(--dark-primary);
            --primary-light: var(--dark-primary-light);
            --background: var(--dark-background);
            --text-color: var(--dark-text-color);
            --sidebar-text-color: var(--dark-sidebar-text-color);
            --sidebar-icon-color: var(--dark-sidebar-icon-color);
            --task-list-box: var(--dark-task-list-box);
            color: var(--dark-text-color);
        }

        body {
            background-color: var(--primary-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(118, 203, 187, 0.2);
            background: var(--background);
        }

        .login-header {
            background: var(--primary);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .profile-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-icon svg {
            width: 48px;
            height: 48px;
        }

        .login-body {
            padding: 2rem;
        }

        .login-title {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }

        .field:not(:last-child) {
            margin-bottom: 1.25rem;
        }

        .control.has-icons-left .icon {
            color: var(--primary);
        }

        .button.is-primary {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            width: 100%;
            border: none;
            transition: all 0.3s ease;
        }

        .button.is-primary:hover {
            background-color: #5fb8aa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(118, 203, 187, 0.3);
        }

        .button.is-primary:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding-top: 1.5rem;
            color: #666;
        }

        .login-footer a {
            color: var(--primary);
            font-weight: 500;
            transition: color 0.2s;
        }

        .login-footer a:hover {
            color: #5fb8aa;
        }

        .input:focus,
        .input:active {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.125em rgba(118, 203, 187, 0.25);
        }

        .checkbox:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>

            <div class="login-body">
                <h3 class="login-title">Login</h3>

                <?php if (!empty($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>

                <form method="POST">
                    <div class="field">
                        <label class="label" style="color: var(--text-color);">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" id="email" name="email" type="email" placeholder="your@email.com">
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
                        <label class="label" style="color: var(--text-color);">Password</label>
                        <div class="control has-icons-left">
                            <input class="input" id="password" name="password" type="password" placeholder="••••••••">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                <span>Login</span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="login-footer">
                    <a href="#">Forgot password?</a> • <a href="register.php">Create account</a>
                </div>
            </div>
        </div>
    </div>
</body>

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

</html>
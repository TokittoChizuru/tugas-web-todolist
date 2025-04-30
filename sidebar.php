<style>
    :root {
        --primary: #76CBBB;
        --primary-light: #CFFFF6;
        --background: #FFFFFF;
        --text-color: #000000;

        --dark-primary: #3aafa9;
        --dark-primary-light: #2b7a78;
        --dark-background: #17252a;
        --dark-text-color: #ffffff;
    }

    body.dark-mode {
        --primary: var(--dark-primary);
        --primary-light: var(--dark-primary-light);
        --background: var(--dark-background);
        --text-color: var(--dark-text-color);
        color: var(--dark-text-color);
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

    .task-form {
        background-color: var(--background);
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        max-width: 600px;
        margin: 0 auto;
    }

    .task-form h1 {
        color: var(--primary);
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        font-weight: 600;
    }

    .field:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    .label {
        color: var(--primary);
        font-weight: 500;
    }

    .input,
    .textarea,
    .datepicker {
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: none;
    }

    .input:focus,
    .textarea:focus,
    .datepicker:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.125em rgba(118, 203, 187, 0.25);
    }

    .create-btn {
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        margin-top: 1rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .create-btn:hover {
        background-color: #5fb8aa;
        transform: translateY(-2px);
    }

    .create-btn .icon {
        margin-right: 0.5rem;
    }

    /* Sidebar styles same as before */
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

    .button {
        background-color: transparent;
        color: #5fb8aa;
    }
</style>
</head>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        }

        // Load state from localStorage
        const darkModeOn = localStorage.getItem('dark-mode') === 'enabled';
        if (darkModeOn) {
            body.classList.add('dark-mode');
            setIcon(true);
        } else {
            setIcon(false);
        }

        toggleDarkMode.addEventListener('click', function () {
            const isDark = body.classList.toggle('dark-mode');
            localStorage.setItem('dark-mode', isDark ? 'enabled' : 'disabled');
            setIcon(isDark);
        });
    });
</script>

<body>
    <button class="mobile-menu-toggle is-hidden-desktop">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            width="24" height="24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <button class="toggle-sidebar is-hidden-mobile">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" width="16" height="16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <div class="user-greeting">
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </span>
                <h1>Hi, <?php echo ($nama_user); ?></h1>
            </div>

            <aside class="menu">
                <ul class="menu-list">
                    <li>
                        <div style="margin-top: 2rem;">
                            <button class="button is-small" id="darkModeToggle"
                                style="display: flex; align-items: center;">
                                <span class="icon" id="darkModeIcon">
                                    <!-- Default: Sun icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364l-1.06-1.06M6.697 6.697l-1.06-1.06m12.727 0l-1.06 1.06M6.697 17.303l-1.06 1.06M12 8.25A3.75 3.75 0 1112 15a3.75 3.75 0 010-6.75z" />
                                    </svg>
                                </span>
                                <span style="margin-left: 0.5rem;">Dark Mode</span>
                            </button>
                        </div>
                    </li>
                    <li>
                        <a href="dashboard.php">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                            </span>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="is-active">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </span>
                            <span class="menu-text">Tambah Task</span>
                        </a>
                    </li>
                    <li>
                        <a href="profile.php">
                            <span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
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
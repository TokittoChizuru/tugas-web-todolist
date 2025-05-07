<?php
session_start();
// Jika pengguna sudah login, redirect ke dashboard
if (isset($_SESSION['id_user'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HariHebatKu - Aplikasi TodoList untuk Anak Hebat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="#">
                    <span class="title">HariHebatKu</span>
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false"
                    data-target="navbarBasic">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasic" class="navbar-menu">
                <div class="navbar-end">
                    <a class="navbar-item" href="#features">Fitur</a>
                    <a class="navbar-item" href="#screenshots">Tampilan</a>
                    <a class="navbar-item" href="#testimonials">Testimoni</a>
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="login.php">
                                <strong>Masuk</strong>
                            </a>
                            <a class="button is-light" href="register.php">
                                Daftar
                            </a>
                            <a class="button is-dark" id="darkModeToggle">
                                <span class="icon" id="darkModeIcon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364 6.364l-1.06-1.06M6.697 6.697l-1.06-1.06m12.727 0l-1.06 1.06M6.697 17.303l-1.06 1.06M12 8.25A3.75 3.75 0 1112 15a3.75 3.75 0 010-6.75z" />
                                    </svg>
                                </span>
                                <span class="menu-text">Dark Mode</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero is-medium">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-6">
                        <h1 class="title">
                            Atur Harimu dengan HariHebatKu
                        </h1>
                        <h2 class="subtitle">
                            Aplikasi TodoList khusus untuk anak-anak hebat yang ingin menjadi lebih produktif dan
                            terorganisir!
                        </h2>
                        <div class="buttons">
                            <a href="register.php" class="button is-dark is-medium">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                                <span>Mulai Sekarang</span>
                            </a>
                            <a href="#features" class="button is-outlined is-medium">
                                <span>Pelajari Fitur</span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="column is-6">
                        <img src="https://png.pngtree.com/png-vector/20230808/ourmid/pngtree-test-taking-vector-png-image_6931364.png"
                            alt="Ilustrasi Anak Mengatur Tugas" class="illustration">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="has-text-centered mb-6">
                <h2 class="title is-2" style="color: var(--primary);">Fitur Unggulan</h2>
                <p class="subtitle">Apa yang membuat HariHebatKu istimewa?</p>
            </div>

            <div class="columns">
                <div class="column is-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Manajemen Tugas Mudah</h3>
                        <p>Tambahkan, edit, dan hapus tugas dengan mudah. Atur prioritas dan deadline untuk setiap
                            tugasmu.</p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Pengingat Deadline</h3>
                        <p>Jangan sampai lupa deadline tugas! HariHebatKu akan mengingatkanmu sebelum waktu habis.</p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="feature-title">Pencapaian Harian</h3>
                        <p>Lihat perkembanganmu setiap hari dan rasakan kepuasan menyelesaikan semua tugas tepat waktu!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshot Section -->
    <section class="screenshot-section" id="screenshots">
        <div class="container">
            <div class="has-text-centered mb-6">
                <h2 class="title is-2" style="color: var(--primary);">Tampilan Aplikasi</h2>
                <p class="subtitle">Lihat bagaimana HariHebatKu membantumu mengatur hari-harimu</p>
            </div>

            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="screenshot-card">
                        <img src="assets/img/gambar1.png" alt="Tampilan Dashboard HariHebatKu" class="illustration">
                    </div>
                </div>
            </div>

            <div class="columns mt-5">
                <div class="column is-4">
                    <div class="screenshot-card">
                        <img src="assets/img/gambar2.png" alt="Tambah Tugas" class="illustration">
                    </div>
                </div>
                <div class="column is-4">
                    <div class="screenshot-card">
                        <img src="assets/img/gambar3.png" alt="Profil Pengguna" class="illustration">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial" id="testimonials">
        <div class="container">
            <div class="has-text-centered mb-6">
                <h2 class="title is-2" style="color: var(--primary);">Apa Kata Mereka?</h2>
                <p class="subtitle">Testimoni dari pengguna HariHebatKu</p>
            </div>

            <div class="columns">
                <div class="column is-4">
                    <div class="testimonial-card">
                        <div class="has-text-centered mb-4">
                            <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="Testimoni 1"
                                class="testimonial-avatar">
                        </div>
                        <p class="mb-4">"Sejak pakai HariHebatKu, aku nggak pernah lupa tugas sekolah lagi! Aplikasinya
                            mudah banget dipakai."</p>
                        <p class="has-text-weight-bold">Rafi, 21 tahun</p>
                        <p class="has-text-grey">Mahasiswa</p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="testimonial-card">
                        <div class="has-text-centered mb-4">
                            <img src="https://randomuser.me/api/portraits/women/21.jpg" alt="Testimoni 2"
                                class="testimonial-avatar">
                        </div>
                        <p class="mb-4">"Aku suka banget sama fitur reminder-nya. Sekarang PR selalu selesai tepat
                            waktu!"</p>
                        <p class="has-text-weight-bold">Siti, 16 tahun</p>
                        <p class="has-text-grey">Siswa SMA</p>
                    </div>
                </div>

                <div class="column is-4">
                    <div class="testimonial-card">
                        <div class="has-text-centered mb-4">
                            <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Testimoni 3"
                                class="testimonial-avatar">
                        </div>
                        <p class="mb-4">"Anak saya jadi lebih mandiri mengatur waktunya sejak menggunakan HariHebatKu.
                            Recommended banget!"</p>
                        <p class="has-text-weight-bold">Ibu Dian</p>
                        <p class="has-text-grey">Orang Tua</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Siap untuk Hari-Hari Hebatmu?</h2>
            <p class="subtitle mb-5" style="color: var(--text-color);">Daftar sekarang dan mulai atur tugas-tugasmu
                dengan lebih mudah!</p>
            <div class="buttons is-centered">
                <a href="register.php" class="button is-primary is-large">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </span>
                    <span>Daftar Gratis</span>
                </a>
                <a href="login.php" class="button is-outlined is-large">
                    <span>Sudah Punya Akun? Masuk</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <h3 class="title is-4" style="color: var(--text-color);">HariHebatKu</h3>
                    <p>Aplikasi TodoList untuk anak-anak hebat yang ingin menjadi lebih produktif dan terorganisir!</p>
                </div>
                <div class="column is-2">
                    <h4 class="title is-5" style="color: var(--text-color);">Menu</h4>
                    <ul>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#screenshots">Tampilan</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                    </ul>
                </div>
                <div class="column is-2">
                    <h4 class="title is-5" style="color: var(--text-color);">Akun</h4>
                    <ul>
                        <li><a href="login.php">Masuk</a></li>
                        <li><a href="register.php">Daftar</a></li>
                    </ul>
                </div>
                <div class="column is-4">
                    <h4 class="title is-5 " style="color: var(--text-color);">Hubungi Kami</h4>
                    <p>
                        <span class="icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        hello@harihebatku.com
                    </p>
                </div>
            </div>
            <div class="has-text-centered mt-5">
                <p>&copy; 2025 RizDev. All rights reserved.</p>
            </div>
        </div>
    </footer>

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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile menu toggle
            const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            if ($navbarBurgers.length > 0) {
                $navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);

                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>

</html>
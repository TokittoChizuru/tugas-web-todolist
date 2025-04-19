<?php
session_start();
require 'includes/koneksi.php'; // Koneksi ke database
require 'includes/auth.php'; // Memuat fungsi autentikasi

// Cek jika user sudah login
if (isset($_SESSION['id_user'])) {
    header('Location: dashboard.php'); // Redirect ke dashboard jika sudah login
    exit;
}

// Inisialisasi variabel untuk pesan error
$error = "";
$success = "";

// Proses form registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $konfirmasi_password = trim($_POST['konfirmasi_password']);

    // Validasi input
    if (!empty($nama) && !empty($email) && !empty($username) && !empty($password) && !empty($konfirmasi_password)) {
        if ($password === $konfirmasi_password) {
            // Cek apakah email atau username sudah terdaftar
            $query = "SELECT * FROM users WHERE email = ? OR username = ?";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param('ss', $email, $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email atau username sudah terdaftar!";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                // Simpan data ke database
                $insert_query = "INSERT INTO users (nama, email, username, password) VALUES (?, ?, ?, ?)";
                $insert_stmt = $koneksi->prepare($insert_query);
                $insert_stmt->bind_param('ssss',$nama, $email, $username, $hashed_password);

                if ($insert_stmt->execute()) {
                    $success = "Registrasi berhasil! Silakan login.";
                } else {
                    $error = "Terjadi kesalahan saat menyimpan data.";
                }
            }
        } else {
            $error = "Password dan konfirmasi password tidak cocok!";
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
    <title>Create Account</title>
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
            display: flex;
            align-items: center;
        }
        
        .register-card {
            max-width: 450px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(118, 203, 187, 0.2);
            background: var(--background);
        }
        
        .register-header {
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
        
        .register-body {
            padding: 2rem;
        }
        
        .register-title {
            font-size: 1.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-weight: 600;
            text-align: center;
        }
        
        .field:not(:last-child) {
            margin-bottom: 1rem;
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
            margin-top: 1rem;
        }
        
        .button.is-primary:hover {
            background-color: #5fb8aa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(118, 203, 187, 0.3);
        }
        
        .button.is-primary:active {
            transform: translateY(0);
        }
        
        .register-footer {
            text-align: center;
            padding-top: 1.5rem;
            color: #666;
        }
        
        .register-footer a {
            color: var(--primary);
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .register-footer a:hover {
            color: #5fb8aa;
        }
        
        .input:focus, .input:active {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.125em rgba(118, 203, 187, 0.25);
        }
        
        .checkbox:hover {
            color: var(--primary);
        }
        
        .name-fields {
            display: flex;
            gap: 1rem;
        }
        
        .name-fields .field {
            flex: 1;
        }
        
        @media (max-width: 480px) {
            .name-fields {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-card">
            <div class="register-header">
                <div class="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
            </div>
            
            <div class="register-body">
                <h3 class="register-title">Create your account</h3>
                
                    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
                
                <form method="POST">
                    <div class="name-fields">
                        <div class="field">
                            <label class="label">Nama Lengkap</label>
                            <div class="control has-icons-left">
                                <input class="input" id="nama" name="nama" type="text" placeholder="John">
                                <span class="icon is-small is-left">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" id="email" name="email" type="email" placeholder="your@email.com">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control has-icons-left">
                            <input class="input" id="password" name="password" type="password" placeholder="••••••••">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </div>
                        <p class="help">Minimum 8 characters</p>
                    </div>
                    
                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <div class="control has-icons-left">
                            <input class="input" id="konfirmasi_password" name="konfirmasi_password" type="password" placeholder="••••••••">
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox">
                                I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                    
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                <span>Create Account</span>
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="register-footer">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
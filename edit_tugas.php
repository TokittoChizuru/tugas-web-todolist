<?php
session_start();
require 'includes/koneksi.php';
require 'includes/auth.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

$id_user = $_SESSION['id_user'];

if (!isset($_GET['id_tugas'])) {
    header('Location: dashboard.php');
    exit;
}

$id_tugas = $_GET['id_tugas'];

$query = "SELECT * FROM tugas WHERE id_tugas = ? AND id_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param('ii', $id_tugas, $id_user);
$stmt->execute();
$result = $stmt->get_result();
$tugas = $result->fetch_assoc();

if (!$tugas) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_tugas'])) {
    $nama_tugas = trim($_POST['nama_tugas']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_deadline = trim($_POST['tanggal_deadline']);
    $status = trim($_POST['status_tugas']);

    if (!empty($nama_tugas) && !empty($tanggal_deadline)) {
        $update_query = "UPDATE tugas SET nama_tugas = ?, deskripsi = ?, tanggal_deadline = ?, status_tugas = ? WHERE id_tugas = ? AND id_user = ?";
        $update_stmt = $koneksi->prepare($update_query);
        $update_stmt->bind_param('ssssii', $nama_tugas, $deskripsi, $tanggal_deadline, $status, $id_tugas, $id_user);

        if ($update_stmt->execute()) {
            header('Location: dashboard.php?status=edit_berhasil');
            exit;
        } else {
            $error = "Gagal memperbarui tugas.";
        }
    } else {
        $error = "Nama tugas dan tanggal deadline wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
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
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styles (same as previous) */
        .sidebar {
            width: 250px;
            background-color: var(--background);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
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
            display: flex;
            align-items: center;
        }
        
        .task-form h1 .icon {
            margin-right: 0.75rem;
        }
        
        .field:not(:last-child) {
            margin-bottom: 1.5rem;
        }
        
        .label {
            color: var(--primary);
            font-weight: 500;
        }
        
        .input, .textarea, .select select {
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: none;
        }
        
        .input:focus, .textarea:focus, .select select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.125em rgba(118, 203, 187, 0.25);
        }
        
        .buttons {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .button.is-primary {
            background-color: var(--primary);
        }
        
        .button.is-primary:hover {
            background-color: #5fb8aa;
        }
        
        .button.is-light {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        
        .notification.is-danger {
            background-color: #ffe6e6;
            color: #ff0000;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Include your sidebar here (same as previous) -->
        
        <div class="main-content">
            <div class="task-form">
                <h1>
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </span>
                    <span>Edit Tugas</span>
                </h1>

                <?php if (isset($error)): ?>
                    <div class="notification is-danger is-light">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="field">
                        <label class="label">Nama Tugas</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" name="nama_tugas" value="<?php echo htmlspecialchars($tugas['nama_tugas']); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Deskripsi</label>
                        <div class="control">
                            <textarea class="textarea" name="deskripsi" rows="4"><?php echo htmlspecialchars($tugas['deskripsi']); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Tanggal Deadline</label>
                        <div class="control has-icons-left">
                            <input class="input" type="date" name="tanggal_deadline" value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($tugas['tanggal_deadline']))); ?>" required>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Status</label>
                        <div class="control has-icons-left">
                            <div class="select is-fullwidth">
                                <select name="status_tugas" required>
                                    <option value="belum" <?php echo ($tugas['status_tugas'] === 'belum') ? 'selected' : ''; ?>>Belum</option>
                                    <option value="selesai" <?php echo ($tugas['status_tugas'] === 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </div>
                            <span class="icon is-small is-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                    <div class="buttons">
                        <button type="submit" name="update_tugas" class="button is-primary">
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
                            <span>Batal</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Include your sidebar toggle JavaScript here (same as previous)
    </script>
</body>
</html>
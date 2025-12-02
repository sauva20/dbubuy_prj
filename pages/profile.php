<?php
session_start();
require_once '../config/koneksi.php';

// Cek Login
if (empty($_SESSION['is_login'])) {
    header("Location: login.php");
    exit;
}

// LOGIC PENGAMBILAN DATA USER 
$nama_sess = $_SESSION['nama'] ?? '';     // Ambil nama dari session
$username_sess = $_SESSION['username'] ?? ''; // Ambil username dari session 

// Default variable user kosong
$user = null;

if (!empty($username_sess)) {
    // Cari berdasarkan username jika ada
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username_sess'");
    $user = mysqli_fetch_assoc($query);
} 

// Jika user belum ketemu (karena username kosong/salah), cari berdasarkan nama
if (!$user && !empty($nama_sess)) {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE nama_lengkap = '$nama_sess'");
    $user = mysqli_fetch_assoc($query);
}

// Jika data user tetap tidak ditemukan di database (Fatal Error Prevention)
if (!$user) {
    echo "<script>alert('Data user tidak ditemukan. Silakan login ulang.'); window.location='../action/logout.php';</script>";
    exit;
}

// Variabel Pesan
$msg = "";
$msg_type = "";

// --- LOGIC UPDATE PROFIL ---
if (isset($_POST['btn_update_profile'])) {
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $wa_baru   = mysqli_real_escape_string($koneksi, $_POST['wa']);
    
    // Update DB
    $update = mysqli_query($koneksi, "UPDATE users SET nama_lengkap='$nama_baru', no_whatsapp='$wa_baru' WHERE id='{$user['id']}'");
    
    if ($update) {
        // Update Session & Data User
        $_SESSION['nama'] = $nama_baru;
        $user['nama_lengkap'] = $nama_baru;
        $user['no_whatsapp'] = $wa_baru;
        
        $msg = "Profil berhasil diperbarui!";
        $msg_type = "success";
    } else {
        $msg = "Gagal update profil: " . mysqli_error($koneksi);
        $msg_type = "danger";
    }
}

// --- LOGIC GANTI PASSWORD ---
if (isset($_POST['btn_ganti_pass'])) {
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $pass_konf = $_POST['pass_konf'];

    // Cek password lama
    if (password_verify($pass_lama, $user['password'])) {
        if ($pass_baru === $pass_konf) {
            $new_hash = password_hash($pass_baru, PASSWORD_DEFAULT);
            $update_pass = mysqli_query($koneksi, "UPDATE users SET password='$new_hash' WHERE id='{$user['id']}'");
            
            if ($update_pass) {
                $msg = "Password berhasil diubah! Silakan login ulang nanti.";
                $msg_type = "success";
            }
        } else {
            $msg = "Konfirmasi password baru tidak cocok.";
            $msg_type = "warning";
        }
    } else {
        $msg = "Password lama salah.";
        $msg_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - D'Bubuy Ma'Atik</title>
    
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f7f7f7; font-family: 'Poppins', sans-serif; color: #333; }
        
        .profile-header {
            background: linear-gradient(135deg, #ce1212, #a80000);
            padding: 60px 0 100px;
            color: white;
            text-align: center;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background-color: #fff;
            color: #ce1212;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border: 4px solid rgba(255,255,255,0.3);
        }

        .main-content { margin-top: -60px; }

        .card-custom {
            background: #fff;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 25px;
        }

        .card-title-custom {
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        .form-label { font-weight: 500; color: #555; font-size: 14px; }
        .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #ddd; }
        .form-control:focus { border-color: #ce1212; box-shadow: 0 0 0 0.2rem rgba(206, 18, 18, 0.25); }
        .form-control[readonly] { background-color: #f8f9fa; color: #888; }

        .btn-simpan { background-color: #ce1212; color: #fff; border: none; padding: 10px 25px; border-radius: 50px; font-weight: 500; transition: 0.3s; }
        .btn-simpan:hover { background-color: #b00e0e; color: #fff; }

        .btn-back { color: white; text-decoration: none; display: inline-flex; align-items: center; margin-bottom: 20px; opacity: 0.8; }
        .btn-back:hover { color: white; opacity: 1; }

        .shortcut-btn {
            display: block;
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            border: 1px solid #eee;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .shortcut-btn:hover { background-color: #fcebeb; color: #ce1212; border-color: #ce1212; }
        .shortcut-icon { font-size: 20px; margin-right: 10px; color: #ce1212; }
    </style>
</head>
<body>

    <div class="profile-header">
        <div class="container">
            <div class="text-start mb-3">
                <a href="../index.php" class="btn-back"><i class="bi bi-arrow-left me-2"></i> Kembali ke Home</a>
            </div>
            
            <div class="avatar-circle">
                <?= strtoupper(substr($user['nama_lengkap'] ?? 'U', 0, 1)) ?>
            </div>
            
            <h3><?= htmlspecialchars($user['nama_lengkap'] ?? 'User') ?></h3>
            <p class="mb-0 text-white-50">Member sejak <?= isset($user['created_at']) ? date('Y', strtotime($user['created_at'])) : '-' ?></p>
        </div>
    </div>

    <div class="container main-content">
        <div class="row">
            
            <div class="col-lg-4">
                <div class="card-custom p-3">
                    <small class="text-muted d-block mb-2 fw-bold px-2">MENU UTAMA</small>
                    <a href="orders.php" class="shortcut-btn mb-2">
                        <div><i class="bi bi-bag-check shortcut-icon"></i> Riwayat Pesanan</div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="../index.php#features" class="shortcut-btn mb-2">
                        <div><i class="bi bi-book shortcut-icon"></i> Lihat Katalog Menu</div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="shortcut-btn">
                        <div><i class="bi bi-whatsapp shortcut-icon"></i> Hubungi Admin</div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-8">
                
                <?php if($msg): ?>
                    <div class="alert alert-<?= $msg_type ?> alert-dismissible fade show" role="alert">
                        <?= $msg ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card-custom">
                    <h5 class="card-title-custom"><i class="bi bi-person-lines-fill me-2"></i> Data Pribadi</h5>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?= $user['username'] ?? '-' ?>" readonly>
                                <small class="text-muted" style="font-size:11px">*Username tidak dapat diubah</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Bergabung Pada</label>
                                <input type="text" class="form-control" value="<?= isset($user['created_at']) ? date('d F Y', strtotime($user['created_at'])) : '-' ?>" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="number" name="wa" class="form-control" value="<?= htmlspecialchars($user['no_whatsapp'] ?? '') ?>" required>
                            <small class="text-muted">*Pastikan nomor aktif untuk notifikasi pesanan</small>
                        </div>

                        <div class="text-end">
                            <button type="submit" name="btn_update_profile" class="btn btn-simpan">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>

                <div class="card-custom">
                    <h5 class="card-title-custom"><i class="bi bi-shield-lock me-2"></i> Ganti Password</h5>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Password Lama</label>
                            <input type="password" name="pass_lama" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="pass_baru" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ulangi Password Baru</label>
                                <input type="password" name="pass_konf" class="form-control" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" name="btn_ganti_pass" class="btn btn-outline-secondary rounded-pill px-4">Update Password</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
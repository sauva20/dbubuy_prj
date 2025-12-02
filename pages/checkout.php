<?php
session_start();
require_once '../config/koneksi.php';

// Cek Login & Keranjang
if (empty($_SESSION['cart']) || empty($_SESSION['is_login'])) {
    header("Location: ../index.php");
    exit;
}

// LOGIC DATA USER
$nama_user = $_SESSION['nama'] ?? '';
$username  = $_SESSION['username'] ?? '';
$user_data = [];

if (!empty($username)) {
    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $user_data = mysqli_fetch_assoc($q);
} elseif (!empty($nama_user)) {
    $q = mysqli_query($koneksi, "SELECT * FROM users WHERE nama_lengkap = '$nama_user'");
    $user_data = mysqli_fetch_assoc($q);
}

// Data Default dari Akun
$val_nama = $user_data['nama_lengkap'] ?? $nama_user;
$val_wa   = $user_data['no_whatsapp'] ?? '';

//  Hitung total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - D'Bubuy Ma'Atik</title>
    
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f7f7f7; font-family: 'Poppins', sans-serif; color: #212529; }
        .container { margin-top: 50px; margin-bottom: 50px; }
        
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-header h2 { font-size: 32px; font-weight: 600; color: #37373f; }
        .section-header p { color: #b0b0b0; }

        .card-custom { background: #fff; border: none; border-radius: 8px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.05); padding: 30px; margin-bottom: 20px; }
        .card-header-custom { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 20px; font-size: 18px; font-weight: 600; color: #ce1212; }

        .form-label { font-weight: 500; color: #555; font-size: 14px; }
        .form-control { border: 1px solid #ddd; padding: 12px; border-radius: 4px; font-size: 14px; }
        .form-control:focus { box-shadow: none; border-color: #ce1212; }
        .form-control[readonly] { background-color: #f9f9f9; color: #777; }

        .pickup-box { background-color: #e7f6f2; border: 1px dashed #2c786c; color: #2c786c; padding: 15px; border-radius: 6px; text-align: center; font-weight: 500; margin-bottom: 25px; }

        .order-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f2f2f2; }
        .order-total { background-color: #ce1212; color: white; padding: 15px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 18px; margin-top: 20px; }

        .btn-checkout { background-color: #ce1212; color: #fff; width: 100%; padding: 14px; border: none; border-radius: 50px; font-weight: 600; margin-top: 20px; transition: 0.3s; }
        .btn-checkout:hover { background-color: #e43c5c; color: #fff; }
        .btn-back { text-decoration: none; color: #777; font-size: 14px; display: block; text-align: center; margin-top: 15px; }
        .btn-back:hover { color: #ce1212; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="section-header">
            <h2>Checkout Pesanan</h2>
            <p>Selesaikan pesanan Anda untuk menikmati hidangan istimewa</p>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-person-circle"></i> Data Pemesan
                    </div>

                    <form action="/action/process_order.php" method="POST">
                        <input type="hidden" name="alamat" value="PICKUP - Ambil di Tempat">
                        
                        <div class="pickup-box">
                            <i class="bi bi-shop-window"></i> METODE: AMBIL DI TEMPAT (PICKUP)
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($val_nama) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email (Untuk Bukti Pembayaran)</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukan email aktif anda..." required>
                        </div>

                        <hr style="margin: 0px 0; border-color: #eee;">

                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor WhatsApp (Akun Terdaftar)</label>
                            <input type="text" name="phone_registered" class="form-control" value="<?= htmlspecialchars($val_wa) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Nomor WhatsApp Penerima (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="number" name="phone_manual" class="form-control" placeholder="Isi JIKA pesanan ini untuk orang lain...">
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">
                                *Jika diisi, kami akan menghubungi nomor ini. Jika kosong, kami gunakan nomor akun.
                            </small>
                        </div>

                        <button type="submit" class="btn-checkout">
                            BAYAR SEKARANG
                        </button>

                        <a href="../index.php" class="btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Menu</a>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-custom">
                    <div class="card-header-custom">
                        <i class="bi bi-receipt"></i> Ringkasan Pesanan
                    </div>
                    
                    <div>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                        <div class="order-item">
                            <div>
                                <div style="font-weight: 600; color: #333;"><?= $item['name'] ?></div>
                                <small style="color: #888;">Qty: <?= $item['qty'] ?></small>
                            </div>
                            <span style="font-weight: 500; color: #555;">Rp <?= number_format($item['price'] * $item['qty'], 0, ',', '.') ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-total">
                        <span>TOTAL</span>
                        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
<?php
session_start();
require_once '../config/koneksi.php';

// 1. Cek Login
if (empty($_SESSION['is_login'])) {
    header("Location: ../pages/login.php");
    exit;
}

// 2. Ambil Data User (Kita butuh No WA untuk mencocokkan pesanan)
$username = $_SESSION['username'] ?? '';
$nama_user = $_SESSION['nama'] ?? '';

// Cari data user di database
if (!empty($username)) {
    $q_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
} else {
    $q_user = mysqli_query($koneksi, "SELECT * FROM users WHERE nama_lengkap = '$nama_user'");
}
$user = mysqli_fetch_assoc($q_user);
$user_phone = $user['no_whatsapp'];

// 3. Ambil Data Pesanan Berdasarkan No WA
// Kita urutkan dari yang terbaru (DESC)
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders WHERE customer_phone = '$user_phone' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - D'Bubuy Ma'Atik</title>
    
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { background-color: #f7f7f7; font-family: 'Poppins', sans-serif; color: #333; }
        
        .page-header { background: #fff; padding: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .page-title { margin: 0; font-weight: 600; color: #ce1212; }

        .card-order {
            background: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            margin-bottom: 20px;
            transition: 0.3s;
            overflow: hidden;
        }

        .card-order:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }

        .order-header {
            background: #fdfdfd;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .order-id { font-weight: 600; color: #333; }
        
        .order-body { padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        
        .order-info h5 { font-size: 18px; font-weight: 600; margin-bottom: 5px; }
        .order-date { font-size: 13px; color: #999; margin-bottom: 10px; display: block; }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
        }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-success { background-color: #d4edda; color: #155724; }
        .bg-failed { background-color: #f8d7da; color: #721c24; }

        .btn-detail {
            background-color: #fff;
            border: 1px solid #ce1212;
            color: #ce1212;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-detail:hover { background-color: #ce1212; color: #fff; }
        
        .empty-state { text-align: center; padding: 50px 20px; }
        .empty-icon { font-size: 60px; color: #ddd; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="page-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h4 class="page-title">Riwayat Pesanan</h4>
            <a href="../index.php" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Kembali ke Menu</a>
        </div>
    </div>

    <div class="container">
        
        <?php if (mysqli_num_rows($query_orders) > 0): ?>
            
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <?php while($order = mysqli_fetch_assoc($query_orders)): ?>
                        
                        <?php 
                            $status_class = 'bg-pending';
                            $status_label = 'Menunggu Pembayaran';
                            
                            if ($order['status'] == 'success' || $order['status'] == 'settlement' || $order['status'] == 'capture') {
                                $status_class = 'bg-success';
                                $status_label = 'Pembayaran Berhasil';
                            } elseif ($order['status'] == 'expire' || $order['status'] == 'cancel' || $order['status'] == 'deny') {
                                $status_class = 'bg-failed';
                                $status_label = 'Gagal / Kadaluarsa';
                            }
                        ?>

                        <div class="card-order">
                            <div class="order-header">
                                <div>
                                    <i class="bi bi-receipt"></i> No. Order: <span class="order-id"><?= $order['id'] ?></span>
                                </div>
                                <span class="badge-status <?= $status_class ?>"><?= strtoupper($status_label) ?></span>
                            </div>
                            <div class="order-body">
                                <div class="order-info mb-3 mb-md-0">
                                    <span class="order-date"><i class="bi bi-calendar"></i> <?= date('d F Y, H:i', strtotime($order['created_at'])) ?> WIB</span>
                                    <h5>Total: Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></h5>
                                    <small class="text-muted">Metode: <?= ($order['customer_address'] == 'PICKUP - Ambil di Tempat') ? 'Ambil di Tempat (Pickup)' : 'Delivery' ?></small>
                                </div>
                                <div>
                                    <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn-detail">Lihat Detail</a>
                                </div>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </div>
            </div>

        <?php else: ?>
            
            <div class="empty-state">
                <i class="bi bi-cart-x empty-icon"></i>
                <h3>Belum ada pesanan</h3>
                <p class="text-muted">Kamu belum pernah melakukan pemesanan apapun.</p>
                <a href="../index.php" class="btn btn-danger rounded-pill mt-3 px-4">Mulai Pesan Sekarang</a>
            </div>

        <?php endif; ?>

    </div>

</body>
</html>
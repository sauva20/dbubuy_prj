<?php
session_start();
require_once '../../../config/koneksi.php';

// 1. Cek Akses Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// 2. Logic Update Status Pesanan
// 2. Logic Update Status Pesanan
if (isset($_POST['btn_update_status'])) {
    $order_id = $_POST['order_id'];
    $status_baru = $_POST['status'];
    
    $query_update = "UPDATE orders SET status = '$status_baru' WHERE id = '$order_id'";
    if (mysqli_query($koneksi, $query_update)) {
        
        // [BARU] CATAT LOG
        catat_log($koneksi, "Update Status", "Mengubah status Order #$order_id menjadi $status_baru");

        echo "<script>alert('Status pesanan berhasil diperbarui!'); window.location='pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal update status.');</script>";
    }
}

// 3. Ambil Semua Data Pesanan
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kelola Pesanan - Admin D'Bubuy</title>
    
    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Override sedikit style tabel agar lebih rapi */
        .table th { vertical-align: middle; white-space: nowrap; }
        .table td { vertical-align: middle; }
        
        /* Status Badge */
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; color: white; }
        .bg-pending { background-color: #f6c23e; }
        .bg-proses { background-color: #36b9cc; }
        .bg-success { background-color: #1cc88a; }
        .bg-failed { background-color: #e74a3b; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        
        <?php include 'partials/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                
                <?php include 'partials/topbar.php'; ?>

                <div class="container-fluid">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Pesanan</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Masuk</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID Order</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th width="12%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($query_orders)): ?>
                                            <?php 
                                                // Warna Badge Status
                                                $st = $row['status'];
                                                $badge = 'bg-secondary';
                                                if($st=='pending') $badge='bg-pending';
                                                elseif($st=='proses') $badge='bg-proses';
                                                elseif($st=='success' || $st=='settlement') $badge='bg-success';
                                                elseif($st=='expire' || $st=='deny' || $st=='cancel') $badge='bg-failed';
                                            ?>
                                        <tr>
                                            <td class="font-weight-bold">#<?= substr($row['id'], -6) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                                            <td>
                                                <?= htmlspecialchars($row['customer_name']) ?><br>
                                                <small class="text-muted"><?= $row['customer_phone'] ?></small>
                                            </td>
                                            <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                                            <td class="text-center">
                                                <span class="status-badge <?= $badge ?>"><?= strtoupper($st) ?></span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#modalDetail<?= $row['id'] ?>" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                <button class="btn btn-warning btn-circle btn-sm" data-toggle="modal" data-target="#modalStatus<?= $row['id'] ?>" title="Ubah Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Detail Order #<?= $row['id'] ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>Nama:</strong> <?= $row['customer_name'] ?></p>
                                                        <p><strong>Info:</strong> <?= $row['customer_address'] ?></p>
                                                        <hr>
                                                        <h6>Item Pesanan:</h6>
                                                        <ul class="list-group list-group-flush">
                                                            <?php
                                                            $oid = $row['id'];
                                                            $q_item = mysqli_query($koneksi, "SELECT * FROM order_items WHERE order_id='$oid'");
                                                            while($item = mysqli_fetch_assoc($q_item)):
                                                            ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                <?= $item['product_name'] ?> (x<?= $item['quantity'] ?>)
                                                                <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                                            </li>
                                                            <?php endwhile; ?>
                                                        </ul>
                                                        <div class="mt-3 text-right font-weight-bold h5">
                                                            Total: Rp <?= number_format($row['total_amount'], 0, ',', '.') ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="modalStatus<?= $row['id'] ?>" tabindex="-1" role="dialog">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Status</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                            <div class="form-group">
                                                                <label>Pilih Status</label>
                                                                <select name="status" class="form-control">
                                                                    <option value="pending" <?= ($st=='pending')?'selected':'' ?>>Pending</option>
                                                                    <option value="proses" <?= ($st=='proses')?'selected':'' ?>>Diproses</option>
                                                                    <option value="success" <?= ($st=='success')?'selected':'' ?>>Selesai/Success</option>
                                                                    <option value="cancel" <?= ($st=='cancel')?'selected':'' ?>>Dibatalkan</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" name="btn_update_status" class="btn btn-primary btn-sm btn-block">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                                
                                <?php if(mysqli_num_rows($query_orders) == 0): ?>
                                    <div class="text-center p-4 text-muted">Belum ada pesanan masuk.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>Copyright &copy; D'Bubuy 2025</span></div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>
</body>
</html>
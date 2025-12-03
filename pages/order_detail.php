<?php
session_start();
require_once '../config/koneksi.php';

$order_id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM orders WHERE id = '$order_id'");
$order = mysqli_fetch_assoc($query);

if (!$order) { die("Pesanan tidak ditemukan."); }

// Ambil item
$q_items = mysqli_query($koneksi, "SELECT * FROM order_items WHERE order_id = '$order_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $order_id ?></title>
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="SB-Mid-client-xxxxxxxxx"></script>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow border-0 mx-auto" style="max-width: 600px;">
            <div class="card-header bg-white text-center py-4">
                <h4 class="mb-0">Detail Pesanan</h4>
                <small class="text-muted">Order ID: <?= $order['id'] ?></small>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info text-center">
                    Status: <strong><?= strtoupper($order['status']) ?></strong>
                </div>

                <p><strong>Nama:</strong> <?= $order['customer_name'] ?></p>
                <p><strong>Alamat:</strong> <?= $order['customer_address'] ?></p>
                
                <hr>
                
                <table class="table table-borderless table-sm">
                    <?php while($item = mysqli_fetch_assoc($q_items)): ?>
                    <tr>
                        <td><?= $item['product_name'] ?> x<?= $item['quantity'] ?></td>
                        <td class="text-end">Rp <?= number_format($item['price'] * $item['quantity'],0,',','.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="border-top fw-bold">
                        <td>Total Bayar</td>
                        <td class="text-end">Rp <?= number_format($order['total_amount'],0,',','.') ?></td>
                    </tr>
                </table>

                <?php if ($order['status'] == 'pending'): ?>
                    <button id="pay-button" class="btn btn-success w-100 py-3 fw-bold mt-3">
                        BAYAR SEKARANG
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary w-100 py-3 mt-3" disabled>
                        Pembayaran Selesai / Kadaluarsa
                    </button>
                <?php endif; ?>
                
                <a href="../index.php" class="btn btn-link w-100 mt-2 text-decoration-none">Kembali ke Home</a>
            </div>
        </div>
    </div>

<script type="text/javascript">
      var payButton = document.getElementById('pay-button');
      if(payButton){
          payButton.addEventListener('click', function () {
            // Trigger Snap Popup
            window.snap.pay('<?= $order['snap_token'] ?>', {
              
              // KETIKA SUKSES
              onSuccess: function(result){
                // Arahan kembali ke halaman order_detail dengan status sukses
                window.location.href = "orders.php?id=<?= $order_id ?>&status=success";
              },
              
              // KETIKA PENDING (Belum bayar tapi close)
              onPending: function(result){
                alert("Menunggu pembayaran!");
                window.location.reload();
              },
              
              // ERROR
              onError: function(result){
                alert("Pembayaran gagal!");
                window.location.reload();
              },
              
              // DI-CLOSE TANPA BAYAR
              onClose: function(){
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
              }
            });
          });
      }
    </script>
</body>
</html>
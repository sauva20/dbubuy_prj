<?php
session_start(); // Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");

if (!$koneksi) { die("Gagal Konek: " . mysqli_connect_error()); }
 // --- 1. HITUNG TOTAL PENDAPATAN (OMSET) --- // Hanya hitung yang statusnya sukses/settlement
$total_omset = 0;
$q_omset = mysqli_query($koneksi, "SELECT SUM(total_amount) as total FROM orders WHERE status IN ('success', 'settlement', 'capture')");
if ($q_omset) { 
    $d = mysqli_fetch_assoc($q_omset); 
    $total_omset = $d['total'] ?? 0; 
}
 // --- 2. HITUNG JUMLAH DATA ---
$total_produk = 0;
$q_prod = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM products");
if ($q_prod) { $d = mysqli_fetch_assoc($q_prod); $total_produk = $d['total']; }

$total_customer = 0;
$q_cust = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE kategori='customer'");
if ($q_cust) { $d = mysqli_fetch_assoc($q_cust); $total_customer = $d['total']; }

$total_media = 0;
$q_media = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM liputan");
if ($q_media) { $d = mysqli_fetch_assoc($q_media); $total_media = $d['total']; }
 // --- 3. DATA UNTUK CHART (GRAFIK 7 HARI TERAKHIR) ---
$label_chart = [];
$data_chart = [];
 // Query ambil tanggal & total per hari
$q_chart = mysqli_query($koneksi, "
    SELECT DATE(created_at) as tgl, SUM(total_amount) as omset 
    FROM orders 
    WHERE status IN ('success', 'settlement', 'capture')
    GROUP BY DATE(created_at)
    ORDER BY tgl DESC
    LIMIT 7
");

$temp_data = [];
while($row = mysqli_fetch_assoc($q_chart)) {
    $temp_data[] = $row;
} // Balik urutan agar tanggal lama di kiri, baru di kanan
$temp_data = array_reverse($temp_data);

foreach($temp_data as $row) {
    $label_chart[] = date('d M', strtotime($row['tgl'])); // Format: 01 Jan
    $data_chart[] = $row['omset'];
}
 // Convert ke JSON untuk JS
$json_label = json_encode($label_chart);
$json_data  = json_encode($data_chart);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin D'Bubuy - Dashboard</title>

    <link href="../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="http ://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/css/admin.css" rel="stylesheet"> 
    
    <style>
        /* Card Dashboard Modern */
        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        /* Border Left Styles */
        .card-modern.border-primary { border-left: 5px solid #4e73df !important; }
        .card-modern.border-success { border-left: 5px solid #1cc88a !important; }
        .card-modern.border-info    { border-left: 5px solid #36b9cc !important; }
        .card-modern.border-warning { border-left: 5px solid #f6c23e !important; }

        /* Icon Styling */
        .icon-bg-circle {
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0.15;
            position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
            font-size: 24px;
        }
        .icon-primary { background-color: #4e73df; color: #4e73df; }
        .icon-success { background-color: #1cc88a; color: #1cc88a; }
        .icon-info    { background-color: #36b9cc; color: #36b9cc; }
        .icon-warning { background-color: #f6c23e; color: #f6c23e; }

        /* Typography */
        .text-label { font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 5px; }
        .text-value { font-size: 1.5rem; font-weight: 700; color: #333; }

        /* Table Styling */
        .table-custom th { background-color: #f8f9fc; color: #5a5c69; font-weight: 700; border-bottom: 2px solid #e3e6f0; }
        .table-custom td { vertical-align: middle; }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        .img-media { height: 30px; object-fit: contain; }
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Ringkasan</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-modern border-primary h-100 py-3 px-3">
                                <div class="card-body p-0">
                                    <div class="text-label text-primary">Pendapatan</div>
                                    <div class="text-value">Rp <?= number_format($total_omset, 0, ',', '.') ?></div>
                                    <div class="icon-bg-circle bg-primary text-white" style="opacity: 1;">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-modern border-success h-100 py-3 px-3">
                                <div class="card-body p-0">
                                    <div class="text-label text-success">Total Produk</div>
                                    <div class="text-value"><?= $total_produk; ?> Item</div>
                                    <div class="icon-bg-circle bg-success text-white" style="opacity: 1;">
                                        <i class="fas fa-utensils"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-modern border-info h-100 py-3 px-3">
                                <div class="card-body p-0">
                                    <div class="text-label text-info">Artikel Media</div>
                                    <div class="text-value"><?= $total_media; ?> Berita</div>
                                    <div class="icon-bg-circle bg-info text-white" style="opacity: 1;">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card card-modern border-warning h-100 py-3 px-3">
                                <div class="card-body p-0">
                                    <div class="text-label text-warning">Customer</div>
                                    <div class="text-value"><?= $total_customer; ?> User</div>
                                    <div class="icon-bg-circle bg-warning text-white" style="opacity: 1;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card shadow mb-4" style="border-radius: 15px;">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white" style="border-bottom: 1px solid #f0f0f0; border-radius: 15px 15px 0 0;">
                                    <h6 class="m-0 font-weight-bold text-primary">Tren Pendapatan (7 Hari Terakhir)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area" style="height: 350px;">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                    <?php if(empty($label_chart)): ?>
                                        <div class="text-center text-muted mt-3 small">Belum ada data transaksi yang sukses.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4" style="border-radius: 15px;">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white" style="border-radius: 15px 15px 0 0;">
                                    <h6 class="m-0 font-weight-bold text-primary">Produk Terbaru</h6>
                                    <a href="pages/tambah_produk.php" class="btn btn-sm btn-success shadow-sm" style="border-radius: 20px;"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-custom" width="100%" cellspacing="0">
                                            <thead><tr><th>Foto</th><th>Nama</th><th>Harga</th><th>Aksi</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $q_p = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC LIMIT 3");
                                                while($p = mysqli_fetch_assoc($q_p)){
                                                    $img = "../../" . $p['image']; 
                                                ?>
                                                <tr>
                                                    <td class="text-center"><img src="<?= $img; ?>" class="img-thumb"></td>
                                                    <td class="font-weight-bold"><?= $p['name']; ?></td>
                                                    <td>Rp <?= number_format($p['price'],0,',','.'); ?></td>
                                                    <td>
                                                        <a href="../produk/edit_produk.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm btn-circle"><i class="fas fa-pen"></i></a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4" style="border-radius: 15px;">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white" style="border-radius: 15px 15px 0 0;">
                                    <h6 class="m-0 font-weight-bold text-primary">Liputan Media</h6>
                                    <a href="pages/media.php" class="btn btn-sm btn-primary shadow-sm" style="border-radius: 20px;"><i class="fas fa-arrow-right"></i> Lihat Semua</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-custom">
                                            <thead><tr><th>Logo</th><th>Nama Media</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $q_m = mysqli_query($koneksi, "SELECT * FROM liputan ORDER BY id DESC LIMIT 3");
                                                while($m = mysqli_fetch_assoc($q_m)){
                                                    $img_m = "../../" . $m['image'];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><img src="<?= $img_m; ?>" class="img-media"></td>
                                                    <td><?= $m['media_name']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

    <script src="../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

    <script src="../../assets/template/sbadmin2/vendor/chart.js/Chart.min.js"></script>

    <script>
     // Set font family and color default
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
          number = (number + '').replace(',', '').replace(' ', '');
          var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
              var k = Math.pow(10, prec);
              return '' + Math.round(n * k) / k;
            };
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
          if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }
          if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
          }
          return s.join(dec);
        }

     // Render Chart
        var ctx = document.getElementById("myAreaChart");
        var myAreaChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: <?= $json_label; ?>, // Tanggal dari PHP
            datasets: [{
              label: "Pendapatan",
              lineTension: 0.3,
              backgroundColor: "rgba(78, 115, 223, 0.05)",
              borderColor: "rgba(78, 115, 223, 1)",
              pointRadius: 3,
              pointBackgroundColor: "rgba(78, 115, 223, 1)",
              pointBorderColor: "rgba(78, 115, 223, 1)",
              pointHoverRadius: 3,
              pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
              pointHoverBorderColor: "rgba(78, 115, 223, 1)",
              pointHitRadius: 10,
              pointBorderWidth: 2,
              data: <?= $json_data; ?>, // Nominal dari PHP
            }],
          },
          options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
              xAxes: [{
                time: { unit: 'date' },
                gridLines: { display: false, drawBorder: false },
                ticks: { maxTicksLimit: 7 }
              }],
              yAxes: [{
                ticks: {
                  maxTicksLimit: 5,
                  padding: 10,
                  callback: function(value, index, values) {
                    return 'Rp ' + number_format(value);
                  }
                },
                gridLines: {
                  color: "rgb(234, 236, 244)",
                  zeroLineColor: "rgb(234, 236, 244)",
                  drawBorder: false,
                  borderDash: [2],
                  zeroLineBorderDash: [2]
                }
              }],
            },
            legend: { display: false },
            tooltips: {
              backgroundColor: "rgb(255,255,255)",
              bodyFontColor: "#858796",
              titleMarginBottom: 10,
              titleFontColor: '#6e707e',
              titleFontSize: 14,
              borderColor: '#dddfeb',
              borderWidth: 1,
              xPadding: 15,
              yPadding: 15,
              displayColors: false,
              intersect: false,
              mode: 'index',
              caretPadding: 10,
              callbacks: {
                label: function(tooltipItem, chart) {
                  var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                  return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                }
              }
            }
          }
        });
    </script>
</body>
</html>
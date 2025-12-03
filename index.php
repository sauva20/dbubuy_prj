<?php
session_start();
// Koneksi DB
include 'config/koneksi.php';

// --- BAGIAN 1: Helper Functions & Classes ---

// Fungsi Ambil ID Youtube dari Link
function getYoutubeID($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
    return isset($match[1]) ? $match[1] : null;
}

// Class Keranjang Belanja
// (cart) menggunakan histori browser n/ db
class CartService {
    public function __construct() {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    }
    public function addToCart($id, $name, $price, $image) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $name,
                'price' => $price,
                'image' => $image,
                'qty' => 1
            ];
        }
    }
    // isset mengisi
    // unset menarik
    
    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    }
    public function getCart() {
        return $_SESSION['cart'];
    }
    public function countItems() {
        return count($_SESSION['cart']);
    }
    public function getTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total; // 2 operasi
    }
}

$cart = new CartService();

// Variable Penanda Login (Default false)
$wajib_login = false;

// --- BAGIAN 2: Handle Form Post ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // LOGIC TAMBAH KERANJANG
    if (isset($_POST['act_add'])) {
        // Cek Login Dulu
        if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
            $wajib_login = true; // Trigger SweetAlert di bawah
        } 
        else {
            // Jika Login Aman, Masuk Keranjang
            $cart->addToCart($_POST['id'], $_POST['name'], $_POST['price'], $_POST['image']);
            header("Location: index.php?status=added#features"); 
            exit;
        }
    }

    // LOGIC HAPUS ITEM
    if (isset($_POST['act_remove'])) {
        $cart->removeFromCart($_POST['id']);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>D'Bubuy Ma'Atik</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
      /* Efek Klik Tombol */
      .btn-active-effect:active { transform: scale(0.95); }

      /* --- NETFLIX STYLE HOVER CARD CSS --- */
      .media-container {
          position: relative;
          padding: 10px;
      }

      /* Item Utama  */
      .media-logo-box {
          background: #fff;
          border-radius: 8px;
          box-shadow: 0 4px 10px rgba(0,0,0,0.05);
          padding: 20px;
          height: 150px;
          display: flex;
          align-items: center;
          justify-content: center;
          border: 1px solid #eee;
          transition: 0.3s;
          cursor: pointer;
      }

      .media-logo-box img {
          max-height: 80px;
          max-width: 100%;
          object-fit: contain;
          filter: grayscale(100%);
          transition: 0.3s;
      }

      /* Saat Hover Logo */
      .media-container:hover .media-logo-box img {
          filter: grayscale(0%);
      }

      /* --- THE POPUP CARD (Hidden by default) --- */
      .media-popup-card {
          display: none;
          position: absolute;
          top: -30%; 
          left: -10%;
          width: 120%;
          background-color: #141414; /* Hitam Netflix */
          color: #fff;
          border-radius: 10px;
          box-shadow: 0 10px 30px rgba(0,0,0,0.5);
          z-index: 999;
          overflow: hidden;
          animation: popUp 0.3s forwards;
      }

      @keyframes popUp {
          0% { opacity: 0; transform: scale(0.8); }
          100% { opacity: 1; transform: scale(1); }
      }

      /* Video Wrapper */
      .popup-video {
          position: relative;
          width: 100%;
          height: 180px;
          background: #000;
      }

      .popup-video iframe {
          width: 100%;
          height: 100%;
          border: none;
      }

      /* Info Text */
      .popup-info { padding: 15px; }
      .popup-info h5 { font-size: 16px; font-weight: 700; margin-bottom: 5px; color: #e50914; }
      .popup-info p { font-size: 11px; color: #b3b3b3; line-height: 1.4; margin-bottom: 10px; }

      .btn-visit {
          background-color: #fff;
          color: #000;
          padding: 5px 15px;
          border-radius: 4px;
          font-size: 12px;
          font-weight: bold;
          text-decoration: none;
          display: flex; align-items: center; gap: 5px;
      }
      .btn-visit:hover { background-color: #e6e6e6; }

      /* Trigger Hover Logic */
      .media-container:hover .media-popup-card {
          display: block;
      }
  </style>
</head>

<body class="index-page">

  <?php include 'partials/navbar.php'; ?>

  <main class="main">

    <section id="hero" class="hero section">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-7 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1>Selamat Datang di D'Bubuy Ma'Atik</h1>
            <p>Rasakan kenikmatan ayam kampung & entog yang dimasak tradisional dengan metode bubuy dalam sekam panas selama 8 jam.</p>
            <div class="d-flex">
              <a href="#features" class="btn-get-started">Lihat Menu</a>
              <a href="https://youtu.be/VSCGS8gSJ2s" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Tonton Video</span></a>
            </div>
          </div>
          <div class="col-lg-5 order-1 order-lg-2 hero-img">
            <img src="assets/img/bubuy-ikan.png" class="piring-putar" alt="Piring">
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="features section">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Katalog Menu</h2>
          <p>Pilihan menu bubuy istimewa resep Ma'Atik</p>
        </div>

        <div class="row gy-4">
          <?php
          if (isset($koneksi)) {
              $q_prod = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
              
              if (mysqli_num_rows($q_prod) > 0) {
                  while ($p = mysqli_fetch_assoc($q_prod)) {
                      $rp = "Rp " . number_format($p['price'], 0, ',', '.');
                      $wa = "https://wa.me/6287775733922?text=Halo%20saya%20mau%20tanya%20terkait%20" . urlencode($p['name']); //custom wa link
          ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
              <div class="product-card">
                <div class="product-image-box">
                  <img src="<?= $p['image']; ?>" alt="<?= $p['name']; ?>" class="img-fluid">
                  <div class="product-description">
                    <p><?= $p['description']; ?></p>
                  </div>
                </div>
                <div class="product-info">
                  <h3><?= $p['name']; ?></h3>
                  <div class="info-footer">
                    <span class="price"><?= $rp; ?></span>
                    <span class="estimation"><i class="bi bi-clock"></i> Est: <?= $p['estimation']; ?></span>
                  </div>
                  
                  <div class="d-flex mt-3 gap-2 align-items-center">
                      <a href="<?= $wa; ?>" target="_blank" class="btn text-white flex-grow-1 d-flex align-items-center justify-content-center btn-active-effect" 
                         style="background-color: #e43c5c; height: 42px; border-radius: 50px; border: none; font-weight: 500;">
                          Tanya Kami
                      </a>
                      
                      <form method="POST" style="margin: 0;">
                          <input type="hidden" name="id" value="<?= $p['id']; ?>">
                          <input type="hidden" name="name" value="<?= $p['name']; ?>">
                          <input type="hidden" name="price" value="<?= $p['price']; ?>">
                          <input type="hidden" name="image" value="<?= $p['image']; ?>">
                          <button type="submit" name="act_add" class="btn text-white d-flex align-items-center justify-content-center btn-active-effect" 
                                  style="background-color: #e43c5c; height: 42px; width: 45px; border-radius: 12px; border: none;">
                              <i class="bi bi-cart-plus fs-5"></i>
                          </button>
                      </form>
                  </div>
                </div>
              </div>
            </div>
          <?php 
                  }
              } else {
                  echo '<div class="col-12 text-center text-muted">Belum ada menu.</div>';
              }
          } 
          ?>
        </div>
      </div>
    </section>

    <section id="liputan-media" class="services section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Liputan Media</h2>
        <p>Sorotan media terhadap kelezatan D'Bubuy Ma'Atik</p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <?php
          if (isset($koneksi)) {
              $q_media = mysqli_query($koneksi, "SELECT * FROM liputan ORDER BY id DESC");
              
              if (mysqli_num_rows($q_media) > 0) {
                  while ($m = mysqli_fetch_assoc($q_media)) { 
                      // Ambil ID Youtube untuk Embed
                      $yt_id = getYoutubeID($m['link_url']);
                      $is_youtube = !empty($yt_id);
                      $embed_src = "";
                      
                      // Link Embed: Autoplay, Mute, No Controls
                      if ($is_youtube) {
                          $embed_src = "https://www.youtube.com/embed/$yt_id?autoplay=1&mute=1&controls=0&modestbranding=1&rel=0&showinfo=0";
                      }
          ?>
            <div class="col-xl-3 col-md-6" data-aos="fade-up">
              <div class="media-container">
                
                <div class="media-logo-box">
                    <img src="<?= $m['image']; ?>" alt="<?= $m['media_name']; ?>">
                </div>

                <div class="media-popup-card">
                    
                    <div class="popup-video">
                        <?php if($is_youtube): ?>
                            <iframe data-src="<?= $embed_src ?>" allow="autoplay; encrypted-media"></iframe>
                        <?php else: ?>
                            <img src="<?= $m['image']; ?>" style="width:100%; height:100%; object-fit:cover; opacity:0.5;">
                        <?php endif; ?>
                    </div>

                    <div class="popup-info">
                        <h5><?= $m['media_name']; ?></h5>
                        <p><?= substr($m['description'], 0, 80); ?>...</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <a href="<?= $m['link_url']; ?>" target="_blank" class="btn-visit">
                                <i class="bi bi-play-fill"></i> Tonton
                            </a>
                            <small class="text-muted" style="font-size:10px;">
                                <?= $is_youtube ? 'YouTube' : 'Artikel'; ?>
                            </small>
                        </div>
                    </div>

                </div>
                </div>
            </div>
          <?php 
                  }
              }
          }
          ?>
        </div>
      </div>
    </section>

    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Lokasi Kami</h2>
      </div>
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 align-items-center">
          <div class="col-lg-6">
            <div class="info-item d-flex align-items-center">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div><h3>Alamat Outlet</h3><p>Jl. Pejuang 45, Subang</p></div>
            </div>
            <div class="info-item d-flex align-items-center mt-3">
              <i class="bi bi-clock flex-shrink-0"></i>
              <div><h3>Jam Operasional</h3><p>Senin - Sabtu: 09:00 - 21:00 WIB</p></div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="map-container" style="border-radius: 15px; overflow: hidden;"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.646630943941!2d107.75558717553719!3d-6.566207993427083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693c9c80847707%3A0xe99d603dc2ca2194!2sD'Bubuy%20Ma%20Atik!5e0!3m2!1sen!2sid!4v1764596308576!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <div class="modal fade" id="modalKeranjang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Keranjang Belanja</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <?php if ($cart->countItems() == 0): ?>
                <p class="text-center text-muted">Keranjang kosong.</p>
            <?php else: ?>
                <table class="table align-middle">
                    <thead><tr><th>Menu</th><th>Harga</th><th>Qty</th><th>Total</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php 
                        foreach ($cart->getCart() as $id => $item): 
                            $subtotal = $item['price'] * $item['qty'];
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= $item['image']; ?>" alt="<?= $item['name']; ?>" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border-radius: 5px;">
                                    <?= $item['name']; ?>
                                </div>
                            </td>
                            <td><?= number_format($item['price'],0,',','.'); ?></td>
                            <td><?= $item['qty']; ?></td>
                            <td><?= number_format($subtotal,0,',','.'); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                    <button type="submit" name="act_remove" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Grand Total</th>
                            <th colspan="2">Rp <?= number_format($cart->getTotal(), 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <?php if ($cart->countItems() > 0): ?>
                <a href="pages/checkout.php" class="btn btn-primary">
                    <i class="bi bi-credit-card"></i> Lanjut Pembayaran
                </a>
            <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <footer id="footer" class="footer light-background">
    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="/" class="logo d-flex align-items-center">
              <span class="sitename">D'Bubuy Ma'Atik</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Jl. Pejuang 45, Subang</p>
              <p>Jawa Barat, Indonesia</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+62 812 3456 7890</span></p>
              <p><strong>Email:</strong> <span>info@dbubuy.com</span></p>
            </div>
          </div>
          
          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#hero">Home</a></li>
              <li><a href="#features">Katalog</a></li>
              <li><a href="#liputan-media">Media</a></li>
              <li><a href="#contact">Kontak</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container">© Copyright <strong><span>D'Bubuy Ma'Atik</span></strong>. All Rights Reserved</div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <?php if($wajib_login): ?>
  <script>
      Swal.fire({
          icon: 'warning',
          title: 'Belum Login',
          text: 'Silakan login terlebih dahulu untuk memesan!',
          showCancelButton: true,
          confirmButtonColor: '#ce1212',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Login Sekarang',
          cancelButtonText: 'Nanti Saja'
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = 'pages/login.php';
          }
      });
  </script>
  <?php endif; ?>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        const mediaContainers = document.querySelectorAll('.media-container');

        mediaContainers.forEach(container => {
            const iframe = container.querySelector('iframe');
            
            if (iframe) {
                const videoSrc = iframe.getAttribute('data-src');

                // Saat Mouse Masuk -> Pasang Link Video (Play)
                container.addEventListener('mouseenter', () => {
                    if(videoSrc) iframe.setAttribute('src', videoSrc);
                });

                // Saat Mouse Keluar -> Cabut Link Video (Stop/Reset)
                container.addEventListener('mouseleave', () => {
                    iframe.setAttribute('src', '');
                });
            }
        });
    });
  </script>

</body>
</html>
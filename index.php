<?php
session_start();
// Koneksi DB (Pastikan path benar)
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>D'Bubuy Ma'Atik</title>

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center">
      <a href="/" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">D'Bubuy Ma'Atik</h1>
      </a>
      <?php require('partials/navbar.php'); ?>
    </div>
  </header>

  <main class="main">

    <section id="hero" class="hero section">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-7 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1>Selamat Datang di D'Bubuy Ma'Atik</h1>
            <p>Rasakan kenikmatan ayam kampung & entog yang dimasak tradisional dengan metode bubuy dalam sekam panas selama 8 jam. Warisan resep leluhur Priangan yang lembut dan kaya rempah.</p>
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
          // Pastikan koneksi ada
          if (isset($koneksi)) {
              // Ambil Produk
              $q_prod = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
              
              if (mysqli_num_rows($q_prod) > 0) {
                  while ($p = mysqli_fetch_assoc($q_prod)) {
                      // Format Rupiah & Link WA
                      $rp = "Rp " . number_format($p['price'], 0, ',', '.');
                      $wa = "https://wa.me/6281234567890?text=Halo%20saya%20mau%20pesan%20" . urlencode($p['name']);
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
                  <a href="<?= $wa; ?>" target="_blank" class="btn-book-now">Booking Sekarang</a>
                </div>
              </div>
            </div>
          <?php 
                  } // End While
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
        <p>D'Bubuy Ma'Atik diliput oleh berbagai media terpercaya</p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <?php
          if (isset($koneksi)) {
              // Ambil Media
              $q_media = mysqli_query($koneksi, "SELECT * FROM liputan ORDER BY id DESC");
              
              if (mysqli_num_rows($q_media) > 0) {
                  while ($m = mysqli_fetch_assoc($q_media)) { 
          ?>
            <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
              <div class="service-item position-relative" style="width:100%;">
                <div class="icon" style="display:flex; justify-content:center; margin-bottom:20px;">
                    <img src="<?= $m['image']; ?>" style="height:50px; object-fit:contain;">
                </div>
                <h4 class="text-center">
                    <a href="<?= $m['link_url']; ?>" target="_blank" class="stretched-link"><?= $m['media_name']; ?></a>
                </h4>
                <p class="text-center text-muted"><?= substr($m['description'], 0, 90); ?>...</p>
                <div class="text-center mt-3" style="color:#e43c5c; font-size:13px; font-weight:600;">
                    BACA ARTIKEL <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </div>
          <?php 
                  }
              } else {
                  echo '<div class="col-12 text-center text-muted">Belum ada data media.</div>';
              }
          }
          ?>
        </div>
      </div>
    </section>

    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Lokasi Kami</h2>
        <p>Kunjungi outlet D'Bubuy Ma'Atik atau pesan via WhatsApp</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4 align-items-center">
          
          <div class="col-lg-6">
            <div class="row gy-4">
              <div class="col-md-12">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt flex-shrink-0"></i>
                  <div>
                    <h3>Alamat Outlet</h3>
                    <p>Jl. Pejuang 45, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211</p> 
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="info-item d-flex align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-clock flex-shrink-0"></i>
                  <div>
                    <h3>Jam Operasional</h3>
                    <p>Senin - Sabtu: 09:00 - 21:00 WIB</p>
                    <p>Minggu: Tutup</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="map-container" data-aos="fade-up" data-aos-delay="500" style="border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.646630943941!2d107.75558717553719!3d-6.566207993427083!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693c9c80847707%3A0xe99d603dc2ca2194!2sD&#39;Bubuy%20Ma%20Atik!5e0!3m2!1sen!2sid!4v1764596308576!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> 
          </div>

        </div>
      </div>
    </section>

  </main>

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
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">
        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>© Copyright <strong><span>D'Bubuy Ma'Atik</span></strong>. All Rights Reserved</div>
        </div>
        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

</body>
</html>
<nav id="navmenu" class="navmenu">
  <ul>
    <li><a href="#hero" class="active">Home</a></li>
    <li><a href="#features">Katalog Menu</a></li>
    <li><a href="#liputan-media">Media</a></li>
    <li><a href="contact">Location</a></li>
    <li><a href="https://wa.link/esi7xm">Kontak</a></li>

    <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
        
        <li class="dropdown">
            <a href="#">
                <span>Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong></span> 
                <i class="bi bi-chevron-down toggle-dropdown"></i>
            </a>
            <ul>
                <?php if (isset($_SESSION['kategori']) && $_SESSION['kategori'] == 'admin'): ?>
                    <li>
                        <a href="backend/admin/index_admin.php" style="color: #e43c5c; font-weight: bold;">
                            Dashboard Admin
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                <?php endif; ?>

                <li><a href="pages/profile.php">Profil Saya</a></li>
                <li><a href="action/logout.php" style="color: red;">Logout</a></li>
            </ul>
        </li>

    <?php else: ?>

        <li><a href="pages/login.php" class="btn-get-started" style="margin-left: 15px;">Login</a></li>

    <?php endif; ?>

  </ul>
  <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
</nav>
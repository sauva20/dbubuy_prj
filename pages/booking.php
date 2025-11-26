<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKING D'BUBUY Ma'atik</title>
    <link rel="stylesheet" href="/assets/css/booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
<body>

    <div class="booking-wrapper">
        <header class="header">
            <span class="page-title">BOOKING</span>
        </header>

        <div class="main-content">
            
            <div class="left-panel">
                
                <div class="restaurant-header">
                    <div class="logo-box">
                        <img src="placeholder-logo.png" alt="Logo D'Bubuy" class="logo-img">
                        <div class="logo-text">
                            <span class="logo-year">2014</span>
                            <span class="logo-name">D'BUBUY</span>
                            <span class="logo-tagline">Ma'atik</span>
                        </div>
                    </div>
                </div>

                <div class="food-card">
                    <div class="food-info-left">
                        <img src="placeholder-bubuy-ayam.jpg" alt="Bubuy Ayam" class="food-image">
                    </div>
                    <div class="food-info-right">
                        <h3 class="food-name">BUBUY AYAM</h3>
                        <p class="food-price">RP 145.000</p>
                        <div class="quantity-control">
                            <button class="qty-btn minus">-</button>
                            <span class="qty-display">2</span>
                            <button class="qty-btn plus">+</button>
                        </div>
                    </div>
                    <span class="food-total-price">RP 290.000</span>
                </div>

                <form class="booking-form">
                    
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" id="nama" class="custom-input" placeholder="..........">
                    </div>

                    <div class="form-group">
                        <label for="nomorhp">Nomor Hp</label>
                        <input type="tel" id="nomorhp" class="custom-input" placeholder="..........">
                    </div>

                    <div class="form-group dropdown-group">
                        <label for="pilihan">Pilihan</label>
                        <select id="pilihan" class="custom-dropdown">
                            <option value="take-away">Take Away</option>
                            <option value="dine-in">Dine In</option>
                        </select>
                        <i class="dropdown-arrow">V</i>
                    </div>

                    <div class="form-group">
                        <label for="catatan">Catatan</label>
                        <textarea id="catatan" class="custom-textarea"></textarea>
                    </div>

                </form>

            </div>

            <div class="right-panel">
                <div class="right-panel-inner">

                    <div class="right-food-image-wrapper">
                        <img src="placeholder-bubuy-ayam.jpg" alt="Bubuy Ayam Besar" class="right-food-image">
                        <span class="food-title-overlay">BUBUY AYAM</span>
                        <div class="curved-bg-right"></div>
                    </div>
                    
                    <div class="payment-methods">
                        <h4>Metode Pembayaran</h4>
                        <div class="method-section">
                            <h5>Transfer Bank</h5>
                            <div class="bank-logos">
                                </div>
                        </div>
                        <div class="method-section">
                            <h5>E-Wallet</h5>
                            <div class="wallet-logos">
                                <span class="logo-placeholder">Dana</span>
                                <span class="logo-placeholder">Gopay</span>
                                <span class="logo-placeholder">OVO</span>
                            </div>
                        </div>
                    </div>

                    <div class="order-details">
                        <div class="detail-row header-row">
                            <span class="detail-label">Rincian Pesanan</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Bubuy Ayam x2</span>
                            <span class="detail-value">Rp 290.000</span>
                        </div>
                        <div class="detail-row total-row">
                            <span class="detail-label">Total Pesanan</span>
                            <span class="detail-value">Rp 290.000</span>
                        </div>
                    </div>

                    <div class="grand-total">
                        <h2 class="total-label">TOTAL PESANAN</h2>
                        <p class="total-amount">RP 290.000</p>
                    </div>

                    <button class="btn-create-order">
                        BUAT PESANAN
                    </button>
                    
                </div>
            </div>
            
            <div class="curved-bg-left-bottom"></div>
        </div>

    </div>

</body>
</html>
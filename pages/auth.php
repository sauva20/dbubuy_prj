<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk & Daftar - D'Bubuy Ma'Atik</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="/assets/css/auth_style.css" rel="stylesheet">
</head>
<body>

    <div class="bubble-container">
        <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
    </div>

    <div class="container" id="container">
        
        <div class="form-container sign-up-container">
            <form action="/action/proses_auth.php" method="POST">
                <input type="hidden" name="action" value="register">
                <h1>Buat Akun</h1>
                
                <div class="social-container">
                    <a href="#" class="social"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social"><i class="bi bi-google"></i></a>
                </div>
                <span>atau daftar manual</span>
                
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required />
                <input type="text" name="no_whatsapp" placeholder="No. WhatsApp" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="password" name="password_repeat" placeholder="Ulangi Password" required />
                
                <button type="submit">Daftar Sekarang</button>

                <p class="mobile-text">Sudah punya akun? <a href="#" id="mobileSignIn">Masuk</a></p>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="/action/proses_auth.php" method="POST">
                <input type="hidden" name="action" value="login">
                <h1>Masuk</h1>
                
                <button type="button" class="btn-wa">
                    <i class="bi bi-whatsapp"></i> Login via WhatsApp
                </button>
                
                <span>atau gunakan akun anda</span>
                
                <input type="text" name="no_whatsapp" placeholder="No. WhatsApp" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Lupa Password Anda?</a>
                
                <button type="submit">Masuk</button>

                <p class="mobile-text">Belum punya akun? <a href="#" id="mobileSignUp">Daftar</a></p>
            </form>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                
                <div class="overlay-panel overlay-left">
                    <h1>Selamat Datang Kembali!</h1>
                    <p>Login dengan akun Anda untuk melanjutkan pesanan lezat Anda.</p>
                    <button class="ghost" id="signIn">Masuk</button>
                </div>

                <div class="overlay-panel overlay-right">
                    <h1>Halo, Sobat Kuliner!</h1>
                    <p>Daftarkan diri Anda dan nikmati berbagai menu spesial D'Bubuy.</p>
                    <button class="ghost" id="signUp">Daftar</button>
                </div>

            </div>
        </div>

    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const mobileSignUp = document.getElementById('mobileSignUp');
        const mobileSignIn = document.getElementById('mobileSignIn');
        const container = document.getElementById('container');

        // Fungsi untuk geser ke Register
        function activateRegister(e) {
            if(e) e.preventDefault();
            container.classList.add("right-panel-active");
        }

        // Fungsi untuk geser ke Login
        function activateLogin(e) {
            if(e) e.preventDefault();
            container.classList.remove("right-panel-active");
        }

        // Event Listeners (Desktop)
        signUpButton.addEventListener('click', activateRegister);
        signInButton.addEventListener('click', activateLogin);

        // Event Listeners (Mobile)
        mobileSignUp.addEventListener('click', activateRegister);
        mobileSignIn.addEventListener('click', activateLogin);
    </script>

</body>
</html>
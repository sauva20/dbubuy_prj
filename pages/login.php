<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link href="/assets/css/login.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


</head>

<body>

    <div class="custom-login-wrapper">

        <div class="left-panel">
            
            <div class="moon-container">
                <div class="circle-grey"></div>
                <div class="circle-white"></div>
            </div>

            <h1 class="login-title">LOGIN ACCOUNT</h1>

            <div class="illustration-box">
                <div class="illustration-content">
                    <i class="fas fa-laptop fa-8x" style="color: #dbeafe;"></i>
                    <i class="fas fa-lock fa-4x" style="color: #ff9aa2; position: absolute; bottom: 10px; right: -15px; text-shadow: 2px 2px 0 #fff;"></i>
                    <div style="position: absolute; top: 35%; width: 100%; text-align: center;">
                        <i class="fas fa-smile fa-3x" style="color: #333;"></i>
                    </div>
                </div>
            </div>

            <div class="left-footer">
                Don't Have an Account? <a href="register.php">Register Now</a>
            </div>
        </div>

        <div class="right-panel">
            
            <div class="form-wrapper">
                <button class="btn-wa">
                    <i class="fab fa-whatsapp"></i> LOGIN WITH WHATSAPP
                </button>

                <form method="POST" action="/action/proses_auth.php">
                    <input type="hidden" name="action" value="login">

                    <div class="input-group-custom">
                        <label>No. WhatsApp<span>*</span></label>
                        <input type="text" name="no_whatsapp" class="input-custom" placeholder="Input Your WhatsApp Number" required>
                    </div>

                    <div class="input-group-custom">
                        <label>Password<span>*</span></label>
                        <input type="password" name="password" class="input-custom" placeholder="Input Your Password" required>
                    </div>

                    <div class="form-actions">
                        <div style="display: flex; align-items: center;">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember" style="margin:0; cursor:pointer;">Remember Me</label>
                        </div>
                        <a href="forgot-password.html" class="forgot-pass">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login-black">LOGIN</button>
                </form>
            </div>

            <div class="corner-curve"></div>
        </div>

    </div>

</body>
</html>
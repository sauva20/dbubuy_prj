<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Page</title>
    <link href="/assets/css/register.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

</head>

<body>

    <div class="register-wrapper">
        
        <div class="left-panel">
            <div class="page-title-top">Register Page</div>

            <form action="/action/proses_auth.php" method="POST">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label>Username<span>*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Input Your Username" required>
                </div>

                <div class="form-group">
                    <label>No WhatsApp<span>*</span></label>
                    <input type="text" name="no_whatsapp" class="form-control" placeholder="Input Your WhatsApp" required>
                </div>

                <div class="form-group">
                    <label>Password<span>*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Input Your Password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password<span>*</span></label>
                    <input type="password" name="password_repeat" class="form-control" placeholder="Confirm Your Password" required>
                </div>

                <div class="form-options">
                    <div>
                        <input type="checkbox" id="remember">
                        <label for="remember" style="display:inline; font-weight:400;">Remember Me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-register">REGISTER</button>
            </form>

            <!-- <div class="bottom-curve"></div>   <!-- style lingkaran bawah kiri -->
        </div> -->

        <div class="right-panel">
            <div class="top-circle-decoration"></div>
 
            <h1 class="right-title">REGISTER ACCOUNT</h1> <div class="image-
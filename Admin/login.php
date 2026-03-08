<?php
// Start session and check if user is already logged in
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is already logged in
if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_email'])) {
    // Redirect to admin panel if already authenticated
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portfolio Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">
    <div class="login-container">
        <div class="login-form-container">
            <div class="login-header">
                <div class="login-logo">
                    <img src="https://img.favpng.com/14/21/1/computer-icons-information-system-administrator-vector-graphics-iconfinder-png-favpng-1402dfSF2bAber4ZLgnmdgQ6J.jpg" alt="Deepak Kumar Admin"
                        style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                </div>
                <h1 class="login-title">Deepak Kumar</h1>
                <p class="login-subtitle">Portfolio Administration System</p>
            </div>

            <form id="login-form">
                <div class="input-group">
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" class="login-form-control" placeholder="Email Address" required>
                    </div>
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>

                <div class="input-group">
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" class="login-form-control" placeholder="Password" required>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    <div class="error-message" id="password-error">Password must be at least 6 characters</div>
                </div>

                <button type="submit" class="login-btn">
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-arrow-right btn-icon"></i>
                </button>
            </form>
        </div>

        <div class="login-illustration">
            <div class="floating-elements">
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
                <div class="floating-circle"></div>
            </div>
            <div class="login-graphic">
                <i class="fas fa-chart-line"></i>
                <i class="fas fa-briefcase"></i>
                <i class="fas fa-rocket"></i>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Log in</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-page">
        <div class="text-overlay">
            <h1 class="title">Escape the chaos of social media</h1>
            <p class="subtitle">Engage in respectful, informed debates on topics that matter</p>
            <a href="howitworks.php" class="info-button">How does Opinion8 work?</a>
        </div>
        <div class="login-box">
            <div class="greeting">
                <img src="../assets/logo.png" alt="Logo" class="logo">
                <h2>Log in</h2>
                <p>Enter your details below to login</p>
            </div>

            <div class="credentials">
                <form action="../actions/login_action.php" method="post">
                    <label for="username">Email</label>
                    <input type="text" name="username" id="username" placeholder="Enter your email" required>
                    <br>
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                        <button type="button" id="toggle-password">
                            <img src="../assets/show.png" alt="Show" id="toggle-icon">
                        </button>
                    </div>
                    <br>
                    <label id="rememberme">
                        <input type="checkbox" name="remember" id="remember"> Remember Me
                    </label>
                    <br>
                    <input type="submit" value="Login" id="login">
                    <p id="registertext">
                        Don't have an account? <a href="register.php">Register</a>
                    </p>
                    <p id="links">
                        <a href="about_us.php" class="text-link">About Us</a> | 
                        <a href="contact_us.php" class="text-link">Contact Us</a> | 
                        <a href="privacy_policy.php" class="text-link">Privacy Policy</a>
                    </p>
                </form>
            </div>
        </div>

        <div class="right-box">
            <img src="../assets/index design.png" alt="image" class="design">
        </div>
    </div>

    <script>
        // script for the show/hide button (eye icon) for password
        document.getElementById('toggle-password').addEventListener('click', function() {
            var passwordField = document.getElementById('password');
            var toggleIcon = document.getElementById('toggle-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.src = '../assets/hidden.png';
            } else {
                passwordField.type = 'password';
                toggleIcon.src = '../assets/show.png';
            }
        });
    </script>
</body>
</html>

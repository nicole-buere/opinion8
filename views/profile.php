<!-- views/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-box"> 
        <div class="greeting"> 
            <h1>Opinion8</h1>
            <h2>Login</h2>
            <p>Enter your details below to login</p>
        </div>

        <div class="credentials">
            <form action="../actions/login_action.php" method="post">
                <label for="username">Email</label>
                <input type="text" name="username" id="username" placeholder="Enter your email" required>
                <br>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <br>
                <label id="rememberme">
                    <input type="checkbox" name="remember" id="remember"> Remember Me
                </label>
                <br>
                <input type="submit" value="Login" id="login">
                <p id="registertext">
                    Don't have an account? <a href="register.php">Register</a>
                </p>
            </form>
        </div>
    </div> 
</body>
</html>

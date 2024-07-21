<!--this is the register page (user creates an account)-->
<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <div class="register-box">
        <div class="greeting">
            <h1>Ignite Your Ideas</h1>
            <p>Join our community and let your voice be heard. 
                <br>
                Register now to dive into lively debates and share your unique perspectives.</p>
        </div>

        <div class="credentials">
            <form action="../actions/register_action.php" method="post">
                <div class="input-row">
                    <label>Role</label>
                    <div class="role-options">
                        <input type="radio" id="user" name="role" value="user" required>
                        <label for="user">User</label>
                        <input type="radio" id="admin" name="role" value="admin" required>
                        <label for="admin">Admin</label>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-column">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    </div>
                    <div class="input-column">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required>
                    </div>
                </div>
                <div class="input-row">
                    <div class="input-column">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    </div>
                    <div class="input-column">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>
                <input type="submit" value="Register" id="register">
            </form>
            <p id="logintext">
                Already have an account? <a href="index.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>

<!-- views/register.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <div class="register-box">
        <div class="greeting">
            <h1>Opinion8</h1>
            <h2>Register</h2>
            <p>Enter your details below to create an account</p>
        </div>

        <div class="credentials">
            <form action="../actions/register_action.php" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <br>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
                <br>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <br>
                <input type="submit" value="Register" id="register">
            </form>
            <p id="logintext">
                Already have an account? <a href="index.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>

<!-- this is the register page (user creates an account) -->
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
            <p id="aboutus">
                Want to learn more? <a href="about_us.php">About Us</a>
            </p>
        </div>
    </div>

    <!-- The Modal -->
    <div id="errorModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="errorMessage"></p>
        </div>
    </div>

    <script>
        // Function to get query parameter by name
        function getQueryParam(name) {
            let urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the error message from query parameters
        let errorMessage = getQueryParam('error');
        if (errorMessage) {
            // Display the modal
            document.getElementById('errorMessage').textContent = errorMessage;
            document.getElementById('errorModal').style.display = 'block';
        }

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName('close')[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            document.getElementById('errorModal').style.display = 'none';
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == document.getElementById('errorModal')) {
                document.getElementById('errorModal').style.display = 'none';
            }
        }
    </script>
</body>
</html>

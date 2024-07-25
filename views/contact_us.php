<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - Opinion8</title>
    <link rel="stylesheet" href="../css/contact_us.css"> 
</head>
<body>
    <div class="container">
        <header>
            <h1>Contact Us</h1>
        </header>

        <main>
            <section>
                <p>We’d love to hear from you! If you have any questions or feedback, feel free to use the form below.</p>
                <form action="../actions/contact_action.php" method="post">
                    <label for="name">Your Name *</label>
                    <input type="text" name="name" id="name" required>
                    
                    <label for="email">Your Email *</label>
                    <input type="email" name="email" id="email" required>
                    
                    <label for="subject">Subject *</label>
                    <input type="text" name="subject" id="subject" maxlength="100" required>
                    
                    <label for="message">Message *</label>
                    <textarea name="message" id="message" maxlength="2000" required></textarea>
                    
                    <label for="attachments">Attachments</label>
                    <input type="file" name="attachments" id="attachments" multiple>
                    
                    <input type="submit" value="Send Message">
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; 2024 Opinion8. All rights reserved.</p>
            <a href="index.php">Back to Login</a>
        </footer>
    </div>
</body>
</html>

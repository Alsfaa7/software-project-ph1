<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/header.css">
    <link rel="stylesheet" href="../Styles/contact.css"> <!-- Link to Contact Us specific styles -->
    <title>Contact Us</title>
</head>
<body>
    <?php include 'Components/header.php'; ?>

    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>If you have any questions, suggestions, or feedback, we'd love to hear from you! Feel free to reach out to us through any of the channels below, and our team will get back to you as soon as possible.</p>

        <div class="contact-details">
            <h2>Our Contact Details</h2>
            <p><i class="bi bi-geo-alt"></i> Address: 123 Fitness Ave, Wellness City, XY 45678</p>
            <p><i class="bi bi-telephone"></i> Phone: +1 (123) 456-7890</p>
            <p><i class="bi bi-envelope"></i> Email: support@nutritionx.com</p>
        </div>

        <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form action="submit_form.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>

        <div class="social-icons">
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
        </div>
    </div>

    <?php include 'Components/footer.php'; ?>
</body>
</html>

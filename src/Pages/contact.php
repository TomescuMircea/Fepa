<!DOCTYPE html>
<html lang="en-US" vocab="https://schema.org/" typeof="WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa contact">
    <meta name="keywords" content="HTML, CSS, JavaScript, contact, contact page, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Contact</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/contact.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body vocab="https://schema.org/" typeof="WebPage">
<?php
include 'header.php';
?>
<main typeof="WebPageElement">
    <div id="background">
        <div id="container">
            <h1>Get in touch</h1>
            <div id="container__contact-info">
                <p>Email: contact@example.com</p>
                <p>Phone: +123456789</p>
                <p>Address: 123 Main St, City, Country</p>
            </div>
            <h2>Send us a message</h2>
            <form id="container__contact-form" action="" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
                <label for="message">Message:</label><br>
                <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>&copy; 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
</body>

</html>
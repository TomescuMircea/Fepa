<!DOCTYPE html>
<html lang="en" vocab="https://schema.org/" typeof="WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa about">
    <meta name="keywords" content="HTML, CSS, JavaScript, about, about page, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Not authenticated</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/about.css" type="text/css">
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
            <h1 property="headline">You can not access this page because you are not authenticated.</h1>
        </div>
    </div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>© 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
</body>

</html>
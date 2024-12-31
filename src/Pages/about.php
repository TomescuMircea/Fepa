<!DOCTYPE html>
<html lang="en" vocab="https://schema.org/" typeof="WebPage">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Fepa about">
  <meta name="keywords" content="HTML, CSS, JavaScript, about, about page, fepa">
  <meta name="author" content="Tomescu Mircea-Andrei">
  <meta name="author" content="Burcă Flavian">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title property="name">About</title>
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
        <h1 property="headline">What is Fepa?</h1>
        <p property="text">
          Fepa is a web application that allows users to report animals in
          distress. The reports are then made available to the community to
          allow for quick response to the animals in need.
        </p>
        <p id="additional-information">
          Find out more about this initiative by reading the documentation <a href="<?php echo BASE_URL; ?>/scholarly/Scholarly.html">here</a>
          or reading the <a href="<?php echo BASE_URL; ?>/index.php/help">help</a> page.
        </p>
        <div id="container__image-grid">
          <img src="<?php echo BASE_URL; ?>/images/dog_in_distress.jpg" alt="image of a dog" property="image">
          <img src="<?php echo BASE_URL; ?>/images/cat_in_distress.jpg" alt="image of a cat" property="image">
        </div>
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
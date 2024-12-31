<!DOCTYPE html>
<html lang="en" vocab="http://schema.org/" typeof="WebPage">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Fepa home web page">
  <meta name="keywords" content="HTML, CSS, JavaScript, homepage, fepa">
  <meta name="author" content="Tomescu Mircea-Andrei">
  <meta name="author" content="Burcă Flavian">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title property="name">Fepa</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/home.css" type="text/css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body id="body" vocab="http://schema.org/" typeof="WebPage">
<?php
  include 'header.php';
?>
  <main typeof="WebPageElement">
    <div id="container">
      <div id="container__welcome-first">
        <h1 property="headline">Help the <em>animals</em>. Help the <em>community</em>.</h1>
      </div>
      <div id="container__welcome-snd">
        <p property="text">Start reporting animals in distress and see current reports.</p>
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
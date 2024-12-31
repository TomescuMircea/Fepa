<!DOCTYPE html>
<html lang="en-US" vocab="https://schema.org/" typeof="WebPage">

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Fepa help">
  <meta name="keywords" content="HTML, CSS, JavaScript, help, help page, fepa">
  <meta name="author" content="Tomescu Mircea-Andrei">
  <meta name="author" content="Burcă Flavian">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title property="name">Help</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/help.css" type="text/css">
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
        <h1 property="headline">Reporting Animals in Distress</h1>

        <p property="text">
          This section provides information on how to effectively report an
          animal in need. If you have any further questions after reviewing
          this section, don't hesitate to visit our
          <a href="<?php echo BASE_URL; ?>/index.php/contact">Contact</a> page.
        </p>

        <section typeof="ItemList">
          <h2 property="headline">What to Report</h2>

          <ul>
            <li>
              Animals left unattended outside in extreme weather conditions (hot
              or cold).
            </li>
            <li>Animals with injuries or visible signs of illness.</li>
            <li>Animals that appear malnourished or dehydrated.</li>
            <li>Animals confined in unsanitary or unsafe conditions.</li>
            <li>Suspected cruelty or neglect.</li>
          </ul>
        </section>
        <section typeof="ItemList">
          <h2>What Information to Include</h2>

          <ul>
            <li>The type of animal (dog, cat, horse, etc.).</li>
            <li>
              The location of the animal (address or detailed description).
            </li>
            <li>The number of animals involved.</li>
            <li>
              A description of the animal's condition (injured, sick,
              neglected).
            </li>
            <li>
              Any other relevant details about the situation (witnesses, owner
              information).
            </li>
          </ul>
        </section>
        <section typeof="ItemList">
          <h2>How to Report</h2>

          <ul>
            <li>
              Visit our <a href="<?php echo BASE_URL; ?>/index.php/reportAnimal">Report</a> section and fill out
              the online form.
            </li>
            <li>
              You can also call us directly at [phone number] (during business
              hours).
            </li>
            <li>
              If the situation is an emergency, please contact your local animal
              control authorities.
            </li>
          </ul>
        </section>
        <br>
        <section>
          <h3>
            Our website offers additional resources that you may find helpful:
          </h3>

          <ul>
            <li>
              See real-time reports of animal welfare concerns in your area (<a href="<?php echo BASE_URL; ?>/index.php/reports">Reports</a>).
            </li>
            <li>
              Learn more about this initiative (<a href="<?php echo BASE_URL; ?>/index.php/about">About</a>
              section).
            </li>
          </ul>
        </section>
        <p id="container__bottom-message" property="text">
          We appreciate your concern for animal welfare and your willingness
          to help animals in need. By reporting suspected animal cruelty or
          neglect, you can make a difference in an animal's life.
        </p>
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